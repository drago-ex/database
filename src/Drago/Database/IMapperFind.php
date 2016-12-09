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
interface IMapperFind
{
	/**
	 * Find records.
	 * @param mixed
	 */
	public function find($args);

}
