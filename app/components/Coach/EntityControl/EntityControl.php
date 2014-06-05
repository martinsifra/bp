<?php

namespace App\Components\Coach;

/**
 * Athlete record control
 * @property \App\Entities\Session $entity
 */
class EntityControl extends \App\Components\Base\EntityControl
{    
    
    /** @var \App\Model\AthleteModel */
    private $athletes;
    
    public function __construct(\App\Model\CoachModel $model, \App\Model\AthleteModel $athletes) {
        parent::__construct();
        $this->model = $model;
        $this->athletes = $athletes;
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

        $form->addMultiSelect('athletes', 'Závodníci:', $this->athletes->toMultiSelector())
                ->getControlPrototype()->style('height', '300px');
        
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
            $this->entity = new \App\Entities\Coach();
            $this->entity->user = new \App\Entities\User();
            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }


        // 3) Map data from form to entity
        $this->toEntity($values);
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('default');
	}

    
    protected function toArray()
    {
        $defaults = [];      
        foreach ($this->entity->athletes as $athlete) {
            $defaults[] = $athlete->id;
        }
        
        return [
            'firstname' => $this->entity->user->firstname,
            'surname' => $this->entity->user->surname,
            'athletes' => $defaults
        ];
    }
    
    
    protected function toEntity($values)
    {
        $new = $this->athletes->findBy(['id' => $values->athletes]);
        $old = $this->entity->athletes;
        
        $old_id = [];
        foreach ($old as $athlete) {
            $old_id[] = $athlete->id;
        }
        
        foreach ($new as $athlete) {
            if (!in_array($athlete->id, $old_id)) {
                $this->entity->addAthlete($athlete);
            } else {
                $old_id = array_diff($old_id, [$athlete->id]);
            }
        }
        
        foreach ($old as $athlete) {
            if (in_array($athlete->id, $old_id)) {
                $this->entity->removeAthlete($athlete);
            }
        }
        
        
        $this->entity->user->firstname = $values->firstname;
        $this->entity->user->surname = $values->surname;
    }
}