## Drago Database
Connecting to database.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/database/badge)](https://www.codefactor.io/repository/github/drago-ex/database)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/database/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/database?branch=master)

## Technology
- PHP 8.0 or higher
- composer

## Knowledge
- [Dibi - smart database layer for PHP](https://github.com/dg/dibi)

## Installation
```
composer require drago-ex/database
```

## Use
```php
#[Table('table', 'id')]
class Model extends Drago\Database\Connect {}
```

## Creating queries
```php
$this->db;
```

## Basic queries in the Repository

Returns all records.
```php
$this->model->all();
```

Search for a record by column name.
```php
$this->model->discover('email', 'email@email.com');
```

Search for a record by id.
```php
$this->model->get(1);
```

Delete a record from the database.
```php
$this->model->remove(1);
```

Save record (the update will be performed if a column with id is added).
```php
$this->model->put(['column' => 'record']);
```

## Use of entity
```php
class SampleEntity extends Drago\Database\Entity
{
	public const TABLE = 'table';
	public const PRIMARY = 'id';
	public const SAMPLE = 'sample';

	public int $id;
	public string $sample;
}
```

Basic repository.
```php
#[Table(SampleEntity::TABLE, SampleEntity::PRIMARY)]
class Repository extends Drago\Database\Connect {}
```

Use of an entity in a repository.
```php
function find(int $id): array|SampleEntity|Row|null
{
	return $this->get($id)->fetch();
}
```

Reading data.
```php
$row = $this->find(1);
echo $row->id;
echo $row->sample;
```

Save records across an entity (to update the record we add id).
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';

$this->save($entity);
```

The save method saves the record to the database.
```php
function save(SampleEntity $entity): Result|int|null
{
	return $this->put($entity->toArray());
}
```

## Tips
You can also use entities and have them generated. [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
