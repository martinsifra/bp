<?php

namespace App\Components\Profile;

use App\Components\BaseControl;
use App\Forms\Rendering\Bs3FormRenderer;
use App\Model\Entity\User;
use App\Model\Logic\ProfileLogic;
use Nette\Application\UI\Form;
use Nette\Security\Identity;
use Nette\Utils\ArrayHash;

class RecoveryControl extends BaseControl
{

	/** @var ProfileLogic @inject */
	public $profileLogic;

	/** @var User */
	private $user;

	/** @return Form */
	public function createComponentForm()
	{
		$form = new Form();
		$form->setRenderer(new Bs3FormRenderer());
		$form->setTranslator($this->translator);

		$form->addPassword('newPassword', 'profile.form.label.newPassword', NULL, 255)
				->setRequired('profile.form.required.newPassword')
				->addRule(Form::MIN_LENGTH, 'profile.form.rule.passwordMinLength', 8);

		$form->addPassword('passwordAgain', 'profile.form.label.passwordAgain', NULL, 255)
				->setRequired('profile.form.required.passwordAgain')
				->addRule(Form::EQUAL, 'profile.form.rule.passwordEqual', $form['newPassword']);

		$form->addSubmit('recovery', 'profile.form.submit.setNewPassword');

		$form->onSuccess[] = $this->formSucceeded;

		return $form;
	}

	/**
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function formSucceeded(Form $form, ArrayHash $values)
	{
		$user = $this->profileLogic->recoveryPassword($this->user, $values->newPassword);

		$this->presenter->user->login(new Identity($user->id, $user->getRolesPairs(), $user->toArray()));
		$this->presenter->flashMessage('profile.flash.newPasswordSet', 'success');
		$this->presenter->redirect('Sign:in');
	}

	/**
	 * @param type $token
	 * @return void
	 */
	public function setToken($token)
	{
		$this->user = $this->profileLogic->findByRecoveryToken($token);

		if ($this->user === NULL) {
			$this->presenter->flashMessage('profile.flash.expiredToken', 'info');
			$this->presenter->redirect('Sign:forgotten');
		}
	}

}

interface IRecoveryControlFactory
{

	/** @return RecoveryControl */
	function create();
}
