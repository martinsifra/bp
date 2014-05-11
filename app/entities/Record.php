<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 * @author Martin Šifra <me@martinsifra.cz>
 * 
 * @ORM\Entity
 * @ORM\Table(name="record", uniqueConstraints={@ORM\UniqueConstraint(name="unique_idx", columns={"session_id", "test_id", "athlete_id"})})
 * 
 * @property \App\Entities\Session $session
 * @property \App\Entities\Test $test
 * @property \App\Entities\Athlete $athlete
 * @property string $value
 */
class Record extends \App\Entities\IdentifiedEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Session", inversedBy="records")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
     */
    protected $session;
    
    /**
     * @ORM\ManyToOne(targetEntity="Test", fetch="EAGER")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     */
    protected $test;
    
    /**
     * @ORM\ManyToOne(targetEntity="Athlete", inversedBy="records")
     * @ORM\JoinColumn(name="athlete_id", referencedColumnName="id")
     */
    protected $athlete;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $value;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;
}
