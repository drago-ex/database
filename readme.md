## Drago Database

Connect to database server.

## Requirements

- PHP 7.1 or higher
- composer

## Installation

```
composer require drago-ex/database
```

## Register the extension

```
extensions:

	# library to connect to the database
	dibi: Dibi\Bridges\Nette\DibiExtension22

# settings database
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

## Class for entity

The package contains a base class for entity in which the setter and getter method is used to work with id.

## Class Iterator

To insert or update values from an entity to a database, we must pass the values in the array.
In this case, we can use the Iterator class, which returns those values as array.

## An example of how to insert or update values

```php
/**
 * Save values to database.
 */
public function save(Entity $entity)
{
	$entity->getId() ?
	$this->db->query('UPDATE :prefix:privileges SET  %a', Iterator::toArray($entity), 'WHERE id = ?', $entity->getId()) :
	$this->db->query('INSERT INTO :prefix:privileges %v', Iterator::toArray($entity));
}
```

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
