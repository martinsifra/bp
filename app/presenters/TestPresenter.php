<?php

namespace App\Presenters;

/**
 * 
 */
class TestPresenter extends BasePresenter
{

    /** @var \App\Model\TestModel @inject */
    public $tests;
    
    /** @var \App\Components\Test\IRecordControlFactory @inject */
    public $recordControlFactory;

    /** @var \App\Components\Test\IGridControlFactory @inject */
    public $gridControlFactory;
    
    
    ///// Actions /////
    
    /**
     * @secured
     * @resource('test')
     * @privilege('default')
     */
    public function actionDefault()
    {
        
    }
    
    /**
     * @secured
     * @resource('test')
     * @privilege('add')
     */
    public function actionNew()
    {
        
    }
    
    /**
     * @secured
     * @resource('test')
     * @privilege('show')
     */
    public function actionDetail($id)
    {
        $test = $this->loadItem($id);
        $this->template->test = $test;
        $this['record']->entity = $test;
    }
    
    
    ///// Components /////

    /** @return \App\Components\Test\RecordControl */
    protected function createComponentRecord()
    {
        return $this->recordControlFactory->create();
    }    
    
    /** @return \App\Components\Test\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }
    
    
    //// Other methods ////
    
    /**
     * @param type $id
     * @return \App\Entities\Test
     */
    protected function loadItem($id)
    {
        $item = $this->tests->find($id);
        
        if (!$item) {
            $this->flashMessage("Item with id $id does not exist", 'warning');
            $this->redirect('default'); // Items list
        }
        return $item;
    }
}
