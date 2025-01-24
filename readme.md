## Drago Database
A simple and powerful database library for PHP built on top of Dibi.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/database/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/database/badge)](https://www.codefactor.io/repository/github/drago-ex/database)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/database/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/database?branch=master)

## Technology
- PHP 8.3 or higher
- composer

## Knowledge
- [Dibi - smart database layer for PHP](https://github.com/dg/dibi)

## Installation
```
composer require drago-ex/database
```

## Quick Start
### Define a Model with Attributes
To define a model (or entity) class, use the Table attribute to specify the table and primary key:
```php
#[Table('table', 'id')]
class Model {}
```

## Basic Repository Queries

Get all records from a table:
```php
$this->model->table();
```

Search for a record by column name:
```php
$this->model->table('email = ?', 'email@email.com');
```

Search for a record by its `id`:
```php
$this->model->get(1);
```

Delete a record by `id`:
```php
$this->model->remove(1);
```

Save a record (use `id` to perform an update if it exists):
```php
$this->model->put(['column' => 'record']);
```

## Using an Entity in the Repository
Define your entity (model class) that extends the `Entity` class:
```php
class SampleEntity extends Drago\Database\Entity
{
	public const Table = 'table';
	public const PrimaryKey = 'id';

	public ?int $id = null;
	public string $sample;
}
```

Create a repository class:
```php
#[Table(SampleEntity::Table, SampleEntity::PrimarKey)]
class Repository {}
```

## Querying with the Entity
Find a record by its `id`:
```php
function find(int $id): array|SampleEntity|null
{
	return $this->get($id)->fetch();
}
```

Reading data from a record:
```php
$row = $this->find(1);
echo $row->id;
echo $row->sample;
```

## Saving an Entity
To save a record, you can use the `put` method. If the `id` exists, it will perform an update;
otherwise, it will insert a new record:
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';

$this->save($entity);
```

The save method saves the record to the database:
```php
function save(SampleEntity $entity): Result|int|null
{
	return $this->put($entity);
}
```

## Tips
You can also use entities and have them generated. [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
