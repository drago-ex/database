<?php 

declare(strict_types = 1);

/**
 * Drago Database
 * @copyright Zdeněk Papučík
 */
namespace Drago\Database;
use Nette;

/**
 * Base entity.
 * @package Drago\Database
 */
trait Entity
{
	/** @var int */
	private $id;


	public function setId(int $id)
	{
		$this->id = $id;
	}


	/**
	 * Get the record ID.
	 */
	public function getId(): int
	{
		return $this->id;
	}
}
