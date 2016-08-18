<?php

/**
 * This file is part of the Drago Framework
 * Copyright (c) 2015, Zdeněk Papučík
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
