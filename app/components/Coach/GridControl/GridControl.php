<?php

namespace App\Components\Coach;

/**
 * 
 */
class GridControl extends \App\Components\Base\GridControl
{
    
    protected function createComponentGrido()
    {
        $grid = new \Grido\Grid();
        
        //// Datasource ////
        $repository = $this->em->getRepository('App\Entities\Coach');
        $qb = $repository->createQueryBuilder('c')
                ->addSelect('u')
                ->leftJoin('c.user', 'u');
        
        $grid->model = new \Grido\DataSources\Doctrine($qb,[
            'surname' => 'u.surname',
            'firstname' => 'u.firstname'
        ]);
        
        ///// Default settings /////
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        $grid->setDefaultSort([
            'surname' => 'ASC',
            'firstname' => 'ASC',
        ]);
        
        
        //// Columns ////
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
        $grid->addActionHref('detail', 'Otevřít')
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('athlete', 'show');
            })
            ->setIcon('caret-square-o-right');

        $grid->addActionHref('edit', 'Upravit')
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('athlete', 'edit');
            })
            ->setIcon('edit');
            
        return $grid;
    }
    
}
