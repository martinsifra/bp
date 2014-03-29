<?php

use Nette\Application\UI\Form,
    Nette\Utils\Html,
    Kdyby\BootstrapFormRenderer\BootstrapRenderer;

class BootstrapForm extends Form {

    public function __construct() {
        parent::__construct();

        $renderer = $this->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;
        $renderer->wrappers['pair']['container'] = 'div class=form-group';
        $renderer->wrappers['pair']['.error'] = 'has-error';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';
        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
        $this->setRenderer($renderer);

    }
}