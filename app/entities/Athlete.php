<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Athlete extends User
{
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthdate;
    
    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="athlete")
     **/
    protected $records;

    
    public function __construct() {
        parent::__construct();
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
