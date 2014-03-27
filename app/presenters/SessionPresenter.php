<?php

namespace App\Presenters;

/**
 * 
 */
class SessionPresenter extends BasePresenter
{
    
    /** @var \App\Model\SessionModel @inject */
    public $sessions;
    
    /** @var \App\Components\Session\IRecordControlFactory @inject */
    public $recordControlFactory;

    /** @var \App\Components\Session\IGridControlFactory @inject */
    public $gridControlFactory;
    
    
    ///// Actions /////
    
    /**
     * @secured
     * @resource('session')
     * @privilege('default')
     */
    public function actionDefault()
    {
        
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
}
