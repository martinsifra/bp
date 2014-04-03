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
    
    /** @var \App\Components\Athlete\IFormControlFactory @inject */
    public $formControlFactory;

    /** @var \App\Components\Athlete\IGridControlFactory @inject */
    public $gridControlFactory;

    /** @var \App\Components\Athlete\IRecordGridControlFactory @inject */
    public $recordGridControlFactory;
    
    /** @var \App\Components\Athlete\ISessionGridControlFactory @inject */
    public $sessionGridControlFactory;
    
    
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
        $this['form']->entity = $athlete;

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
        $this['form']->entity = $athlete;
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

    /** @return \App\Components\Athlete\FormControl */
    protected function createComponentForm()
    {
        return $this->formControlFactory->create();
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
