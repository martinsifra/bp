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
     * @privilege('show')
     */
    public function actionDetail($id)
    {
        $athlete = $this->loadItem($this->athletes, $id);
        $this->template->athlete = $athlete;
        $this['entity']->entity = $athlete;

        $this['sessionGrid']->setParams($id);
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
     * @privilege('show')
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
    
    public function actionTest($id, $test_id)
    {
        $this->template->athlete = $this->loadItem($this->athletes, $id);
        $this->template->test = $this->loadItem($this->tests, $test_id);
        
        $this['testGrid']->setParams($id, $test_id);
        
        $records = $this->records->findBy([
                'athlete' => $id,
                'test' => $test_id
            ],
            [
                'created' => 'ASC'
            ]);
        
        $labels = [];
        $data = [];
        
        foreach ($records as $record) {
            $labels[] = [
                'name' => $record->session->name,
                'created' => $record->created
            ];
            $data[] = $record->value;
        }
        
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
