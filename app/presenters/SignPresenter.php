<?php

namespace App\Presenters;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{
    const
        REDIRECT_DEST = 'Dashboard:'; // Redirect destination for logged in users

    
    /** @persistent */
    public $backlink = '';
    
    /** @var \App\Components\ISignInControlFactory @inject */
    public $signInControlFactory;
    
    /** @var \App\Components\IForgottenControlFactory @inject */
    public $forgottenControlFactory;
    
    /** @var \App\Components\IRecoveryControlFactory @inject */
    public $recoveryControlFactory;
    
    protected function startup() {
        parent::startup();
        
        // LoggedIn user redirect away
        if ($this->user->isLoggedIn()) {
            $this->redirect(self::REDIRECT_DEST);
        }
    }
    
    
    public function actionIn()
    {

    }
    
    public function actionForgotten()
    {
        
    }
    
    
    public function actionRecovery()
    {
        
    }
    
    
    public function actionRegister()
    {
        
    }




    ///// Components /////
    
    /*
     * SignIn control.
     * @return \App\Components\GridBookControl
     */
    protected function createComponentSignIn()
    {
        return $this->signInControlFactory->create();
    }

    /**
     * Forgotten password form control
     * @return \App\Components\ForgottenControl
     */
    protected function createComponentForgotten()
    {
        return $this->forgottenControlFactory->create();
    }

    /**
     * Recovery password form control
     * @return \App\Components\RecoveryControl
     */
    protected function createComponentRecovery()
    {
        return $this->recoveryControlFactory->create();
    }    
}
