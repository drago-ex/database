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
#[From('table', 'id')]
class Model extends Database {}
```

## Basic queries in the Model

Get all columns from table.
```php
$this->model->read();
```

Get specific columns from table.
```php
$this->model->read('column');
```

Find record by column name in the table.
```php
$this->model->find('column, 'value');
```

Delete record.
```php
$this->model->delete('column, 'value');
```

Save record (the update will be performed if a column with id is added).
```php
$this->model->save(['column' => 'record']);
```

## Use of entity
```php
class SampleEntity extends Drago\Database\Entity
{
	public const Table = 'table';
	public const PrimaryKey = 'id';

	public ?int $id = null;
	public string $sample;
}
```

Basic repository.
```php
#[From(SampleEntity::Table, SampleEntity::PrimarKey)]
class Repository extends Database {}
```

Use of an entity in a repository.
```php
function findById(int $id): SampleEntity|null
{
	return $this->find(SampleEntity::PrimarKey, $id)->record();
}
```

Reading data.
```php
$row = $this->findById(1);
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

## Tips
You can also use entities and have them generated. [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
