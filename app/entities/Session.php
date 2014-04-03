<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 * @author Martin Šifra <me@martinsifra.cz>
 * 
 * @ORM\Entity
 * @ORM\Table(name="session")
 * 
 * @property string $title
 * @property string $description
 */
class Session extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="session")
     */
    protected $records;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=1024)
     */
    protected $description;



    public function __construct() {
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
