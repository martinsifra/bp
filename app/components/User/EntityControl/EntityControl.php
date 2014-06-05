<?php

namespace App\Components\User;

/**
 * Athlete entity from control
 * @property \App\Entities\Athlete $entity
 */
class EntityControl extends \App\Components\Base\EntityControl
{    
    const REDIRECT = 'User:';

    /** @var \App\Model\RoleModel */
    private $roles;
    
    public function __construct(\App\Model\UserModel $model, \App\Model\RoleModel $roles) {
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
        
        $form->addText('email', 'E-mail:')
			->setRequired('Please enter your e-mail address.');
        
        $form->addText('phone', 'Telefon:');
        
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
        $this->toEntity($values);
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->save([], 'User:');
	}

    
    protected function toArray()
    {
        return [
            'username' => $this->entity->username,
            'firstname' => $this->entity->firstname,
            'surname' => $this->entity->surname,
            'email' => $this->entity->email,
            'phone' => $this->entity->phone,
        ];
    }
    
    
    protected function toEntity($values)
    {   
        $this->entity->firstname = $values->firstname;
        $this->entity->surname = $values->surname;
        $this->entity->email = $values->email;
        $this->entity->phone = $values->phone;
    }
}