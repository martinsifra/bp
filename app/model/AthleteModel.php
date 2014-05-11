<?php

namespace App\Model;

class AthleteModel extends BaseModel
{
    public function forSelect()
    {
        $athletes = $this->dao->findAll();
        $pairs = [];
        
        foreach ($athletes as $athlete) {
            $pairs[$athlete->id] = $athlete->surname . ' ' . $athlete->firstname; 
        }
        
        return $pairs;
    }
}