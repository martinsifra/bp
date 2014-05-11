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
        
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Session');
        $qb = $repository->createQueryBuilder('b');
//                ->addSelect('a') // This will produce less SQL queries with prefetch.
//                ->innerJoin('b.author', 'a');
        
        $model = new \Grido\DataSources\Doctrine($qb);//, array( // Map grido columns to the Author entity
//            'firstname' => 'a.firstname',
//            'surname' => 'a.surname',
//            'birthdate' => 'a.surname'
//        ));
        $grid->model = $model;
        
        $grid->setDefaultSort(array('id' => 'DESC'));

        
        //// Columns ////
        if ($this->settings->grid['id']) {
            $grid->addColumnText('id', 'ID');
        }
        
        $grid->addColumnText('name', 'Název')
            ->setSortable();

        $grid->addColumnDate('date', 'Datum', 'j.n.Y')
            ->setSortable();
        
        $grid->getColumn('date')->cellPrototype->class[] = 'center';
        $grid->getColumn('date')->headerPrototype->class[] = 'center';
        
        
        //// Actions ////
        $grid->addActionHref('detail', 'Open')
            ->setIcon('caret-square-o-right')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('session', 'show');
            });
            
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setIcon('remove')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('test', 'remove');
//            });
            
        return $grid;
    }
    
}
