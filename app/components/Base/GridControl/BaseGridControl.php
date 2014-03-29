<?php

namespace App\Components\Base;

use Nette\Application\UI\Control;

/*
 * 
 */
class GridControl extends Control
{
    
    /** @var \Kdyby\Doctrine\EntityManager */
    protected $em;

    public function __construct(\Kdyby\Doctrine\EntityManager $em)
    {
        $this->em = $em; 
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/BaseGridControl.latte');
        $template->render();
    }
}
