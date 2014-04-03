<?php

namespace App\Components\Session;

/**
 * Athlete record control
 * @property \App\Entities\Session $entity
 */
class RecordControl extends \App\Components\Base\FormControl
{    
    
    public function __construct(\App\Model\SessionModel $model) {
        $this->model = $model;
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{   
		$form = new \BootstrapForm();

        if ($this->entity) {
            $form->addText('username', 'Username:')
                    ->setDisabled();
        }
        
		$form->addText('firstname', 'Firstname:')
			->setRequired('Please enter your firstname.');
        
		$form->addText('surname', 'Surname:')
			->setRequired('Please enter your surname.');
        
        $form->addText('birthdate', 'Birthdate:')
			->setRequired('Please enter your date of birth.');

        $form->addText('email', 'E-mail:')
			->setRequired('Please enter your e-mail address.');
        
		$form->addSubmit('save', 'Save');
        
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
            'birthdate' => $this->entity->birthdate->format('Y-m-d'),
            'email' => $this->entity->email
        ];
    }
}