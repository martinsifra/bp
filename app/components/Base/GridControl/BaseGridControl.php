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

    /** @var \App\Model\SettingsModel */
    protected $settings;
    
    
    public function __construct(\Kdyby\Doctrine\EntityManager $em, \App\Model\SettingsModel $settings)
    {
        $this->em = $em;
        $this->settings = $settings;
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/BaseGridControl.latte');
        $template->render();
    }
}
