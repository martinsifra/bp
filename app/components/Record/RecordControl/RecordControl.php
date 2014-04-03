<?php

namespace App\Components\Record;

/**
 * Record control
 * @property \App\Entities\Record $entity
 */
class RecordControl extends \App\Components\Base\FormControl
{    
    
    /** @var \App\Model\AthleteModel */
    private $athletes;

    /** @var \App\Model\SessionModel */
    private $sessions;

    /** @var \App\Model\TestModel */
    private $tests;

    
    public function __construct(\App\Model\RecordModel $model, \App\Model\AthleteModel $athletes, \App\Model\SessionModel $sessions, \App\Model\TestModel $tests)
    {
        $this->model = $model;
        $this->athletes = $athletes;
        $this->sessions = $sessions;
        $this->tests = $tests;
    }

    
	/** @return Nette\Application\UI\Form */
	protected function createComponentForm()
	{
		$form = new \Nette\Application\UI\Form();
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
        
        $form->addSelect('session_id', 'Měření:', $this->sessions->findPairs('title', 'id'))
                ->setDisabled($this->entity);
        
        $form->addSelect('athlete_id', 'Závodník:', $this->athletes->findPairs('username', 'id'))
                ->setDisabled($this->entity);
        
        $form->addSelect('test_id', 'Test:', $this->tests->findPairs('name', 'id'))
                ->setDisabled($this->entity);
                
        $form->addText('value', 'Hodnota:');
        
		$form->addSubmit('save', 'Uložit');
        
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
            $this->entity = new \App\Entities\Record();

            // TODO: Check all id's validity (user, session, test) - only when new record
            $athlete = $this->athletes->find($values->athlete_id);
            $session = $this->sessions->find($values->session_id);
            $test = $this->tests->find($values->test_id);
            
            $this->entity->athlete = $athlete;
            $this->entity->session = $session;
            $this->entity->test = $test;
            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }

        // 3) Map data from form to entity
        $this->entity->value = $values->value;
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('Athlete:session', [$this->entity->athlete->id, $this->entity->session->id]);
	}
    
    public function setEntity(\App\Entities\Record $entity)
    {
        $this->entity = $entity;
        $this['form']->setDefaults($this->loadDefaults());
    }
    
    /** @return array */
    private function loadDefaults()
    {
        return [
            'session_id' => $this->entity->session->id,
            'athlete_id' => $this->entity->athlete->id,
            'test_id' => $this->entity->test->id,
            'value' => $this->entity->value
        ];
    }
}