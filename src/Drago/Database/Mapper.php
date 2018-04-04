<?php

/**
 * Drago Database
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Database;

/**
 * Pattern for repository.
 */
interface Mapper
{
	/**
	 * Get all records.
	 */
	public function all();

	/**
	 * Find records.
	 * @param int
	 */
	public function find($id);

	/**
	 * Save records to database.
	 * @param mixed
	 */
	public function save($entity);

}
