<?php

namespace App\Components\Coach;

/**
 * 
 */
class AthleteGridControl extends \App\Components\Base\GridControl
{
    
    /** @var int $coach_id */
    private $coach_id;

    
    protected function createComponentGrido()
    {
        $grid = new \Grido\Grid();

        ///// Datasource /////
        $repository = $this->em->getRepository('\App\Entities\Athlete');
        $qb = $repository->createQueryBuilder('a')
                ->leftJoin('a.coaches', 'c')
                ->leftJoin('a.user', 'u')
                ->where('c.id = :coach_id')
                ->setParameters([
                    'coach_id' => $this->coach_id,
                ]);        
        $grid->model = new \Grido\DataSources\Doctrine($qb,[
            'surname' => 'u.surname',
            'firstname' => 'u.firstname'
        ]);

        
        ///// Grid settings /////
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);
        $grid->setDefaultSort(['surname' => 'ASC']);

        
        ///// Columns /////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnText('surname', 'Příjmení')
            ->setCustomRender(__DIR__ . '\surname.latte')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        $grid->addColumnText('firstname', 'Jméno')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        //// Actions ////
        $grid->addActionHref('athlete', 'Otevřít', 'session')
            ->setIcon('caret-square-o-right')
            ->setCustomHref(function($athlete) {
                return $this->presenter->link('Athlete:detail', [$athlete->id]);
            })
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('athlete', 'detail');
            });
            
//        $grid->addActionHref('new_record', 'Nový záznam')
//            ->setIcon('plus')
//            ->setCustomHref(function($session) {
//                return $this->presenter->link('Record:new', ['session_id' => $session->id, 'athlete_id' => $this->athlete_id]);
//            })
//            ->setDisable(function(){
//                return !$this->presenter->user->isAllowed('test', 'detail');
//            });
            
        return $grid;
    }
    
    public function setParams($coach_id)
    {
        $this->coach_id = $coach_id;
    }
    
}
