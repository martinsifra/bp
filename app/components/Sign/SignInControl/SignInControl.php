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
    
    /** @var \App\Model\UserModel $users */
    private $users;


    public function __construct(\App\Model\UserModel $users)
    {
        parent::__construct();
        $this->users = $users;
    }

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

            $user = $this->users->find($this->presenter->user->id);
            
            if ($this->presenter->user->isInRole('admin')) {
                $this->presenter->redirect('Dashboard:');
            } elseif ($this->presenter->user->isInRole('coach')) {
                $this->presenter->redirect('Coach:detail', [$user->coach->id]);
            } elseif ($this->presenter->user->isInRole('athlete')) {
                $this->presenter->redirect('Athlete:detail', [$user->athlete->id]);
            }
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('Incorrect login or password!');
		}
	}
}