<?php

namespace App\Presenters;

/**
 * Example usage of Grido datagrid component with Doctrine 2 datasource.
 * @author Martin Šifra
 */
class GridoPresenter extends BasePresenter
{

    /** @var \App\Components\IGridBookControlFactory @inject */
    public $gridBookControlFactory;

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $entityManager;
    
    
    ///// Components /////
    
    /*
     * Grid with Books.
     * @return \App\Components\GridBookControl
     */
    protected function createComponentGridBook($name)
    {
        $control = new \App\Components\GridBookControl($this, $name);
        $control->em = $this->entityManager;
        return $control;
//        return $this->gridBookControlFactory->create();
    }
    
    protected function createComponentGrid($name)
    {
        $grid = new \Grido\Grid($this, $name);

        //// Datasource ////
        $repository = $this->entityManager->getRepository('\App\Entities\Athlete');
        $qb = $repository->createQueryBuilder('b');
//                ->addSelect('a') // This will produce less SQL queries with prefetch.
//                ->innerJoin('b.author', 'a');
        
        $model = new \Grido\DataSources\Doctrine($qb);//, array( // Map grido columns to the Author entity
//            'firstname' => 'a.firstname',
//            'surname' => 'a.surname',
//            'birthdate' => 'a.surname'
//        ));
        $grid->model = $model;
        
        $grid->setDefaultPerPage(10)
            ->setPerPageList(array(5, 10, 20, 30, 50, 100));
//        $grid->setDefaultSort(array('title' => 'ASC'));
        
        //// Columns ////
        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnText('surname', 'Surname')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        
        $grid->addColumnText('firstname', 'Firstname')
            ->setSortable();
        
        $grid->addColumnDate('birthdate', 'Birthdate')
            ->setDateFormat('j.d.Y')
            ->setSortable()
            ->setFilterDateRange();
    }
}
