<?php

namespace App\Components\Test;

/*
 * 
 */
class GridControl extends \App\Components\Base\GridControl
{
    
    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid($this, $name);
        
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Test');
        $qb = $repository->createQueryBuilder('b');
        
        $model = new \Grido\DataSources\Doctrine($qb);
        $grid->model = $model;
        
        $grid->setDefaultSort(array('name' => 'ASC'));

        
        //// Columns ////
        //$grid->addColumnText('id', 'ID');

        $grid->addColumnText('slug', 'Slug');
        
        $grid->addColumnText('name', 'Název')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('unit', 'Jednotka');
        
        
        //// Actions ////
        $grid->addActionHref('detail', 'Otevřít')
            ->setIcon('caret-square-o-right')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('test', 'show');
            });
            
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setIcon('remove')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('test', 'remove');
//            });
            
        return $grid;
    }
    
}
