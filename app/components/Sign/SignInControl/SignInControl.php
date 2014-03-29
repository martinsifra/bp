<?php

Namespace App\Components;

use Nette\Security as NS,
    Nette\Application\UI\Control,
    Nette\Application\UI\Form,
    Nette, Model;


/**
 * Přihlašovací formulář.
 * @author Martin Šifra
 */
class SignInControl extends Control {
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/SignInControl.latte');
        $template->render();
    }

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new \Nette\Application\UI\Form();
		$form->addText('username', 'E-mail:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Neodhlašovat');

		$form->addSubmit('send', 'Přihlásit se');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}

	public function signInFormSucceeded($form)
	{
		$values = $form->getValues();

		if ($values->remember) {
			$this->presenter->getUser()->setExpiration('30 days', FALSE);
		} else {
			$this->presenter->getUser()->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->presenter->getUser()->login($values->username, $values->password);
            $this->presenter->restoreRequest($this->presenter->backlink);
			$this->presenter->redirect('Homepage:');

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('Incorrect login or password!');
		}
	}
}