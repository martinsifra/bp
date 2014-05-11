<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="author")
 */
class Author extends \App\Entities\IdentifiedEntity
{

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $surname;
}
