<?php

namespace App\Components\Session;

use Nette\Application\UI\Control;

/**
 * Import
 * @author Martin Šifra <me@martinsifra.cz>
 */
class ImportControl extends Control 
{    
    /** @var \App\Model\TestModel $tests */
    private $tests;

    /** @var \App\Entities\Session $session */
    private $session;

    /** @var \App\Model\AthleteModel $athletes */
    private $athletes;

    /** @var \App\Model\RecordModel $records */
    private $records;
    
    public function __construct(\App\Model\TestModel $tests, \App\Model\AthleteModel $athletes, \App\Model\RecordModel $records) {
        $this->tests = $tests;
        $this->athletes = $athletes;
        $this->records = $records;
    }


    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/ImportControl.latte');
        $template->render();
    } 

    
	/**
	 * Add form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{   
		$form = new \Nette\Application\UI\Form();
        $form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
        
		$form->addUpload('file', 'CSV soubor:')
			->setRequired('Zadejte vyberte CSV soubor s daty.');
        
		$form->addSubmit('save', 'Spustit');
        
		// Call on success
		$form->onSuccess[] = $this->formSucceeded;
        
        return $form;
	}

	public function formSucceeded(\Nette\Application\UI\Form $form)
	{
        // 1) Load data from form
		$values = $form->getValues();
        
        $tests = $this->tests->findAll();

        \Nette\Diagnostics\Debugger::dump($this->athletes->findAll());
        
        $reader = new \EasyCSV\Reader($values->file->getTemporaryFile());
        
        while ($row = $reader->getRow()) {
            // Existuje vůbec daný závodník? (jmeno, prijmeni,datum-narozeni)
            $athlete = $this->athletes->findOneBy([
                    'firstname' => $row['jmeno'],
                    'surname' => $row['prijmeni'],
                    'birthdate' => new \DateTime($row['datum-narozeni']),
                ]);

            if (!$athlete) {
                $athlete = new \App\Entities\Athlete;
                $athlete->firstname = $row['jmeno'];
                $athlete->surname = $row['prijmeni'];
                $athlete->birthdate = new \DateTime($row['datum-narozeni']);
                $athlete->sex = $row['pohlavi'];
                
                \Nette\Diagnostics\Debugger::dump($this->athletes->save($athlete));
                
            }
            
            
            // Vytažení data měření
            
            foreach ($tests as $test) {
                $value = \Nette\Utils\Arrays::get($row, $test->slug, FALSE);
                
                if ($value) {
                    $record = new \App\Entities\Record;
                    $record->athlete = $athlete;
                    $record->session = $this->session;
                    $record->test = $test;
                    $record->value = $row[$test->slug];
                    $record->created = new \DateTime($row['datum-mereni']);
                    
                    try {
                        $this->records->save($record);
                    } catch(\Exception $e) {
                        \Nette\Diagnostics\Debugger::dump($e);
                    }
                }

            }
            
        }
        
//        \Nette\Diagnostics\Debugger::dump($records);

       

//        
//        // 2) Recognize add or edit of record
//        if (!$this->entity) {
//            $this->entity = new \App\Entities\Session();
//            
//            $message = 'New record was successfuly saved!';
//        } else {
//            $message = 'Changes was successfuly saved!';
//        }
//
//
//        // 3) Map data from form to entity
//        $this->toEntity($values);
//        
//        // 4) Persist and flush entity -> redirect to dafeult
//        $this->model->save($this->entity);
//        $this->presenter->flashMessage($message, 'success');
//        $this->presenter->redirect('default');
	}
   
    public function setSession(\App\Entities\Session $session)
    {
        $this->session = $session;
    }
}