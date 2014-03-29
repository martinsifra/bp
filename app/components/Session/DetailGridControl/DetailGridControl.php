<?php

namespace App\Components\Session;

use Nette\Application\UI\Control;

/*
 * 
 */
class DetailGridControl extends Control
{
    
    /** @var \Kdyby\Doctrine\EntityManager */
    public $em;

    public function __construct(\Kdyby\Doctrine\EntityManager $em) {
        $this->em = $em; 
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/DetailGridControl.latte');
        $template->render();
    }
    
    protected function createComponentGrido($name)
    {
        $grid = new \Grido\Grid($this, $name);
        
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        //// Datasource ////
        $repository = $this->em->getRepository('\App\Entities\Record');
        $qb = $repository->createQueryBuilder('r')
                ->addSelect('a') // This will produce less SQL queries with prefetch.
                ->join('r.athlete', 'a')
                ->where('r.session = 1');
        
        $model = new \Grido\DataSources\Doctrine($qb, array(
            'weight' => 'r.value',
            'firstname' => 'a.firstname',
//            'surname' => 'r.athlete',
        ));
        $grid->model = $model;
        
        $grid->setDefaultSort(array('id' => 'DESC'));

        
        //// Columns ////
        $grid->addColumnText('id', 'ID');

        $grid->addColumnText('firstname', 'Firstname');
        $grid->addColumnText('surname', 'Surname');
            
            
        $grid->addColumnText('weight', 'Váha')
            ->setCustomRender(function($item){
                return $item->record->value;
            })
            ->setSortable();
//        
//        $grid->addColumnText('surname', 'Surname')
//            ->setSortable()
//            ->setFilterText()
//                ->setSuggestion();
//        
//        $grid->addColumnNumber('hmotnost', 'Hmotnost');
       
        
        //// Actions ////
        $grid->addActionHref('detail', 'Open')
            ->setIcon('folder-open')
            ->setDisable(function() {
                return !$this->presenter->user->isAllowed('session', 'show');
            });
            
//        $grid->addActionHref('remove', 'Remove', 'remove!')
//            ->setIcon('remove')
//            ->setDisable(function () {
//                return !$this->presenter->user->isAllowed('test', 'remove');
//            });
    }
    
}
