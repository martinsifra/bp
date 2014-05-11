<?php

namespace App\Entities;

use Doctrine\ORM\Proxy\Proxy;
use Doctrine\ORM\Mapping as ORM;
use Nette;
use Kdyby;



/**
 *
 * @ORM\MappedSuperclass()
 *
 * @property-read int $id
 */
abstract class IdentifiedEntity extends \Kdyby\Doctrine\Entities\BaseEntity
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var integer
	 */
	protected $id;


	/**
	 * @return integer
	 */
	final public function getId()
	{
		return $this->id;
	}


	public function __clone()
	{
		$this->id = NULL;
	}

}
