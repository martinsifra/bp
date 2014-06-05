<?php

namespace App\Components\Test;

use Nette\Caching\Cache;


/**
 * @property \App\Entities\Test $entity
 */
class EntityControl extends \App\Components\Base\EntityControl
{

    /** @var \Nette\Caching\IStorage $cacheStorage */
    private $cacheStorage;

    public function __construct(\App\Model\TestModel $model, \Nette\Caching\IStorage $cacheStorage) {
        parent::__construct();
        $this->model = $model;
        $this->cacheStorage = $cacheStorage;
    }

	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new \Nette\Application\UI\Form();
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());

        $form->addText('slug', 'Slug');

		$form->addText('name', 'Name:')
			->setRequired('Please enter test\'s name.');

		$form->addTextArea('desc', 'Description:');

//		$form->addTextArea('eval', 'Eval:');

		$form->addTextArea('source', 'M lang:');

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
        $this->toEntity($values);

        // 3.5 Source code parsing and caching
        if ($this->entity->source) {
            $cache = new Cache($this->cacheStorage);
            $parser = new \M\Parser();
            
            try {
                $php = $parser->parse($this->entity->source)->getPHP();
                $toCache = [
                    'source' => $php,
                    'parameters' => $parser->getParameters()
                ];
                $cache->save('test\\' . $this->entity->id, $toCache);
            } catch (\M\ParserException $e) {
                $form->addError($e->getMessage());
            }
        }

        // 4) Persist and flush entity -> redirect to dafeult
        $this->model->save($this->entity);
        $this->presenter->flashMessage($message, 'success');
        $this->presenter->redirect('default');
	}

    protected function toEntity($values)
    {
        $this->entity->slug = $values->slug;
        $this->entity->name = $values->name;
        $this->entity->description = $values->desc;
        $this->entity->eval = $values->eval;
        $this->entity->source = $values->source;
        $this->entity->unit = $values->unit;
    }


    protected function toArray()
    {
        return [
            'slug' => $this->entity->slug,
            'name' => $this->entity->name,
            'desc' => $this->entity->description,
            'eval' => $this->entity->eval,
            'source' => $this->entity->source,
            'unit' => $this->entity->unit
        ];
    }
}