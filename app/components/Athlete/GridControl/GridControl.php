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
        
        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Athlete');
        $qb = $repository->createQueryBuilder('b');
        $grid->model = new \Grido\DataSources\Doctrine($qb);
        
        ///// Default settings /////
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        $grid->setDefaultSort([
            'surname' => 'ASC',
        ]);
        
        
        //// Columns ////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnText('surname', 'Příjmení')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        $grid->addColumnText('firstname', 'Jméno')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        $grid->addColumnDate('birthdate', 'Datum narození')
            ->setDateFormat('j.n.Y')
            ->setSortable();

        
        
        //// Actions ////
        $grid->addActionHref('detail', 'Otevřít')
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('athlete', 'show');
            })
            ->setIcon('expand');

// Protože záznam náleží vždy určité session, přesuneme tlačítko až tam.
//        $grid->addActionHref('record', 'New record')
//            ->setCustomHref(function($item){
//                return $this->presenter->link('Record:new', ['athlete_id' => $item->id]);   
//            })
//            ->setDisable(function(){
//                return !$this->presenter->user->isAllowed('record', 'add');
//            })
//            ->setIcon('plus');

        $grid->addActionHref('edit', 'Upravit')
            ->setDisable(function(){
                return !$this->presenter->user->isAllowed('athlete', 'edit');
            })
            ->setIcon('edit');
            

// Závodníci se mažou jen zřídka, tlačítko proto přesuneme až na stránku s detailem závodníka
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setDisable(function(){
//                return !$this->presenter->user->isAllowed('athlete', 'remove');
//            })
//            ->setIcon('remove');
    }
    
}
