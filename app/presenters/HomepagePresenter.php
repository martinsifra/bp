<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
    /** @var \App\Model\Articles @inject */
    //public $articles;

    /** @var \Kdyby\Doctrine\EntityManager @inject */
    public $entityManager;


    public function __construct() {
        parent::__construct();
     
    }

    /**
     * @secured
     * @resource('homepage')
     * @privilege('default')
     */
    public function renderDefault($id)
	{
//        $books = $this->entityManager->getDao(\App\Entities\Book::getClassName());
//
//        $athlete = new \App\Entities\Athlete();
//        $athlete->username = "radovan";
//        $athlete->password = "$2y$10$6y7exPNgXqn/T6nvBeYOz.pnDYHj6Q.xy6h1EheJvUW2lbXBEyx8O"; // "heslo"
//        $athlete->firstname = "Radovan";
//        $athlete->surname = "Sifra";
//        
//        $role = $this->entityManager->find('\App\Entities\Role', 3);
//        
//        $athlete->addRole($role);
//        
//        $this->entityManager->persist($athlete);
//        
//        $this->entityManager->flush();
        
        //$this->template->books = $this->entityManager->getRepository('App\Entities\Book')->findAll();
        
//        foreach ($this->template->books as $book) {
//            $author = $book->getAuthor();
//            echo $author->getFirstname() . '&nbsp' . $author->getSurname() . ': ';
//            echo $book->title . '<br>';
//        }
	}
}
