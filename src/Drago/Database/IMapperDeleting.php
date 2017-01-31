<?php

/**
 * Extending for Nette Framework.
 * Copyright (c) 2015, Zdeněk Papučík
 *
 * @package Drago
 */
namespace Drago\Database;

/**
 * Database mapper.
 * @author Zdeněk Papučík
 */
interface IMapperDeleting
{
	/**
	 *  Delete records.
	 * @param mixed
	 */
	public function delete($args);

}
