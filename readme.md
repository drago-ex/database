# Drago Database

Simple database helpers built on top of Dibi.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/database/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/database/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Nette Framework
- dibi
- Composer

## Installation

```
composer require drago-ex/database
```

## Knowledge

- [Dibi - smart database layer for PHP](https://github.com/dg/dibi)

## Basic Model Example

A model uses the `Database` trait and describes its table with the `Table` attribute
(table name and primary key). The trait reads the connection from the `$connection` property:

```php
use Dibi\Connection;
use Drago\Attr\Table;
use Drago\Database\Database;

#[Table('table_name', 'id')]
class Model
{
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}
}
```

## Common Queries

The query methods return a query builder (`ExtraFluent`). Fetch the result with `record()` or
`recordAll()`, or run a write query with `execute()`:

```php
// All records
$rows = $this->model->read('*')->recordAll();

// Records by column value
$rows = $this->model->find('column', 'value')->recordAll();

// One page of records
$rows = $this->model->read('*')->orderBy('id')->limit(10)->offset(20)->recordAll();

// One record by the primary key, or null
$row = $this->model->get(1)->record();

// Delete records by column value
$this->model->delete('column', 'value')->execute();
```

Without an entity class, records are returned as `Dibi\Row` objects.

Save a record as an array. When the array contains the primary key with a value greater than zero,
the record is updated, otherwise a new record is inserted:
```php
$this->model->save(['column' => 'value']);
$this->model->save(['id' => 1, 'column' => 'value']);
```

## Using Entities

```php
use Drago\Database\Entity;

class SampleEntity extends Entity
{
	public const string Table = 'sample';
	public const string PrimaryKey = 'id';

	public ?int $id = null;
	public string $sample;
}
```

Use the entity in a model, so records are returned as `SampleEntity` objects:
```php
#[Table(SampleEntity::Table, SampleEntity::PrimaryKey, entity: SampleEntity::class)]
class Model
{
	/** @use Database<SampleEntity> */
	use Database;

	public function __construct(
		protected Connection $connection,
	) {
	}
}

$row = $this->model->get(1)->record();
echo $row->sample;

$rows = $this->model->read('*')->recordAll();
```

## Save Entity Records

Entities are saved the same way as arrays (updated when the primary key is set):
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';

$this->model->save($entity);
```

## Transactions

```php
$this->model->beginTransaction();
try {
	$this->model->save($entity);
	$this->model->commit();
} catch (Throwable $e) {
	$this->model->rollBack();
	throw $e;
}
```

## Entity Generation

For automatic entity generation, consider using the Drago Generator tool: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
