<?php

namespace App\Presenters;

/**
 * 
 */
class RecordPresenter extends BasePresenter
{
    
    /** @persistent */
    public $backlink = '';
    
    /** @var \App\Model\RecordModel @inject */
    public $records;  // Slouzi k overeni recordu pri editaci, ci mazani. Jine modely si musi resit komponenty.
    
    /** @var \App\Model\AthleteModel @inject */
    public $athletes;

    /** @var \App\Model\SessionModel @inject */
    public $sessions;

    /** @var \App\Model\TestModel @inject */
    public $tests;
    
    /** @var \App\Components\Record\IEntityControlFactory @inject */
    public $entityControlFactory;
    
    /**
     * @secured
     * @resource('record')
     * @privilege('add')
     */
    public function actionNew($athlete_id, $session_id, $test_id)
    {
        if ($athlete_id) {
            $athlete = $this->loadItem($this->athletes, $athlete_id);
        }
        
        if ($session_id) {
            $session = $this->loadItem($this->sessions, $session_id);
        }
        
        if ($test_id) {
            $test = $this->loadItem($this->tests, $test_id);
        }
        
        $this['entity']->setParams($athlete_id, $session_id, $test_id);
        
    }
    
    /**
     * @secured
     * @resource('record')
     * @privilege('detail')
     */
    public function actionDetail($id)
    {
        $record = $this->loadItem($this->records, $id);
        $this->template->record = $record;
        $this['entity']->entity = $record;
    }
    
    
    /**
     * @secured
     * @resource('record')
     * @privilege('remove')
     */
    public function actionRemove($id)
    {
        
    }
    
    
    ///// Components /////

    /** @return \App\Components\Record\EntityControl */
    protected function createComponentEntity()
    {
        return $this->entityControlFactory->create();
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
