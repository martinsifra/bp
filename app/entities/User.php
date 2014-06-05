<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * 
 * @property string $username
 * @property string $firstname
 * @property string $surname
 */
class User extends \App\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     **/
    protected $roles;
    
    /**
     * @ORM\OneToOne(targetEntity="Athlete", inversedBy="user", fetch="LAZY", cascade={"all"})
     **/
    protected $athlete;
    
    /**
     * @ORM\OneToOne(targetEntity="Coach", inversedBy="user", fetch="LAZY")
     **/
    protected $coach;
    
    
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }
}