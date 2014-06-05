<?php

namespace App\Components\Athlete;

/**
 * 
 */
class CoachGridControl extends \App\Components\Base\GridControl
{
    
    /** @var int $athlete_id */
    private $athlete_id;
    
    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid();

        ///// Datasource /////
        $repository = $this->em->getRepository('\App\Entities\Coach');
        $qb = $repository->createQueryBuilder('c')
                ->addSelect('u')
                ->join('c.athletes', 'a')
                ->join('c.user', 'u')
                ->where('a = :athlete_id')
                ->setParameters([
                    'athlete_id' => $this->athlete_id,
                ]);        
        $grid->model = new \Grido\DataSources\Doctrine($qb);

        
        ///// Grid settings /////
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);
        $grid->setDefaultSort([]);

        
        ///// Columns /////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnText('surname', 'Příjmení')
            ->setCustomRender(__DIR__ . '\surname.latte')
            ->setSortable();
        
        $grid->addColumnText('firstname', 'Jméno')
            ->setSortable();
            
        return $grid;
    }
    
    public function setParams($athlete_id)
    {
        $this->athlete_id = $athlete_id;
    }
    
}
