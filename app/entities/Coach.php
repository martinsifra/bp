<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * 
 * @property string $sex
 * @property \Doctrine\Common\Collections\ArrayCollection $athletes
 */
class Coach extends \App\Entities\IdentifiedEntity
{
    
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="coach", fetch="EAGER", cascade={"persist"})
     **/
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Athlete", inversedBy="coaches", cascade={"persist"})
     **/
    protected $athletes;

    
    public function __construct()
    {
        parent::__construct();
        $this->athletes = new \Doctrine\Common\Collections\ArrayCollection();
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
