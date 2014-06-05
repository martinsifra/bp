<?php

namespace App\Presenters;

/**
 *
 */
class AthletePresenter extends BasePresenter
{

    /** @var \App\Model\AthleteModel @inject */
    public $athletes;

    /** @var \App\Model\SessionModel @inject */
    public $sessions;

    /** @var \App\Model\RecordModel @inject */
    public $records;

    /** @var \App\Model\testModel @inject */
    public $tests;

    /** @var \App\Components\Athlete\IEntityControlFactory @inject */
    public $entityControlFactory;

    /** @var \App\Components\Athlete\IGridControlFactory @inject */
    public $gridControlFactory;

    /** @var \App\Components\Athlete\IRecordGridControlFactory @inject */
    public $recordGridControlFactory;

    /** @var \App\Components\Athlete\ISessionGridControlFactory @inject */
    public $sessionGridControlFactory;

    /** @var \App\Components\Athlete\ITestGridControlFactory @inject */
    public $testGridControlFactory;

    /** @var \App\Components\Athlete\ICoachGridControlFactory @inject */
    public $coachGridControlFactory;

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $em;
    

    ///// Actions /////

    /**
     * @secured
     * @resource('athlete')
     * @privilege('default')
     */
    public function actionDefault()
    {

    }

    /**
     * @secured
     * @resource('athlete')
     * @privilege('detail')
     */
    public function actionDetail($id)
    {
        $athlete = $this->loadItem($this->athletes, $id);
        $this->template->athlete = $athlete;

        if (!$this->user->isInRole('admin')) {
            if ($this->user->isInRole('coach')) {

            } elseif ($this->user->isInRole('athlete')) {
                $atlet = $this->athletes->findOneBy(['user.id' => $this->user->id]);

                if ($athlete->id != $atlet->id) {
                    throw new Nette\Application\ForbiddenRequestException;
                }                
            }
        }
        
        $this['sessionGrid']->setParams($id);
        $this['coachGrid']->setParams($athlete->id);
    }

    /**
     * @secured
     * @resource('athlete')
     * @privilege('edit')
     */
    public function actionEdit($id)
    {
        $athlete = $this->loadItem($this->athletes, $id);
        $this->template->athlete = $athlete;
        $this['entity']->entity = $athlete;
    }

    /**
     * @secured
     * @resource('athlete')
     * @privilege('session')
     */
    public function actionSession($id, $session_id)
    {
        $this->template->athlete = $this->loadItem($this->athletes, $id);
        $this->template->session = $this->loadItem($this->sessions, $session_id);

        $this['recordGrid']->setParams($id, $session_id);

        $this->template->records = $this->records->findBy([
            'athlete' => $id,
            'session' => $session_id,
        ]);
    }

    /**
     * @secured
     * @resource('athlete')
     * @privilege('test')
     */
    public function actionTest($id, $test_id)
    {
        $this->template->athlete = $this->loadItem($this->athletes, $id);
        $this->template->test = $this->loadItem($this->tests, $test_id);



        // Get sessions to plot
        $records = $this->records->findBy([
                'athlete' => $id,
                'test' => $test_id
            ],
            [
                'created' => 'ASC'
            ]);

        
        // Get records to each session
        $sessions = [];
        foreach ($records as $record) {
            $sessions[$record->id] = $this->records->findBy(['session' => $record->session->id]);
        }

        // Loading all tests
        $testsDao = $this->em->getDao(\App\Entities\Test::getClassName());
        $tests = $testsDao->findAll();
        
        $results = [];
        
        foreach ($sessions as $key => $session) {
            try {
                $results[$key] = $this->tests->evaluate($tests, $session);
            } catch (\M\ParserException $e) {
                \Nette\Diagnostics\Debugger::barDump($e->getMessage());
                $this->presenter->flashMessage('Výpočet hodnot záznamů obsahuje chyby. Čekejte na opravu administrátorem.', 'warning');
                $this->getPresenter()->forward('Athlete:detail', $this->athlete_id);
            }
        }
        
        \Nette\Diagnostics\Debugger::barDump($results);

        $labels = [];
        $data = [];

        foreach ($records as $record) {
            $labels[] = [
                'name' => $record->session->name,
                'created' => $record->created ? $record->created->format('j.n.Y') : ''
            ];
            $data[] = $results[$record->id][$record->test->slug]['value'];
        }
        
        $this['testGrid']->setParams($results, $records);
        
        $this->template->labels = $labels;
        $this->template->data = $data;
    }

    /**
     * @secured
     * @resource('athlete')
     * @privilege('remove')
     */
    public function handleRemove($id)
    {
        $athlete = $this->loadItem($id);
    }

    /**
     * @secured
     * @resource('athlete')
     * @privilege('restore')
     */
    public function handleRestore($id)
    {
        $athlete = $this->loadItem($id);
    }


    ///// Components /////

    /** @return \App\Components\Athlete\EntityControl */
    protected function createComponentEntity()
    {
        return $this->entityControlFactory->create();
    }

    /** @return \App\Components\Athlete\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }

    /** @return \App\Components\Athlete\RecordGridControl */
    protected function createComponentRecordGrid()
    {
        return $this->recordGridControlFactory->create();
    }

    /** @return \App\Components\Athlete\SessionGridControl */
    protected function createComponentSessionGrid()
    {
        return $this->sessionGridControlFactory->create();
    }

    /** @return \App\Components\Athlete\TestGridControl */
    protected function createComponentTestGrid()
    {
        return $this->testGridControlFactory->create();
    }

    /** @return \App\Components\Athlete\CoachGridControl */
    protected function createComponentCoachGrid()
    {
        return $this->coachGridControlFactory->create();
    }

    //// Other methods ////

    /**
     * @param \App\Model\BaseModel $model
     * @param int $id
     * @return \Kdyby\Doctrine\Entities\IdentifiedEntity
     */
    protected function loadItem($model, $id)
    {
        $item = $model->find($id);

        if (!$item) {
            $this->flashMessage("Item with id $id does not exist", 'warning');
            $this->redirect('default');
        }
        return $item;
    }
}
