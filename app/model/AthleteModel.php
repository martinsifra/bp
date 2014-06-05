<?php

namespace App\Model;

class AthleteModel extends BaseModel
{
    public function __construct(\Kdyby\Doctrine\EntityDao $dao, \Kdyby\Doctrine\EntityManager $em)
    {
        parent::__construct($dao);
    }
    
    public function forSelect()
    {
        $athletes = $this->dao->findAll();
        $pairs = [];
        
        foreach ($athletes as $athlete) {
            $pairs[$athlete->id] = $athlete->surname . ' ' . $athlete->firstname; 
        }
        
        return $pairs;
    }
    
    
    public function toMultiSelect(){
        $athletes = [];
        
        $data = $this->dao->createQueryBuilder('a')
                ->addSelect('u')
                ->leftJoin('a.user', 'u')
                ->orderBy('u.surname', 'ASC')
                ->getQuery()->getResult();  
                

        foreach ($data as $athlete) {
            $athletes[] = [
                'value' => $athlete->id,
                'text' => $athlete->surname . ' ' . $athlete->firstname
            ];
        }
        
        return $athletes;
    }
    
    public function toMultiSelector(){
        $athletes = [];
        
        $data = $this->dao->createQueryBuilder('a')
                ->addSelect('u')
                ->leftJoin('a.user', 'u')
                ->orderBy('u.surname', 'ASC')
                ->getQuery()->getResult();  
                

        foreach ($data as $athlete) {
            $athletes[$athlete->id] = $athlete->surname . ' ' . $athlete->firstname;
        }
        
        return $athletes;
    }

}