<?php

namespace App\Components\Athlete;

/**
 * Athlete entity from control
 * @property \App\Entities\Athlete $entity
 */
class FormControl extends \App\Components\Base\FormControl
{    

    /** @var \App\Model\RoleModel */
    private $roles;
    
    public function __construct(\App\Model\AthleteModel $model, \App\Model\RoleModel $roles) {
        $this->model = $model;
        $this->roles = $roles;
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{   
		$form = new \Nette\Application\UI\Form();
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
        
		$form->addText('firstname', 'Jméno:')
			->setRequired('Please enter your firstname.');
        
		$form->addText('surname', 'Příjmení:')
			->setRequired('Please enter your surname.');
        
        $form->addSelect('sex', 'Pohlaví:', [
                'male' => 'Muž',
                'female' => 'Žena'
            ]);
        
        $form->addText('birthdate', 'Datum narození:')
			->setRequired('Please enter your date of birth.');
        
        $form->addText('email', 'E-mail:')
			->setRequired('Please enter your e-mail address.');
        
		$form->addSubmit('save', 'Uložit');
        $form->addButton('back', 'Zrušit')
                ->setAttribute('class', 'cancel');
        $form->addButton('reset')->getControlPrototype()->addAttributes(['type' => 'reset']);
		
        // Call on success
		$form->onSuccess[] = $this->formSucceeded;
        
        return $form;
	}

	public function formSucceeded(\Nette\Application\UI\Form $form)
	{
        // 1) Load data from form
		$values = $form->getValues();
        
        // 2) Recognize add or edit of record
        if (!$this->entity) {
            $this->entity = new \App\Entities\Athlete();
        
            $role = $this->roles->findByName('athlete');
            $this->entity->addRole($role);
            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }

        // 3) Map data from form to entity
        //$this->entity->username = $values->username; // TODO: Check if username is unique! -> Integration violation - in form of course!        
        $this->entity->password = "$2y$10$6y7exPNgXqn/T6nvBeYOz.pnDYHj6Q.xy6h1EheJvUW2lbXBEyx8O"; // 'heslo';
        $this->entity->firstname = $values->firstname;
        $this->entity->surname = $values->surname;
        $this->entity->birthdate = \Nette\DateTime::from($values->birthdate);
        $this->entity->sex = $values->sex;
        $this->entity->email = $values->email;
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('default');
	}
    
    public function setEntity(\App\Entities\Athlete $entity)
    {
        $this->entity = $entity;
        $this['form']->setDefaults($this->loadDefaults());
    }
    
    private function loadDefaults()
    {
        return [
            'username' => $this->entity->username,
            'firstname' => $this->entity->firstname,
            'surname' => $this->entity->surname,
            'birthdate' => $this->entity->birthdate->format('j.n.Y'),
            'sex' => $this->entity->sex,
            'email' => $this->entity->email
        ];
    }
}