<?php

namespace App\Model;

class RoleModel extends BaseModel
{
    
    public function findByName($name)
    {
        return $this->dao->findOneBy(['name' => $name]);
    }
}