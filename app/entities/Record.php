<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 * @author Martin Šifra <me@martinsifra.cz>
 * 
 * @ORM\Entity
 * @ORM\Table(name="record")
 * 
 * @property \App\Entities\Session $session
 * @property \App\Entities\Test $test
 * @property \App\Entities\Athlete $athlete
 * @property string $value
 */
class Record extends \Kdyby\Doctrine\Entities\IdentifiedEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Session", inversedBy="records")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
     **/
    protected $session;
    
    /**
     * @ORM\OneToOne(targetEntity="Test")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     **/
    protected $test;
    
    /**
     * @ORM\ManyToOne(targetEntity="Athlete", inversedBy="records")
     * @ORM\JoinColumn(name="athlete_id", referencedColumnName="id")
     **/
    protected $athlete;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $value;
}
