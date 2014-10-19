<?php

namespace App\Presenters;

use App\Components\Profile;

class SignPresenter extends BasePresenter
{

	/** @persistent */
	public $backlink = '';

	/** @var Profile\ISignInControlFactory @inject */
	public $iSignInControlFactory;

	/** @var Profile\IForgottenControlFactory @inject */
	public $iForgottenControlFactory;

	/** @var Profile\IRecoveryControlFactory @inject */
	public $iRecoveryControlFactory;

	protected function startup()
	{
		parent::startup();

		// LoggedIn user redirect away
		if ($this->user->isLoggedIn()) {
			$this->redirect('Dashboard:');
		}
	}
	
	public function actionForgotten()
	{
		
	}

	public function actionIn()
	{
		
	}

	public function actionRecovery($token)
	{
		$this['recovery']->setToken($token);
	}

	///// Components /////

	/** @return Profile\SignInControl */
	protected function createComponentSignIn()
	{
		return $this->iSignInControlFactory->create();
	}

	/** @return Profile\ForgottenControl */
	protected function createComponentForgotten()
	{
		return $this->iForgottenControlFactory->create();
	}

	/** @return Profile\RecoveryControl */
	protected function createComponentRecovery()
	{
		return $this->iRecoveryControlFactory->create();
	}

}
