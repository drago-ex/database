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
		private readonly Connection $connection,
	) {
	}
}
```

## Common Queries

The query methods return a query builder (`ExtraFluent`), so the query can still be extended
(`where()`, `orderBy()`…). Fetch the result with `record()` (a select without a limit reads one row)
or `recordAll()`, or run a write query with `execute()`. A query builder that is thrown away, such as
`delete()` without `execute()`, is reported by PHP 8.5 and PHPStan (`#[\NoDiscard]`):

```php
// All records
$rows = $this->model->read('*')->recordAll();

// Records by column value
$rows = $this->model->find('column', 'value')->recordAll();

// One page of records
$rows = $this->model->read('*')->orderBy('id')->limit(10)->offset(20)->recordAll();

// One record by the primary key, or null
$row = $this->model->get(1)->record();

// Number of records, all or matching a condition
$count = $this->model->count();
$count = $this->model->count(['column' => 'value']);

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
	public ?string $sampleNote = null;
}
```

Properties are the camelCase names of the columns: column `sample_note` fills `$sampleNote`, and saving
writes the value back to `sample_note`. For Oracle, the entity extends `EntityOracle`, which converts
uppercase columns (`SAMPLE_NOTE`) the same way. An entity that declares a property named exactly as
the column (`$sample_note`) keeps that name.

A property declared as `bool` gets the column value as a bool (`TINYINT(1)` 0/1), and a property
declared as a backed enum gets the case of the column value (`ValueError` for an unknown value).
Both are saved, and passed to queries, as their scalar values:
```php
enum Status: string
{
	case Draft = 'draft';
	case Published = 'published';
}

class ArticleEntity extends Entity
{
	public bool $commentsEnabled;
	public Status $status;
}

$this->model->find('status', Status::Published)->recordAll();
```

Use the entity in a model, so records are returned as `SampleEntity` objects:
```php
#[Table(SampleEntity::Table, SampleEntity::PrimaryKey, entity: SampleEntity::class)]
class Model
{
	/** @use Database<SampleEntity> */
	use Database;

	public function __construct(
		private readonly Connection $connection,
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
$entity->sampleNote = 'note';

$this->model->save($entity);
```

camelCase keys are converted to columns for arrays and form values too, also in `insert()` and `update()`:
```php
$this->model->save(['id' => 1, 'sampleNote' => 'note']);
```

An entity can also be inserted directly:
```php
$this->model->insert($entity)->execute();
```

## Transactions

`transaction()` commits after the callback and returns its result; when the callback throws, it rolls
back and rethrows. A transaction inside another one joins it:
```php
$this->model->transaction(function () use ($entity): void {
	$this->model->save($entity);
});
```

The transaction can also be controlled by hand:
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

---

## Optional add-ons

The package works without them. Set them up by hand as described below when the project needs them.

### PHPStan

The `Database` trait reads the connection of a repository, which PHPStan does not see in `vendor/`, so it reports
a private `$connection` as never read. Include the extension of this package in `phpstan.neon`:

```neon
includes:
	- vendor/drago-ex/database/extension.neon
```

### Entity Generation

For automatic entity generation, consider using the Drago Generator tool: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
