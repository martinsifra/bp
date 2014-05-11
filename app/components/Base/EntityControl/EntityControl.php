<?php

namespace App\Components\Base;

use Nette\Application\UI\Control;

/**
 * Entity Control
 * @author Martin Šifra <me@martinsifra.cz>
 */
abstract class EntityControl extends Control 
{
    const ON_SUCCESS = 'Všechna data byla úspěšně uložena.',
          REDIRECT = 'Dashboard:';


    /** @var \Kdyby\Doctrine\Entities\IdentifiedEntity */
    protected $entity = NULL;
    
    /** @var \App\Model\BaseModel */
    protected $model;
        
    /** @var \Nette\Application\UI\Form */
    protected $form;

    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/EntityControl.latte');
        $template->render();
    } 
    
    
    abstract protected function createComponentForm();
//    {
//		$this->form = new \Nette\Application\UI\Form();
//    }   
    
    
    
    public function setEntity(/*\App\Entities\IdentifiedEntity*/ $entity)
    {
        $this->entity = $entity;
        $this['form']->setDefaults($this->toArray());
    }
    
    public function save($redirect_args, $redirect = 'Dashboard:')
    {
        try {
            $this->model->save($this->entity);
            $this->presenter->flashMessage(self::ON_SUCCESS, 'success');
            $this->presenter->restoreRequest($this->presenter->backlink);
            $this->presenter->redirect($redirect, $redirect_args);
        } catch (\Kdyby\Doctrine\DuplicateEntryException $e) {
            $this['form']->addError('Tento záznam již v databázi existuje.', 'warning');
        }
    }
   
    /**
     * 
     */
//    abstract protected function toArray();
 
    
    /** @param array */
//    abstract protected function toEntity($array);
}
