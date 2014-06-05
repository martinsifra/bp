<?php

namespace App\Presenters;

/**
 * 
 */
class TestPresenter extends BasePresenter
{

    /** @var \App\Model\TestModel @inject */
    public $tests;
    
    /** @var \App\Components\Test\IEntityControlFactory @inject */
    public $entityControlFactory;

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
        $test = $this->loadItem($this->tests, $id);
        $this->template->test = $test;
        $this['entity']->entity = $test;
    }
    
    /**
     * @secured
     * @resource('test')
     * @privilege('delete')
     */
    public function actionDelete($id)
    {
        $test = $this->loadItem($this->tests, $id);
    }
    
    
    ///// Components /////

    /** @return \App\Components\Test\EntityControl*/
    protected function createComponentEntity()
    {
        return $this->entityControlFactory->create();
    }    
    
    /** @return \App\Components\Test\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }

}
