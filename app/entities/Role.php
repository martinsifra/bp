<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends \App\Entities\IdentifiedEntity
{
    
    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $label;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     **/
    protected $users;


    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
}