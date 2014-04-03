<?php

namespace App\Components\Base;

use     Nette\Application\UI\Control;

/**
 * Form control
 * @author Martin Šifra <me@martinsifra.cz>
 */
class FormControl extends Control 
{
    /** @var \Kdyby\Doctrine\Entities\IdentifiedEntity */
    protected $entity = NULL;
    
    /** @var \App\Model\BaseModel */
    protected $model;

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/FormControl.latte');
        $template->render();
    }    
}
