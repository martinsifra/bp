<?php

namespace App\Presenters;

/**
 * 
 */
class RecordPresenter extends BasePresenter
{
    /** @var \App\Model\RecordModel @inject */
    public $records;  // Slouzi k overeni recordu pri editaci, ci mazani. Jine modely si musi resit komponenty.
    
    /** @var \App\Components\Record\IRecordControlFactory @inject */
    public $recordControlFactory;
    
    /**
     * @secured
     * @resource('record')
     * @privilege('add')
     */
    public function actionNew($athlete_id, $session_id, $test_id)
    {

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
        $this['record']->entity = $record;
    }
    
    ///// Components /////

    /** @return \App\Components\Record\RecordControl */
    protected function createComponentRecord()
    {
        return $this->recordControlFactory->create();
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
