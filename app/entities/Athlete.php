<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * 
 * @property \Nette\DateTime $birthdate
 * @property string $sex
 * @property \Doctrine\Common\Collections\ArrayCollection $records
 */
class Athlete extends User
{
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthdate;
    
    /**
     * @ORM\Column(type="string", length=16)
     */ 
    protected $sex;
    
    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="athlete")
     **/
    protected $records;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
