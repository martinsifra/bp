<?php

namespace App\Presenters;

/**
 *
 */
class CoachPresenter extends BasePresenter
{

    /** @var \App\Model\CoachModel @inject */
    public $coaches;

    /** @var \App\Model\SessionModel @inject */
    public $sessions;

    /** @var \App\Model\RecordModel @inject */
    public $records;

    /** @var \App\Model\testModel @inject */
    public $tests;

    /** @var \App\Components\Coach\IEntityControlFactory @inject */
    public $entityControlFactory;

    /** @var \App\Components\Coach\IGridControlFactory @inject */
    public $gridControlFactory;

    /** @var \App\Components\Coach\IAthleteGridControlFactory @inject */
    public $athleteGridControlFactory;

    ///// Actions /////

    /**
     * @secured
     * @resource('coach')
     * @privilege('default')
     */
    public function actionDefault()
    {

    }

    /**
     * @secured
     * @resource('coach')
     * @privilege('detail')
     */
    public function actionDetail($id)
    {
        $coach = $this->loadItem($this->coaches, $id);
        $this->template->coach = $coach;

        if ($this->user->isInRole('coach') && !$this->user->isInRole('admin')) {
            $kouč = $this->coaches->findOneBy(['user.id' => $this->user->id]);

            if ($coach->id != $kouč->id) {
                throw new Nette\Application\ForbiddenRequestException;
            }
        }

        $this['athleteGrid']->setParams($id);
    }

    /**
     * @secured
     * @resource('coach')
     * @privilege('edit')
     */
    public function actionEdit($id)
    {
        $athlete = $this->loadItem($this->coaches, $id);
        $this->template->athlete = $athlete;
        $this['entity']->entity = $athlete;
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

    /** @return \App\Components\Coach\EntityControl */
    protected function createComponentEntity()
    {
        return $this->entityControlFactory->create();
    }

    /** @return \App\Components\Coach\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }

    /** @return \App\Components\Coach\AthleteGridControl */
    protected function createComponentAthleteGrid()
    {
        return $this->athleteGridControlFactory->create();
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
