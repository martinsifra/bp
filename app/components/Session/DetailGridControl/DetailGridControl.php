<?php

namespace App\Components\Session;

use Nette\Application\UI\Control;

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
    
    public function __construct(\Kdyby\Doctrine\EntityManager $em, \App\Model\TestModel $tests) {
        $this->em = $em; 
        $this->tests = $tests;
    }
    
    protected function createComponentGrido()
    {
        $grid = new \Grido\Grid();

        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Record');
        $qb = $repository->createQueryBuilder('r')
                ->addSelect('a')
                ->addSelect('t')// This will produce less SQL queries with prefetch.
                ->join('r.athlete', 'a')
                ->join('r.test', 't')
                ->where('r.session = :session_id')
                ->setParameter(':session_id', $this->session->id)
                ->getQuery()
                ->execute();
        
        $data = [];
        
        foreach ($qb as $record) {
            $data[$record->athlete->id]['id'] = $record->athlete->id;
            $data[$record->athlete->id]['firstname'] = $record->athlete->firstname;
            $data[$record->athlete->id]['surname'] = $record->athlete->surname;
            $data[$record->athlete->id]['test_' . $record->test->id] = $record->value; 
            $data[$record->athlete->id]['record_' . $record->test->id] = $record; 
        }
        
        $tests = $this->tests->findAll();
        
        foreach ($data as $athleteId => $athleteData) {
            foreach ($tests as $test) {
                    $testIndex = 'test_' . $test->id;
                
                    if (array_key_exists($testIndex, $athleteData)) {
                        if ($test->eval) {
                            $record = $data[$athleteId]['record_' . $test->id];
                            $data[$athleteId][$testIndex] = eval($test->eval);
                        } elseif ($test->source) {
                            
                        }
                    } else {
                        $data[$athleteId][$testIndex] = '';
                    }
            }
        }
       
        ///// Grido settings /////
        $grid->model = new \Grido\DataSources\ArraySource($data);
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);
        $grid->setDefaultSort(array('surname' => 'ASC'));

        
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
        foreach ($tests as $test) {
            $grid->addColumnNumber('test_' . $test->id, $test->name . ' ' . ($test->unit ? '<small>[' . $test->unit . ']</small>' : ''), $test->decimals)
                    ->setCustomRender(__DIR__ . "\dropdown.latte", ['test' => $test, 'session' => $this->session])
                    ->setSortable();
                
            $grid->getColumn('test_' . $test->id)->cellPrototype->class[] = 'center';
            $grid->getColumn('test_' . $test->id)->headerPrototype->class[] = 'center';
        }

        
        //// Actions ////
//        $grid->addActionHref('athlete', '')
//            ->setIcon('user')
//            ->setDisable(function() {
//                return !$this->presenter->user->isAllowed('athlete', 'show');
//            });
            
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setIcon('remove')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('test', 'remove');
//            });
        
        return $grid;
    }
    
    public function setSession(\App\Entities\IdentifiedEntity $session)
    {
        $this->session = $session;
    }
}
