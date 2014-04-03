<?php

namespace App\Presenters;

/**
 * 
 */
class SessionPresenter extends BasePresenter
{
    
    /** @var \App\Model\SessionModel @inject */
    public $sessions;
    
    /** @var \App\Model\AthleteModel @inject */
    public $athletes;
    
    /** @var \App\Model\RecordModel @inject */
    public $records;

    /** @var \App\Components\Session\IRecordControlFactory @inject */
    public $recordControlFactory;

    /** @var \App\Components\Session\IGridControlFactory @inject */
    public $gridControlFactory;
 
    /** @var \App\Components\Session\IDetailGridControlFactory @inject */
    public $detailGridControlFactory;   
    
    ///// Actions /////
    
    /**
     * @secured
     * @resource('session')
     * @privilege('default')
     */
    public function actionDefault()
    {
        
    }
    
    
    public function actionDetail($id)
    {
        $session = $this->loadItem($this->sessions, $id);
        $this->template->session = $session;
        $this['detailGrid']->session = $session;
    }
    
    
    ///// Components /////

    /** @return \App\Components\Session\RecordControl */
    protected function createComponentRecord()
    {
        return $this->recordControlFactory->create();
    }
    
    /** @return \App\Components\Session\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }
    
    /** @return \App\Components\Session\DetailGridControl */
    protected function createComponentDetailGrid()
    {
        return $this->detailGridControlFactory->create();
    }
}
