<?php

namespace App\Components\Base;

use     Nette\Application\UI\Control;

/**
 * Record control
 * @author Martin Šifra <me@martinsifra.cz>
 */
class RecordControl extends Control 
{
    /** @var \Kdyby\Doctrine\Entities\IdentifiedEntity */
    protected $entity = NULL;
    
    /** @var \App\Model\BaseModel */
    protected $model;


       public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/BaseRecordControl.latte');
        $template->render();
    }
}
