<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="child_entity", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "athlete" = "Athlete"})
 * 
 * @property string $username
 * @property string $firstname
 * @property string $surname
 */
class User extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $password;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $firstname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $surname;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     **/
    protected $roles;

    
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }
}