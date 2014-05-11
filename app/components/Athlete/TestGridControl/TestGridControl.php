<?php

namespace App\Components\Athlete;

/**
 * 
 */
class TestGridControl extends \App\Components\Base\GridControl
{
    
    /** @var int $athlete_id */
    private $athlete_id;
    
    /** @var int $test_id */
    private $test_id;


    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid();
        
        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Record');
        $qb = $repository->createQueryBuilder('r')
                ->addSelect('t') // This will produce less SQL queries with prefetch.
                ->innerJoin('r.test', 't')
                ->where('r.athlete = :athlete AND r.test = :test')
                ->setParameters([
                    'athlete' => $this->athlete_id,
                    'test' => $this->test_id
                ]);
        $grid->model = new \Grido\DataSources\Doctrine($qb, [
            'test_name' => 't.name'
        ]);

        
        ///// Grid settings /////
        $grid->setDefaultSort(array('created' => 'ASC'));
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);
        
        //// Columns ////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnDate('created', 'Datum měření', 'j.n.Y')
                ->setSortable();
        
        $grid->addColumnText('value', 'Hodnota')
                ->setCustomRender(function($record){
                    if ($record->test->eval) {
                        return eval($record->test->eval) . '&nbsp;' . $record->test->unit;
                    } else {
                        return $record->value . '&nbsp;' . $record->test->unit;
                    }
                });
        
        
        ///// Actions /////
//        $grid->addActionHref('test', '')
//            ->setCustomHref(function($record) {
//                return $this->presenter->link('Athlete:test', [$this->athlete_id, $record->test->id]);
//            })
//            ->setIcon('stats');
//                
//        $grid->addActionHref('detail', 'Upravit', 'Record:detail')
//            ->setIcon('edit')
//            ->setDisable(function() {
//                return !$this->presenter->user->isAllowed('test', 'detail');
//            });
//            
//        $grid->addActionHref('remove', 'Odstranit', 'remove!')
//            ->setIcon('trash')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('athlete', 'remove');
//            });

        return $grid;
    }
    
    public function setParams($athlete_id, $test_id)
    {
        $this->athlete_id = $athlete_id;
        $this->test_id = $test_id;
    }
    
}
