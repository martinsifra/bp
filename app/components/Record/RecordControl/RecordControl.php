<?php

namespace App\Components\Record;

/**
 * Record control
 * @property \App\Entities\Record $entity
 */
class RecordControl extends \App\Components\Base\RecordControl
{    
    
    /** @var \App\Model\AthleteModel */
    private $athletes;

    /** @var \App\Model\SessionModel */
    private $sessions;

    /** @var \App\Model\TestModel */
    private $tests;
    
    public function __construct(\App\Model\RecordModel $model, \App\Model\AthleteModel $athletes, \App\Model\SessionModel $sessions, \App\Model\TestModel $tests) {
        $this->model = $model;
        $this->athletes = $athletes;
        $this->sessions = $sessions;
        $this->tests = $tests;
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/RecordControl.latte');
        $template->render();
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new \BootstrapForm();

        $form->addSelect('session_id', 'Session:', $this->sessions->findPairs('title', 'id'));
        
        $form->addSelect('athlete_id', 'Athlete:', $this->athletes->findPairs('username', 'id'));
//                ->setDefaultValue($this->defaults['user_id']);
        
        $form->addSelect('test_id', 'Test:', $this->tests->findPairs('label', 'id'));
        
        $form->addText('value', 'Value:');
        
		$form->addSubmit('save', 'Save');
        
		// Call on success
		$form->onSuccess[] = $this->formSucceeded;
        
        return $form;
	}

	public function formSucceeded(\Nette\Application\UI\Form $form)
	{
        // 1) Load data from form
		$values = $form->getValues();

        // TODO: Check all id's validity (user, session, test)
        $athlete = $this->athletes->find($values->athlete_id);
        $session = $this->sessions->find($values->session_id);
        $test = $this->tests->find($values->test_id);
        
        
        // 2) Recognize add or edit of record
        if (!$this->entity) {
            $this->entity = new \App\Entities\Record();
            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }

        // 3) Map data from form to entity
        $this->entity->athlete = $athlete;
        $this->entity->session = $session;
        $this->entity->test = $test;
        $this->entity->value = $values->value;
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('Athlete:');
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