<?php

namespace App\Presenters;

/**
 * Description of UserPresenter
 * @author Martin
 */
class UserPresenter extends BasePresenter
{
    
    /** @var \App\Model\UserModel @inject */
    public $users;
    
    /** @var \App\Model\AthleteModel @inject */
    public $athletes;
    
    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $entityManager;
    
    /** @var \App\Authenticator @inject */
    public $userManager;
    
    /** @var \App\Components\User\IGridControlFactory @inject */
    public $gridControlFactory;
    
    /** @var \App\Components\User\IEntityControlFactory @inject */
    public $entityControlFactory;

    /** @var \App\Components\User\ICoachControlFactory @inject */
    public $coachControlFactory;
    
    
    ///// Actions /////
    
    /**
     * @secured
     * @resource('user')
     * @privilege('default')
     */
    public function actionDefault($id)
    {
        
    }

    /**
     * @secured
     * @resource('user')
     * @privilege('detail')
     */
    public function actionDetail($id)
    {
        $user = $this->loadItem($this->users, $id);
        $this->template->entity = $user;
//        $this['entity']->entity = $user;
        if ($user->coach) {
            $this['coach']->entity = $user;
        }
        $this->template->athletes = $this->athletes->toMultiSelect();
//        $this['sessionGrid']->setParams($id);
    }
    
    public function actionEdit($id)
    {
        $user = $this->loadItem($this->users, $id);
        $this->template->entity = $user;
        $this['entity']->entity = $user;
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
    
    
    
    ///// Components /////

    /** @return \App\Components\User\EntityControl */
    protected function createComponentEntity()
    {
        return $this->entityControlFactory->create();
    }    
    
    /** @return \App\Components\User\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }
    
    /** @return \App\Components\User\CoachControl */
    protected function createComponentCoach()
    {
        return $this->coachControlFactory->create();
    }
}
