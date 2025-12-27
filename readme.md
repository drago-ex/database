## Drago Database
Simple recurring questions.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/database/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/database/badge)](https://www.codefactor.io/repository/github/drago-ex/database)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/database/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/database?branch=master)

## Requirements
- PHP >= 8.3
- Nette Framework
- dibi
- Composer

## Knowledge
- [Dibi - smart database layer for PHP](https://github.com/dg/dibi)

## Installation
```
composer require drago-ex/database
```

## Basic Model Example
```php
#[Table('table_name', 'primary_key')]
class Model
{
    use Database;
}
```

## Common Queries
Reading records from a table:
```php
$this->model->read('*');
```

Find records by column name:
```php
$this->model->find('column, 'value');
```

Get a record by ID:
```php
$this->model->get(1);
```

Delete a record by column name:
```php
$this->model->delete('column, 'value');
```

Save records as an array (update if `id` is provided):
```php
$this->model->save(['column' => 'value']);
```

## Using Entities
```php
class SampleEntity extends Drago\Database\Entity
{
	public const Table = 'name';
	public const PrimaryKey = 'id';

	public ?int $id = null;
	public string $sample;
}
```

Use the entity in a model:
```php
#[From(SampleEntity::Table, SampleEntity::PrimarKey)]
class Model
{
    use Database;
}
```

Fetch records as objects:
```php
$row = $this->model->find('id', 1)->record();

// Accessing properties
echo $row->id;
echo $row->sample;
```

## Save Entity Records
To save entity data (update record if `id` is present):
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';

$this->save($entity);
```

# Advanced Features
## Entity Class for Database Mapping
You can use a custom entity class with database mapping:
```php
/** @extends Database<SampleEntity> */
#[From(SampleEntity::Table, SampleEntity::PrimaryKey, class: SampleEntity::class)]
class Model
{
    use Database;
}

// Fetch records directly as objects
$row = $this->model->find('id', 1)->record();

// Access the object's properties
echo $row->id;
echo $row->sample;

// Fetch all records
$allRecords = $this->model->read('*')->recordAll();
```

## Entity Generation
For automatic entity generation, consider using the Drago Generator tool: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
