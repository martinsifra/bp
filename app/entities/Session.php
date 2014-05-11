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
class Session extends \App\Entities\IdentifiedEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Record", mappedBy="session")
     */
    protected $records;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=1024)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $date;



    public function __construct() {
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
