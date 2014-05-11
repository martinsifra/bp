<?php

namespace App\Components\Athlete;

/**
 * 
 */
class SessionGridControl extends \App\Components\Base\GridControl
{
    
    /** @var int $athlete_id */
    private $athlete_id;

    
    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid();

        ///// Datasource /////
        $repository = $this->em->getRepository('\App\Entities\Record');
        $qb = $repository->createQueryBuilder()
                ->add('select', 's')
                ->from('App\Entities\Session', 's')
                ->join('s.records', 'r')
                ->where('r.athlete = :athlete_id')
                ->setParameters([
                    'athlete_id' => $this->athlete_id,
                ]);        
        $grid->model = new \Grido\DataSources\Doctrine($qb);

        
        ///// Grid settings /////
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);
//        $grid->setDefaultSort([]);

        
        ///// Columns /////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnDate('date', 'Datum', 'j.n.Y')
                ->setSortable()
                ->cellPrototype->class[] = 'center';
        
        $grid->addColumnText('name', 'Název');

        
        //// Actions ////
        $grid->addActionHref('session', 'Zobrazit záznamy', 'session')
            ->setIcon('caret-square-o-right')
            ->setCustomHref(function($session) {
                return $this->presenter->link('Athlete:session', [$this->athlete_id, $session->id]);
            })
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('athlete', 'remove');
            });
            
        $grid->addActionHref('new_record', 'Nový záznam')
            ->setIcon('plus')
            ->setCustomHref(function($session) {
                return $this->presenter->link('Record:new', ['session_id' => $session->id, 'athlete_id' => $this->athlete_id]);
            })
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('test', 'detail');
            });
            
        return $grid;
    }
    
    public function setParams($athlete_id)
    {
        $this->athlete_id = $athlete_id;
    }
    
}
