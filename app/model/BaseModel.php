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
    
    public function save(\App\Entities\IdentifiedEntity $entity)
    {
        return $this->dao->save($entity);
    }
    
    public function findAll()
    {
        return $this->dao->findAll();
    }
    
    public function findPairs($criteria, $value = NULL, $key = NULL)
    {
        return $this->dao->findPairs($criteria, $value, $key);
    }
    
    public function findBy(array $criteria, $orderBy = NULL, $limit = NULL, $offset = NULL)
    {
        return $this->dao->findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findOneBy($criteria, $orderBy = NULL)
    {
        return $this->dao->findOneBy($criteria, $orderBy);
    }
    
    public function add($entity, $relations = NULL)
    {
        return $this->dao->add($entity, $relations);
    }




    // Delete metoda bude soft delete

}