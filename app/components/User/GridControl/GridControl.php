<?php

namespace App\Components\User;

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
        $repository = $this->em->getRepository('\App\Entities\User');
        $qb = $repository->createQueryBuilder('b')
                ->addSelect('a') // This will produce less SQL queries with prefetch.
                ->innerJoin('b.roles', 'a');
        
        $model = new \Grido\DataSources\Doctrine($qb);//, array( // Map grido columns to the Author entity
//            'role' => 'a.role',
//            'surname' => 'a.surname',
//            'birthdate' => 'a.surname'
//        ));
        $grid->model = $model;
        
        $grid->setDefaultSort(array('surname' => 'ASC'));
        
        //// Columns ////
        $grid->addColumnText('id', 'ID');

        $grid->addColumnText('username', 'Username')
                ->setSortable()
                ->setFilterText()
                    ->setSuggestion();
        
        $grid->addColumnText('surname', 'Surname')
                ->setSortable()
                ->setFilterText()
                    ->setSuggestion();
        
        $grid->addColumnText('firstname', 'Firstname')
                ->setSortable();
        
        $grid->addColumnText('roles', 'Roles')
                ->setCustomRender(function ($user) {
                    $string = '';
                    foreach ($user->roles as $role) {
                        $string .= $role->label . ', ';
                    }
                    
                    return $string;
                });
        
        //// Actions ////
        $grid->addActionHref('detail', 'Detail')
            ->setIcon('eye-open')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('athlete', 'detail');
            });

        $grid->addActionHref('edit', 'Edit')
            ->setIcon('pencil')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('athlete', 'edit');
            });
            
        $grid->addActionHref('remove', 'Remove', 'remove!')
            ->setIcon('remove')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('athlete', 'remove');
            });
    }
}
