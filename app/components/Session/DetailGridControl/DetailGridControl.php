<?php

namespace App\Components\Session;

use Nette\Application\UI\Control;

/*
 * 
 */
class DetailGridControl extends Control
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
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/DetailGridControl.latte');
        $template->render();
    }
    
    protected function createComponentGrido()
    {
        $grid = new \Grido\Grid();
        
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);

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
            $data[$record->athlete->id][$record->test->slug] = $record; 
       }
        
        $grid->model = new \Grido\DataSources\ArraySource($data);

        
//        $grid->setDefaultSort(array('id' => 'DESC'));

        
        //// Columns ////
//        $grid->addColumnText('id', 'ID');

        $grid->addColumnText('surname', 'Příjmení')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        $grid->addColumnText('firstname', 'Jméno');
            
        ///// Test columns /////
        foreach ($this->tests->findAll() as $test) {
            $grid->addColumnText($test->slug, $test->name)
                ->setCustomRender(function($item) use ($test){
                    if (array_key_exists($test->slug, $item)) {
                        return $item[$test->slug]->value . '&nbsp;' . $item[$test->slug]->test->unit;
                    }
                    return '';
                });            
        }

//        
//        $grid->addColumnText('vyska', 'Výška');
//        $grid->addColumnText('vydrzVeShybu', 'Výdrž ve shybu');
//        
//        $grid->addColumnText('surname', 'Surname')
//            ->setSortable()
//            ->setFilterText()
//                ->setSuggestion();
//        

       
        
        //// Actions ////
//        $grid->addActionHref('detail', 'Open')
//            ->setIcon('folder-open')
//            ->setDisable(function() {
//                return !$this->presenter->user->isAllowed('session', 'show');
//            });
            
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setIcon('remove')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('test', 'remove');
//            });
        
        return $grid;
    }
    
    public function setSession(\Kdyby\Doctrine\Entities\IdentifiedEntity $session)
    {
        $this->session = $session;
    }
    
}
