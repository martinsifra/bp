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


    protected function createComponentGrido($name)
    {
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
                    if ($record->test->eval) {
                        return eval($record->test->eval) . '&nbsp;' . $record->test->unit;
                    } else {
                        return $record->value . '&nbsp;' . $record->test->unit;
                    }
                });
        
        
        //// Actions ////
        $grid->addActionHref('detail', 'Upravit', 'Record:detail')
            ->setIcon('edit')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('test', 'detail');
            });
            
        $grid->addActionHref('remove', 'Odstranit', 'remove!')
            ->setIcon('trash')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('athlete', 'remove');
            });

        return $grid;
    }
    
    public function setParams($athlete_id, $session_id)
    {
        $this->athlete_id = $athlete_id;
        $this->session_id = $session_id;
    }
    
}
