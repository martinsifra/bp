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
//                ->addSelect('a') // This will produce less SQL queries with prefetch.
//                ->innerJoin('b.author', 'a');
        
        $model = new \Grido\DataSources\Doctrine($qb);//, array( // Map grido columns to the Author entity
//            'firstname' => 'a.firstname',
//            'surname' => 'a.surname',
//            'birthdate' => 'a.surname'
//        ));
        $grid->model = $model;
        
        $grid->setDefaultSort(array('name' => 'ASC'));

        
        //// Columns ////
        $grid->addColumnText('id', 'ID');

        $grid->addColumnText('slug', 'Slug');
        
        $grid->addColumnText('name', 'Name')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('unit', 'Unit');
        
        
        //// Actions ////
        $grid->addActionHref('detail', 'Open')
            ->setIcon('folder-open')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('test', 'show');
            });
            
        $grid->addActionHref('remove', 'Remove', 'remove!')
            ->setIcon('remove')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('test', 'remove');
            });
            
        return $grid;
    }
    
}
