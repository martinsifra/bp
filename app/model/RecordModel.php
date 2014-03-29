<?php

namespace App\Model;

class RecordModel extends BaseModel
{
    public function findBySession($session_id)
    {
        $this->dao->createQueryBuilder('')
                ->
                
        $record = $this->dao->related('records');
        
        return $this->dao->createQuery('SELECT s FROM App\Entities\Session s JOIN s.records r WHERE r.athlete = :user_id')
                ->setParameter('user_id', $user_id)
                ->getResult();
    }
}