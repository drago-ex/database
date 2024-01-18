## Drago Database
Simple recurring questions.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/database/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/database/badge)](https://www.codefactor.io/repository/github/drago-ex/database)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/database/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/database?branch=master)

## Technology
- PHP 8.1 or higher
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
class Model {}
```

## Basic queries in the Repository

Get records from table.
```php
$this->model->table();
```

Search for a record by column name.
```php
$this->model->table('email', 'email@email.com');
```

Get records by table name.
```php
$this->model->of('table');
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
	public const Table = 'table';
	public const Id = 'id';

	public ?int $id = null;
	public string $sample;
}
```

Basic repository.
```php
#[Table(SampleEntity::Table, SampleEntity::Id)]
class Repository {}
```

Use of an entity in a repository.
```php
function find(int $id): array|SampleEntity|null
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
