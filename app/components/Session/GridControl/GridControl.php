<?php

namespace App\Components\Session;

/*
 * 
 */
class GridControl extends \App\Components\Base\GridControl
{
    
    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid($this, $name);
        
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);

        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Session');
        $qb = $repository->createQueryBuilder('b');
        $grid->model = new \Grido\DataSources\Doctrine($qb);
        
        $grid->setDefaultSort(array('id' => 'DESC'));

        
        //// Columns ////
        if ($this->settings->grid['id']) {
            $grid->addColumnText('id', 'ID');
        }
        
        $grid->addColumnDate('date', 'Datum', 'j.n.Y')
            ->setSortable();
        
        $grid->addColumnText('name', 'Název')
            ->setSortable();
        
        $grid->getColumn('date')->cellPrototype->class[] = 'center';
        $grid->getColumn('date')->headerPrototype->class[] = 'center';
        
        
        //// Actions ////
        $grid->addActionHref('detail', 'Open')
            ->setIcon('caret-square-o-right')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('session', 'show');
            });
            
        return $grid;
    }
    
}
