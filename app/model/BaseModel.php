<?php

namespace App\Model;

class BaseModel extends \Nette\Object
{
    /** @var \Kdyby\Doctrine\EntityDao */
    public $dao;

    public function __construct(\Kdyby\Doctrine\EntityDao $dao)
    {
        $this->dao = $dao;
    }

    public function findById($id)
    {
        return $this->dao->findOneBy(array('id' => $id));
    }
    
    public function find($id)
    {
        return $this->dao->find($id);
    }
    
    public function save(\Kdyby\Doctrine\Entities\IdentifiedEntity $article)
    {
        return $this->dao->save($article);
    }
    
    public function findAll()
    {
        return $this->dao->findAll();
    }
    
    public function findPairs($criteria, $value = NULL, $key = NULL)
    {
        return $this->dao->findPairs($criteria, $value, $key);
    }
    
    // Delete metoda bude soft delete

}