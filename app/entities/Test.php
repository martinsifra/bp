<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 * @author Martin Šifra <me@martinsifra.cz>
 * 
 * @ORM\Entity
 * @ORM\Table(name="test")
 * 
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string $eval PHP code to execute
 * @property string $unit_in
 * @property string $unit_out
 */
class Test extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $slug;
    
    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $name;   
    
    /**
     * @ORM\Column(type="string", length=1024)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $eval;
    
    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $unit;
}
