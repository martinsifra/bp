<?php

namespace App\Components\User;

/**
 * 
 */
class CoachControl extends \App\Components\Base\EntityControl
{    
    
    /** @var \App\Model\AthleteModel */
    private $athletes;


    public function __construct(\App\Model\UserModel $model, \App\Model\AthleteModel $athletes) {
        $this->model = $model;
        $this->athletes = $athletes;
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{   
		$form = new \Nette\Application\UI\Form();
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
        
//        $form->addText('athletes', 'Závodníci')
//                ->setAttribute('data-role', 'tagsinput');
        
        $form->addMultiSelect('options', 'Možnosti:', $this->athletes->toMultiSelector())
                ->getControlPrototype()->style('height', '500px');

		$form->addSubmit('save', 'Uložit');
        $form->addButton('back', 'Zrušit')
                ->setAttribute('class', 'cancel');
		
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

            
            $message = 'New record was successfuly saved!';
        } else {
            $message = 'Changes was successfuly saved!';
        }

        // 3) Map data from form to entity
          $this->toEntity($values);
        
        // 4) Persist and flush entity -> redirect to dafeult
        $this->save([], 'User:');

        $this->presenter->redirect('detail', $this->entity->id);
	}

    
    protected function toArray()
    {
        $defaults = [];      
        foreach ($this->entity->coach->athletes as $athlete) {
            $defaults[] = $athlete->id;
        }

        return [
            'options' => $defaults
        ];
    }
    
    
    protected function toEntity($values)
    {
        $new = $this->athletes->findBy(['id' => $values->options]);
        $old = $this->entity->coach->athletes;
        
        $old_id = [];
        foreach ($old as $athlete) {
            $old_id[] = $athlete->id;
        }
        
        foreach ($new as $athlete) {
            if (!in_array($athlete->id, $old_id)) {
                $this->entity->coach->addAthlete($athlete);
            } else {
                $old_id = array_diff($old_id, [$athlete->id]);
            }
        }
        
        foreach ($old as $athlete) {
            if (in_array($athlete->id, $old_id)) {
                $this->entity->coach->removeAthlete($athlete);
            }
        }
    }
}