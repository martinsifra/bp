<?php

namespace App\Presenters;

/**
 * Description of UserPresenter
 * @author Martin
 */
class UserPresenter extends BasePresenter
{

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $entityManager;
    
    /** @var \App\Authenticator @inject */
    public $userManager;
    
    /** @var \App\Components\User\IGridControlFactory @inject */
    public $gridControlFactory;
    
    ///// Actions /////
    
    
    /**
     * @secured
     * @resource('user')
     * @privilege('default')
     */
    public function renderDefault()
    {
//        $this->userManager->authenticate(['me@martinsifra.cz', 'heslo']);
//        $role = new \App\Entities\Role();
//        $role->name = 'athlete';
//        $role->label = 'Závodník';
        
//        $user = new \App\Entities\User();
//        $user->username = 'me@martinsifra.cz';
//        $user->password = 'heslo';
//        //$user->firstname = 'Maritn';
//        //$user->surname = 'Šifra';
//        $user->addRole($role);

//        $user = $this->userManager->findById('1');
//        $user->addRole($role);        
        
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
        
    }
    
    public function actionEdit($id)
    {
        
    }
    
    
    ///// Components /////

    /** @return \App\Components\Athlete\RecordControl */
//    protected function createComponentRecord()
//    {
//        return $this->recordControlFactory->create();
//    }    
    
    /** @return \App\Components\Athlete\GridControl */
    protected function createComponentGrid()
    {
        return $this->gridControlFactory->create();
    }
}
