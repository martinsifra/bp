<?php

namespace App\Components\Athlete;

/**
 * 
 */
class GridControl extends \App\Components\Base\GridControl
{
    
    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid($this, $name);
        
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Athlete');
        $qb = $repository->createQueryBuilder('b');
//                ->addSelect('a') // This will produce less SQL queries with prefetch.
//                ->innerJoin('b.author', 'a');
        
        $model = new \Grido\DataSources\Doctrine($qb);//, array( // Map grido columns to the Author entity
//            'firstname' => 'a.firstname',
//            'surname' => 'a.surname',
//            'birthdate' => 'a.surname'
//        ));
        $grid->model = $model;
        
        $grid->setDefaultSort(array('surname' => 'ASC'));
        
        //// Columns ////
        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnText('surname', 'Surname')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        $grid->addColumnText('firstname', 'Firstname')
            ->setSortable();
        
        $grid->addColumnDate('birthdate', 'Birthdate')
            ->setDateFormat('j.n.Y')
            ->setSortable()
            ->setFilterDateRange();
        
        //// Actions ////
        $grid->addActionHref('detail', 'Open')
            ->setIcon('folder-open')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('athlete', 'show');
            });

        $grid->addActionHref('edit', 'Edit')
            ->setIcon('pencil')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('athlete', 'edit');
            });
            
        $grid->addActionHref('record', 'New record')
            ->setCustomHref(function($item) {
                return $this->presenter->link('Record:new', ['athlete_id' => $item->id]);   
            })
            ->setIcon('asterisk')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('record', 'add');
            });
            
        $grid->addActionHref('remove', 'Remove', 'remove!')
            ->setIcon('remove')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('athlete', 'remove');
            });
    }
    
}
