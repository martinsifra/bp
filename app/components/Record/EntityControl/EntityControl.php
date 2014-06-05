<?php

namespace App\Components\Record;

/**
 * Entity Control
 * @property \App\Entities\Record $entity
 */
class EntityControl extends \App\Components\Base\EntityControl
{
    const REDIRECT = 'Athlete:session';
    
    /** @var \App\Model\AthleteModel */
    private $athletes;

    /** @var \App\Model\SessionModel */
    private $sessions;

    /** @var \App\Model\TestModel */
    private $tests;
    
    /** @var int */
    private $athlete_id;
    
    /** @var int */
    private $session_id;
    
    /** @var int */
    private $test_id;
    
    
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
		$this->form = new \Nette\Application\UI\Form();
        $this->form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
        
        $this->form->addSelect('session_id', 'Měření:', $this->sessions->findPairs('name', 'id'))
                ->setDisabled($this->entity || $this->session_id)
                ->setDefaultValue($this->session_id);
        
        $this->form->addSelect('athlete_id', 'Závodník:', $this->athletes->forSelect())
                ->setDisabled($this->entity || $this->athlete_id)
                ->setDefaultValue($this->athlete_id);
        
        $this->form->addSelect('test_id', 'Test:', $this->tests->findPairs('name', 'id'))
                ->setDisabled($this->entity || $this->test_id)
                ->setDefaultValue($this->test_id);
                
        $this->form->addText('value', 'Hodnota:');
        
		$this->form->addSubmit('save', 'Uložit');
        
		// Call on success
		$this->form->onSuccess[] = $this->formSucceeded;
        
        return $this->form;
	}

	public function formSucceeded(\Nette\Application\UI\Form $form)
	{
        // 1) Load data from form
		$values = $form->getValues();

        // 2) Recognize add or edit of record
        if (!$this->entity) {
            $this->entity = new \App\Entities\Record();
            
            if ($this->athlete_id) {
                $values->athlete_id = $this->athlete_id;
            }
            
            if ($this->session_id) {
                $values->session_id = $this->session_id;
            }
                        
            if ($this->test_id) {
                $values->test_id = $this->test_id;
            }
            
            // TODO: Check all id's validity (athlete, session, test) - only when new record
            $athlete = $this->athletes->find($values->athlete_id);
            $session = $this->sessions->find($values->session_id);
            $test = $this->tests->find($values->test_id);
            
            $this->entity->athlete = $athlete;
            $this->entity->session = $session;
            $this->entity->test = $test;
            $this->entity->created = new \DateTime();
            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }

        // 3) Map data from form to entity
        $this->toEntity($values);
        
        \Nette\Diagnostics\Debugger::barDump($this->entity->value);
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->save([$this->entity->athlete->id, $this->entity->session->id], 'Athlete:session');
	}
    
    protected function toEntity($values)
    {
        $this->entity->value = $values->value;
    }


    protected function toArray()
    {
        return [
            'session_id' => $this->entity->session->id,
            'athlete_id' => $this->entity->athlete->id,
            'test_id' => $this->entity->test->id,
            'value' => $this->entity->value
        ];
    }
    
    public function setParams($athlete_id, $session_id, $test_id)
    {
        $this->athlete_id = $athlete_id;
        $this->session_id = $session_id;
        $this->test_id = $test_id;
    }
}