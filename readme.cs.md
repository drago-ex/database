# Drago Database

Jednoduché pomocníky pro práci s databází postavené na Dibi.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/database/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/database/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- dibi
- Composer

## Instalace

```
composer require drago-ex/database
```

## Další informace

- [Dibi - chytrá databázová vrstva pro PHP](https://github.com/dg/dibi)

## Základní příklad modelu

Model používá trait `Database` a svou tabulku popisuje atributem `Table`
(název tabulky a primární klíč). Trait čte připojení z vlastnosti `$connection`:

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

## Běžné dotazy

Metody pro dotazy vracejí sestavovač dotazů (`ExtraFluent`). Výsledek načtěte přes `record()` nebo
`recordAll()`, zápisový dotaz spusťte přes `execute()`:

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

Bez třídy entity se záznamy vracejí jako objekty `Dibi\Row`.

Záznam uložíte jako pole. Když pole obsahuje primární klíč s hodnotou větší než nula,
záznam se aktualizuje, jinak se vloží nový:
```php
$this->model->save(['column' => 'value']);
$this->model->save(['id' => 1, 'column' => 'value']);
```

## Entity

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

Vlastnosti jsou názvy sloupců v camelCase: sloupec `sample_note` naplní `$sampleNote` a při ukládání
se hodnota zapíše zpět do `sample_note`. Pro Oracle dědí entita z `EntityOracle`, která stejně převádí
sloupce velkými písmeny (`SAMPLE_NOTE`). Entita, která deklaruje vlastnost pojmenovanou přesně
jako sloupec (`$sample_note`), si tento název ponechá.

Použijte entitu v modelu, aby se záznamy vracely jako objekty `SampleEntity`:
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

## Ukládání entit

Entity se ukládají stejně jako pole (aktualizují se, když je nastavený primární klíč):
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';
$entity->sampleNote = 'note';

$this->model->save($entity);
```

Klíče v camelCase se převádějí na sloupce i u polí a hodnot z formulářů, také v `insert()` a `update()`:
```php
$this->model->save(['id' => 1, 'sampleNote' => 'note']);
```

## Transakce

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

## Generování entit

Pro automatické generování entit můžete použít nástroj Drago Generator: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
