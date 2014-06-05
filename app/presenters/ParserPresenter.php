<?php

namespace App\Presenters;

use Nette\Application\UI;

class ParserPresenter extends BasePresenter
{

    protected function createComponentParserForm()
    {
        $form = new UI\Form;
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer);
        $form->addTextarea('formula', 'M language:', NULL, 20);
        $form->addSubmit('submit', 'Parse!');
        $form->onSuccess[] = array($this, 'parserFormSubmitted');
        return $form;
    }

    public function parserFormSubmitted(UI\Form $form)
    {
        $values = $form->getValues();

        \Nette\Diagnostics\Debugger::$maxLen = 1000;

        $parser = new \M\Parser();
        $php = $parser->parse($values->formula)->getPHP();

        dump($php);
        
//        $this->flashMessage('Byl jsi úspěšně přihlášen.');
//        $this->redirect('Homepage:');
    }
}
