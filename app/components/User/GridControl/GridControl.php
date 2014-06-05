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
        $qb = $repository->createQueryBuilder('u')
                ->select('u, r, a') // This will produce less SQL queries with prefetch.
                ->leftJoin('u.roles', 'r')
                ->leftJoin('u.athlete', 'a');
        
        $grid->model = new \Grido\DataSources\Doctrine($qb, [
            'roles' => 'r.id',
            'label' => 'r.label'
        ]);
        
        $grid->setDefaultSort([
            'surname' => 'ASC',
            'firstname' => 'ASC',
            'label' => 'ASC'
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

        $grid->addColumnText('username', 'Uživatelské jméno')
                ->setSortable()
                ->setFilterText()
                    ->setSuggestion();
        
        $grid->addColumnText('roles', 'Role')
                ->setSortable()
                ->setCustomRender(__DIR__ . "\\tag.latte");
        
                
        //// Actions ////
        $grid->addActionHref('detail', 'Otevřít')
            ->setIcon('caret-square-o-right')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('user', 'detail');
            });

        $grid->addActionHref('edit', 'Upravit')
            ->setIcon('pencil-square-o')
            ->setDisable(function () {
                return !$this->presenter->user->isAllowed('user', 'edit');
            });
            
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setIcon('remove')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('athlete', 'remove');
//            });
//            
        //// Filters ////
//        $grid->addFilterSelect('role', 'Role', ['' => 'Vše'] + $this->em->getDao('App\Entities\Role')->findPairs('label'))
//                ->setColumn('roles');


        return $grid;
    }
}
