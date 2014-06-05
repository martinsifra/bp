<?php

namespace App\Components\Session;


/*
 *
 */
class DetailGridControl extends \App\Components\Base\GridControl
{

    /** @var \Kdyby\Doctrine\EntityManager */
    public $em;

    /** @var \Kdyby\Doctrine\Entities\IdentifiedEntity */
    private $session;

    /** @var \App\Model\TestModel */
    private $tests;

    /** @var array */
    private $results;

    private $allTests;

    private $allRecords;

    public function __construct(\Kdyby\Doctrine\EntityManager $em, \App\Model\TestModel $tests) {
        $this->em = $em;
        $this->tests = $tests;
    }

    protected function createComponentGrido()
    {
        $grid = new \Grido\Grid();

        foreach ($this->results as $athleteId => $athleteData) {
            foreach ($this->allTests as $test) {
                    $testIndex = 'test_' . $test->id;

                    if (array_key_exists($testIndex, $athleteData)) {
                        if ($test->source) {
//                            $record = $this->results[$athleteId]['record'][$test->id];

                            /// tady probíhá výpočet hodnot
                            if (array_key_exists($test->slug, $this->results)) {
                                $this->results[$athleteId][$testIndex] = $this->results[$test->slug];  // Uložení do pole pro Grido
                            }
                        }
                    } else {
                        $this->results[$athleteId][$testIndex] = ''; // Uložení do pole pro Grido
                    }
            }
        }

        ///// Grido settings /////
        $grid->model = new \Grido\DataSources\ArraySource($this->results);
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);
        $grid->setDefaultSort([
            'surname' => 'ASC'
        ]);


        //// Columns ////
//        $grid->addColumnText('id', 'ID');

        $grid->addColumnText('surname', 'Příjmení')
            ->setCustomRender(function($item) {
                return '<a href="' . $this->presenter->link('Athlete:detail', $item['id'] ) . '">'.$item['surname'].'</a>';
            })
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();

        $grid->getColumn('surname')->getCellPrototype()->setName('th');


        $grid->addColumnText('firstname', 'Jméno')
            ->setCustomRender(function($item) {
                return $item['firstname'];
            })
            ->getCellPrototype()
                ->setName('th');



        ///// Test columns /////
        foreach ($this->allTests as $test) {
            \Nette\Diagnostics\Debugger::barDump($test->id);
            $grid->addColumnNumber('test_' . $test->id, $test->name . ' ' . ($test->unit ? '<small>[' . $test->unit . ']</small>' : ''), $test->decimals)
                    ->setCustomRender(__DIR__ . "\dropdown.latte", ['test' => $test, 'session' => $this->session])
                    ->setSortable();

            $grid->getColumn('test_' . $test->id)->cellPrototype->class[] = 'center';
            $grid->getColumn('test_' . $test->id)->headerPrototype->class[] = 'center';
        }

        return $grid;
    }

    public function setSession(\App\Entities\IdentifiedEntity $session)
    {
        $this->session = $session;

        $repository = $this->em->getRepository('\App\Entities\Record');
        $this->allRecords = $repository->createQueryBuilder('r')
                ->addSelect('a')
                ->addSelect('t')// This will produce less SQL queries with prefetch.
                ->join('r.athlete', 'a')
                ->join('r.test', 't')
                ->where('r.session = :session_id')
                ->setParameter(':session_id', $this->session->id)
                ->getQuery()
                ->execute();

        $this->allTests = $this->tests->findAll();


        $this->results = [];

        foreach ($this->allRecords as $record) {
            $this->results[$record->athlete->id]['id'] = $record->athlete->id;
            $this->results[$record->athlete->id]['firstname'] = $record->athlete->firstname;
            $this->results[$record->athlete->id]['surname'] = $record->athlete->surname;
            $this->results[$record->athlete->id]['record'][$record->test->id] = $record;
        }
        
        foreach ($this->results as $key => $user) {
            try {
                $evaluated = $this->tests->evaluate($this->allTests, $user['record']);
                \Nette\Diagnostics\Debugger::barDump($evaluated);

                foreach ($evaluated as $value) {                   
//                    \Nette\Diagnostics\Debugger::barDump('test_' . $value['id']);
                    $this->results[$key]['test_' . $value['id']] = $value['value'];
                    
//                    $this->results[$key]['record'][$value['id']] = new \App\Entities\Record;
                }
                
//                $this->results[$record->athlete->id]['test_' . $record->test->id] = $evaluated[$record->test->slug];
            } catch (\M\ParserException $e) {
                \Nette\Diagnostics\Debugger::barDump($e->getMessage());
                $this->presenter->flashMessage('Výpočet hodnot záznamů obsahuje chyby. Čekejte na opravu administrátorem.', 'warning');
                $this->getPresenter()->forward('Session:detail', $this->session->id);
            }
        }
    }
}
