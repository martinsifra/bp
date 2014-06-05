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
class Athlete extends \App\Entities\IdentifiedEntity
{
    
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="athlete", fetch="EAGER", cascade={"all"})
     **/
    protected $user;
    
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

    /**
     * @ORM\ManyToMany(targetEntity="Coach", mappedBy="athletes")
     **/
    protected $coaches;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coaches = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    public function getSurname()
    {
        return $this->user->surname;
    }
    
    
    public function getFirstname()
    {
        return $this->user->firstname;
    }
    
}
