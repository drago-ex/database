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

## Basic Repository Example

A repository creates a `Table` in its constructor and works through it. It gets the table name, the primary
key and the entity class the records are fetched as (see [Using Entities](#using-entities)):

```php
use Dibi\Connection;
use Drago\Database\Table;

readonly class SampleRepository
{
	/** @var Table<SampleEntity> */
	public Table $table;

	public function __construct(Connection $db)
	{
		$this->table = new Table($db, SampleEntity::Table, SampleEntity::PrimaryKey, SampleEntity::class);
	}
}

$row = $this->sampleRepository->table->get(1)->record();
```

Without an entity, records are returned as `Dibi\Row` objects. A view without a primary key cannot use `get()`
and `save()`:

```php
$this->table = new Table($db, 'table_name', 'id');
```

## Common Queries

The query methods return a query builder (`ExtraFluent`), so the query can still be extended
(`where()`, `orderBy()`…). Fetch the result with `record()` (a select without a limit reads one row)
or `recordAll()`, or run a write query with `execute()`. A query builder that is thrown away, such as
`delete()` without `execute()`, is reported by PHP 8.5 and PHPStan (`#[\NoDiscard]`):

```php
// All records
$rows = $this->table->read('*')->recordAll();

// Records by column value, or by more columns
$rows = $this->table->find('column', 'value')->recordAll();
$rows = $this->table->find(['column' => 'value', 'other' => 1])->recordAll();

// One page of records
$rows = $this->table->read('*')->orderBy('id')->limit(10)->offset(20)->recordAll();

// One record by the primary key, or null
$row = $this->table->get(1)->record();

// Number of records, all or matching a condition
$count = $this->table->count();
$count = $this->table->count(['column' => 'value']);

// Delete records by column value, or by more columns (an empty array is refused)
$this->table->delete('column', 'value')->execute();
$this->table->delete(['column' => 'value', 'other' => 1])->execute();
```

Save a record as an array. When the array contains the primary key with a value greater than zero,
the record is updated, otherwise a new record is inserted:
```php
$this->table->save(['column' => 'value']);
$this->table->save(['id' => 1, 'column' => 'value']);
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

$this->table->find('status', Status::Published)->recordAll();
```

A `Table` of the entity returns its records as `SampleEntity` objects:
```php
$row = $this->table->get(1)->record();
echo $row->sample;
```

## Save Entity Records

Entities are saved the same way as arrays (updated when the primary key is set):
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';
$entity->sampleNote = 'note';

$this->table->save($entity);
```

camelCase keys are converted to columns for arrays and form values too, also in `insert()` and `update()`:
```php
$this->table->save(['id' => 1, 'sampleNote' => 'note']);
```

An entity can also be inserted directly:
```php
$this->table->insert($entity)->execute();
```

And updated, with the condition after it:
```php
$this->table->update($entity)->where('id = ?', $entity->id)->execute();
```

## Transactions

`transaction()` commits after the callback and returns its result; when the callback throws, it rolls
back and rethrows. A transaction inside another one joins it:
```php
$this->table->transaction(function () use ($entity): void {
	$this->table->save($entity);
});
```

The transaction can also be controlled by hand:
```php
$this->table->beginTransaction();
try {
	$this->table->save($entity);
	$this->table->commit();
} catch (Throwable $e) {
	$this->table->rollBack();
	throw $e;
}
```

---

## Optional add-ons

The package works without them. Set them up by hand as described below when the project needs them.

### Entity Generation

For automatic entity generation, consider using the Drago Generator tool: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
