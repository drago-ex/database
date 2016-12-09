<?php

/**
 * Drago Database, extending Nette Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

/**
 * Database mapper.
 * @author Zdeněk Papučík
 */
interface IMapperFindAll
{
	/**
	 * Return all records.
	 */
	public function getAll();

}
