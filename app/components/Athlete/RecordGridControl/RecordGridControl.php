<?php

namespace App\Components\Athlete;

/**
 * 
 */
class RecordGridControl extends \App\Components\Base\GridControl
{
    
    /** @var int $athlete_id */
    private $athlete_id;
    
    /** @var int $session_id */
    private $session_id;

    /** @var \App\Model\TestModel */
    private $tests;
    
    private $results;

    
    public function __construct(\Kdyby\Doctrine\EntityManager $em, \App\Model\TestModel $tests) {
        $this->em = $em; 
        $this->tests = $tests;
    }

    protected function createComponentGrido()
    {

        //// Grido ////
        $grid = new \Grido\Grid();
        
        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Record');
        $qb = $repository->createQueryBuilder('r')
                ->addSelect('t') // This will produce less SQL queries with prefetch.
                ->innerJoin('r.test', 't')
                ->where('r.athlete = :athlete AND r.session = :session')
                ->setParameters([
                    'athlete' => $this->athlete_id,
                    'session' => $this->session_id
                ]);
        $grid->model = new \Grido\DataSources\Doctrine($qb, [
            'test_name' => 't.name'
        ]);

        
        ///// Grid settings /////
        $grid->setDefaultSort(array('test_name' => 'ASC'));
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);
        
        //// Columns ////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnText('test_name', 'Test')
                ->setCustomRender(function($record){
                    return $record->test->name;
                })
                ->setSortable();
        
        $grid->addColumnText('value', 'Value')
                ->setCustomRender(function($record){
                    if ($record->test->source) {
                        return $this->results[$record->test->slug]['value'] . '&nbsp;' . $record->test->unit;
                    } else {
                        return $record->value . '&nbsp;' . $record->test->unit;
                    }
                });
        
        
        ///// Actions /////
        $grid->addActionHref('test', '')
            ->setCustomHref(function($record) {
                return $this->presenter->link('Athlete:test', [$this->athlete_id, $record->test->id]);
            })
            ->setIcon('bar-chart-o')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('athlete', 'test');
            });
                
        $grid->addActionHref('detail', 'Upravit', 'Record:detail')
            ->setIcon('edit')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('record', 'detail');
            });
            
        $grid->addActionHref('remove', 'Odstranit', 'remove!')
            ->setIcon('trash-o')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('record', 'remove');
            });

        return $grid;
    }
    
    public function setParams($athlete_id, $session_id)
    {
        $this->athlete_id = $athlete_id;
        $this->session_id = $session_id;
        
        
        
        // Loading all tests
        $testsDao = $this->em->getDao(\App\Entities\Test::getClassName());
        $tests = $testsDao->findAll();
        
        $recordsDao = $this->em->getDao(\App\Entities\Record::getClassName());
        $records = $recordsDao->findBy([
            'athlete' => $this->athlete_id,
            'session' => $this->session_id
        ]);


        
        try {
            $this->results = $this->tests->evaluate($tests, $records);
        } catch (\M\ParserException $e) {
            \Nette\Diagnostics\Debugger::barDump($e->getMessage());
            $this->presenter->flashMessage('Výpočet hodnot záznamů obsahuje chyby. Čekejte na opravu administrátorem.', 'warning');
            $this->getPresenter()->forward('Athlete:detail', $this->athlete_id);
        }
    }
}
