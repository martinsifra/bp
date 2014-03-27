<?php

Namespace App\Components\Test;

//use Nette\Security as NS,
//    Nette, Model,
//    Nette\Forms\Form,
//	Nette\Forms\Controls;


/**
 * @property \App\Entities\Test $entity
 */
class RecordControl extends \App\Components\Base\RecordControl
{    
    
    public function __construct(\App\Model\TestModel $model) {
        $this->model = $model;
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/RecordControl.latte');
        $template->render();
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{   
		$form = new \BootstrapForm();
        
		$form->addText('label', 'Label:')
			->setRequired('Please enter test\'s name.');
        
		$form->addTextArea('desc', 'Description:');

		$form->addTextArea('eval', 'Eval:');
        
		$form->addText('unit', 'Unit:');
        
		$form->addSubmit('save', 'Save');
        
		// Call on success
		$form->onSuccess[] = $this->formSucceeded;
        
        return $form;
	}

	public function formSucceeded(\Nette\Application\UI\Form $form)
	{
        // 1) Load data from form
		$values = $form->getValues();
        
        // 2) Recognize add or edit of record
        if (!$this->entity) {
            $this->entity = new \App\Entities\Test();

            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }

        // 3) Map data from form to entity
        $this->entity->label = $values->label;
        $this->entity->description = $values->desc;
        $this->entity->eval = $values->eval;
        $this->entity->unit = $values->unit;       
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('default');
	}
    
    public function setEntity(\Kdyby\Doctrine\Entities\BaseEntity $entity)
    {
        $this->entity = $entity;
        $this['form']->setDefaults($this->loadDefaults());
    }
    
    private function loadDefaults()
    {
        return [
            'label' => $this->entity->label,
            'desc' => $this->entity->description,
            'eval' => $this->entity->eval,
            'unit' => $this->entity->unit
        ];
    }
}