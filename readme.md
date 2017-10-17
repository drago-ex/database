## Drago Database

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0a573beafc964543af530617a71467fd)](https://www.codacy.com/app/accgit/database?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/database&utm_campaign=badger)

Connect to database server.

## Requirements

- PHP 7.0.8 or higher
- composer

## Installation

```
composer require drago-ex/database
```

## Register the extension

```
extensions:

	# Connect to database server.
	dibi: Dibi\Bridges\Nette\DibiExtension22

# Settings database.
dibi:
	host:
	driver:
	username:
	password:
	database:
	lazy: true
	#substitutes:
		#prefix:
```

## Basic class for entities

The package contains an abstract class for entities that can serve us to insert or detect a record id.

## Class Iterator

In order to insert or edit values from entities in a database, we must forward them to insert and update methods in the form of array.
In this case, we can use the Iterator class, which returns those values as array.

## An example of how to insert or update a record

```php
/**
 * @param mixed
 * @return void
 */
public function save(Entity $entity)
{
	if (!$entity->getId()) {
		return $this->db
			->insert('table', Database\Iterator::set($entity))
			->execute();
	} else {
		return $this->db
			->update('table', Database\Iterator::set($entity))
			->where('id = ?', $entity->getId())
			->execute();
	}
}
```

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
