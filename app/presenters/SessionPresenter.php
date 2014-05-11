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

    /** @var \App\Components\Session\IEntityControlFactory @inject */
    public $entityControlFactory;

    /** @var \App\Components\Session\IGridControlFactory @inject */
    public $gridControlFactory;

    /** @var \App\Components\Session\IDetailGridControlFactory @inject */
    public $detailGridControlFactory;

    /** @var \App\Components\Session\IImportControlFactory @inject */
    public $importControlFactory;
    
    
    ///// Actions /////

    /**
     * @secured
     * @resource('session')
     * @privilege('default')
     */
    public function actionDefault()
    {

    }

    /**
     * @secured
     * @resource('session')
     * @privilege('show')
     */
    public function actionDetail($id)
    {
        $session = $this->loadItem($this->sessions, $id);
        $this->template->session = $session;
        $this['detailGrid']->session = $session;
    }

    /**
     * @secured
     * @resource('session')
     * @privilege('import')
     */
    public function actionImport($id)
    {
        $session = $this->loadItem($this->sessions, $id);
        $this->template->session = $session;
        $this['import']->session = $session;
    }


    ///// Components /////

    /** @return \App\Components\Session\EntityControl */
    protected function createComponentEntity()
    {
        return $this->entityControlFactory->create();
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
    
    /** @return \App\Components\Session\DetailGridControl */
    protected function createComponentImport()
    {
        return $this->importControlFactory->create();
    }
}
