<?php

Namespace App\Components;

use Nette\Security as NS,
    Nette\Application\UI\Control,
    Nette\Application\UI\Form,
    Nette, Model;


/**
 * Forgotten password form
 *
 * @author Martin Šifra
 */
class ForgottenControl extends Control {
    
    /** @var \App\Model\UserModel */
    public $userModel;

    public function __construct(\App\Model\UserModel $userModel) {
        $this->userModel = $userModel;
    }
    
    public function render() {
        $template = $this->template;
        $template->setFile(__DIR__ . '/ForgottenControl.latte');
        $template->render();
    }

	/**
	 * Forgotten form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForgottenForm()
	{
        $form = new Form;
        
        $form->addText('email', 'E-mail:', NULL, 255)
                ->setRequired('Zadejte prosím svůj e-mail!')
                ->addRule(Form::EMAIL, 'E-mail musí mít platný formát!');
        
        $form->addSubmit('send', 'Odeslat žádost');
		
        // Call method forgottenFormSucceeded() on success
        $form->onSuccess[] = $this->forgottenFormSucceeded;

        return $form;
    }

	public function forgottenFormSucceeded($form)
	{
        $values = $form->getValues();
        $employee = FALSE;
        //$employee = $this->employeeRepository->findBy(array('email' => $values->email))
        //        ->fetch();
        
        if ($employee === FALSE) {
            $this->presenter->flashMessage('E-mail není v databázi.', 'warning');
        } else {
            $code = Strings::random(32);
            
            $this->employeeRepository->recovery($employee->id, $code);
            
            $this->presenter->flashMessage('Heslo bylo úspěšně obnoveno.', 'success');
            $this->presenter->redirect('Sign:in');
        }
	}
}