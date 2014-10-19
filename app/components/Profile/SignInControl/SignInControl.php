<?php

namespace App\Components\Profile;

use App\Components\BaseControl;
use App\Forms\Rendering\Bs3FormRenderer;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;

/**
 * @author Martin Šifra <me@martinsifra.cz>
 */
class SignInControl extends BaseControl
{

	/** @return Form */
	protected function createComponentForm()
	{
		$form = new Form();
		$form->setRenderer(new Bs3FormRenderer());
		$form->setTranslator($this->translator);

		$form->addText('email', 'base.form.label.email')
				->setRequired('base.form.required.email')
				->addRule(Form::EMAIL, 'base.form.rule.email');

		$form->addPassword('password', 'base.form.label.password')
				->setRequired('base.form.required.password');

		$form->addCheckbox('remember', 'profile.form.label.rememberLogin');

		$form->addSubmit('signIn', 'profile.form.submit.signIn');

		$form->onSuccess[] = $this->formSucceeded;
		return $form;
	}

	/**
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function formSucceeded(Form $form, ArrayHash $values)
	{
		if ($values->remember) {
			$this->presenter->user->setExpiration('30 days', FALSE);
		} else {
			$this->presenter->user->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->presenter->user->login($values->email, $values->password);
			$this->presenter->restoreRequest($this->presenter->backlink);
			$this->presenter->redirect('Dashboard:');
		} catch (AuthenticationException $e) {
			$form->addError($this->translator->translate('profile.form.error.incorrectCredentials'));
		}
	}

}

interface ISignInControlFactory
{

	/** @return SignInControl */
	function create();
}
