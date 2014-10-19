<?php

namespace App\Components\Profile;

use App\Components\BaseControl;
use App\Forms\Rendering\Bs3FormRenderer;
use App\Mail\Messages\ForgottenMessage;
use App\Model\Logic\ProfileLogic;
use App\Model\Logic\UserLogic;
use Nette\Application\UI\Form;
use Nette\Mail\IMailer;
use Nette\Utils\ArrayHash;

/**
 * @author Martin Šifra <me@martinsifra.cz>
 */
class ForgottenControl extends BaseControl
{

	/** @var UserLogic @inject */
	public $userLogic;

	/** @var ProfileLogic @inject */
	public $profileLogic;

	/** @var IMailer @inject */
	public $mailer;

	/**
	 * @return Form
	 */
	protected function createComponentForm()
	{
		$form = new Form();
		$form->setRenderer(new Bs3FormRenderer());
		$form->setTranslator($this->translator);

		$form->addText('email', 'base.form.label.email', NULL, 255)
				->setRequired('base.form.required.email')
				->addRule(Form::EMAIL, 'base.form.rule.email');

		$form->addSubmit('send', 'profile.form.submit.forgotten');

		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}

	public function formSucceeded(Form $form, ArrayHash $values)
	{
		$user = $this->userLogic->findByEmail($values->email);

		if ($user === NULL) {
			$this->presenter->flashMessage('profile.flash.emailNotFound', 'warning');
			$this->presenter->redirect('this');
		} else {
			$user = $this->profileLogic->setRecovery($user);

			// Send e-mail with recovery link
			$message = new ForgottenMessage();
			$message->addTo($user->email);
			$template = $this->createTemplate()->setFile($message->path);
			$template->token = $user->recoveryToken;
			$message->setHtmlBody($template);
			$this->mailer->send($message);

			$this->presenter->flashMessage('profile.flash.recoveryTokenSent', 'success');
			$this->presenter->redirect('Sign:in');
		}
	}

}

interface IForgottenControlFactory
{

	/** @return ForgottenControl */
	function create();
}
