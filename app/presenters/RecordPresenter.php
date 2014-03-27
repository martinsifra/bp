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
    
    ///// Components /////

    /** @return \App\Components\Record\RecordControl */
    protected function createComponentRecord()
    {
        return $this->recordControlFactory->create();
    }
}
