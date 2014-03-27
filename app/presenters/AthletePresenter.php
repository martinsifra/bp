<?php

namespace App\Presenters;

/**
 * 
 */
class AthletePresenter extends BasePresenter
{
    
    /** @var \App\Model\AthleteModel @inject */
    public $athletes;
    
    /** @var \App\Components\Athlete\IRecordControlFactory @inject */
    public $recordControlFactory;

    /** @var \App\Components\Athlete\IGridControlFactory @inject */
    public $gridControlFactory;
    
    
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
        $athlete = $this->loadItem($id);
        $this->template->athlete = $athlete;
        $this['record']->entity = $athlete;
    }
    
    /**
     * @secured
     * @resource('athlete')
     * @privilege('edit')
     */
    public function actionEdit($id)
    {
        $athlete = $this->loadItem($id);
        $this->template->athlete = $athlete;
        $this['record']->entity = $athlete;
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

    /** @return \App\Components\Athlete\RecordControl */
    protected function createComponentRecord()
    {
        return $this->recordControlFactory->create();
    }    
    
    /** @return \App\Components\Athlete\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }

    
    //// Other methods ////
    
    /**
     * 
     * @param type $id
     * @return \App\Entities\Athlete
     */
    protected function loadItem($id)
    {
        $item = $this->athletes->find($id);
        
        if (!$item) {
            $this->flashMessage("Item with id $id does not exist", 'warning');
            $this->redirect('default'); // aka items list
        }
        return $item;
    }
    
    
    /**
     * 
     * @param \App\Entities\Athlete $entity
     * @return array
     */
    public function loadDefaults(\App\Entities\Athlete $athlete)
    {
        return [
            'firstname' => $athlete->firstname,
            'surname' => $athlete->surname
        ];
    }
}
