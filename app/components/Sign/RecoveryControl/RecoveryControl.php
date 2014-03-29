<?php

Namespace App\Components;

use Nette\Security as NS,
    Nette\Application\UI\Control,
    Nette\Application\UI\Form,
    Nette, Model;


/**
 * Recovery password form
 *
 * @author Martin Šifra
 */
class RecoveryControl extends Control {
    
    /** @var \App\Model\UserModel */
    public $userModel;

    public function __construct(\App\Model\UserModel $userModel) {
        $this->userModel = $userModel;
    }
    
    public function render() {
        $template = $this->template;
        $template->setFile(__DIR__ . '/RecoveryControl.latte');
        $template->render();
    }

	/**
	 * Forgotten form factory.
	 * @return Nette\Application\UI\Form
	 */
	public function createComponentRecoveryForm()
	{
        $form = new Form();
        
        $form->addPassword('password', 'Nové heslo:', NULL, 128)
                ->setAttribute('class','form-control')
                ->setRequired('Zvolte si nové heslo.')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků', 4);
        $form->addPassword('PasswordVerify', 'Nové heslo znovu:')
                ->setAttribute('class','form-control')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu.')
                ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);
        $form->addSubmit('recovery', 'Obnovit heslo')
                ->setAttribute('class', 'btn btn-primary');
        $form->onSuccess[] = $this->recoveryFormSucceeded;

        return $form;
    }

	public function recoveryFormSucceeded($form)
	{
        $values = $form->getValues();

//        $this->employeeRepository->recoveryPass($this->id, $values->password);
        
        $this->flashMessage('Nyní se můžete přihlásit s novým heslem.', 'success');
        $this->redirect('Sign:in');
	}
}