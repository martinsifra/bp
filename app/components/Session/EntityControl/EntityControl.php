<?php

namespace App\Components\Session;

/**
 * Athlete record control
 * @property \App\Entities\Session $entity
 */
class EntityControl extends \App\Components\Base\EntityControl
{    
    
    public function __construct(\App\Model\SessionModel $model) {
        $this->model = $model;
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{   
		$form = new \Nette\Application\UI\Form();
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
        
		$form->addText('name', 'Název:')
			->setRequired('Zadejte prosím název měření.');
        
		$form->addTextArea('desc', 'Popis:');
        
        $form->addText('date', 'Datum:')
			->setRequired('Zadejte prosím datum konání měření.');
        
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
            $this->entity = new \App\Entities\Session();
            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }


        // 3) Map data from form to entity
        $this->toEntity($values);
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('default');
	}

    
    protected function toArray()
    {
        return [
            'name' => $this->entity->name,
            'desc' => $this->entity->description,
            'date' => $this->entity->date->format('j.n.Y')
        ];
    }
    
    
    protected function toEntity($values)
    {
        $this->entity->name = $values->name;
        $this->entity->description = $values->desc;
        $this->entity->date = \Nette\DateTime::from($values->date);
    }
}