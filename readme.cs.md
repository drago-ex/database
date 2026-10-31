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
		private readonly Connection $connection,
	) {
	}
}
```

## Běžné dotazy

Metody pro dotazy vracejí sestavovač dotazů (`ExtraFluent`), takže dotaz lze ještě doplnit
(`where()`, `orderBy()`…). Výsledek načtěte přes `record()` (select bez limitu načte jeden řádek)
nebo `recordAll()`, zápisový dotaz spusťte přes `execute()`. Zahozený sestavovač dotazu, například
`delete()` bez `execute()`, nahlásí PHP 8.5 i PHPStan (`#[\NoDiscard]`):

```php
// All records
$rows = $this->model->read('*')->recordAll();

// Records by column value, or by more columns
$rows = $this->model->find('column', 'value')->recordAll();
$rows = $this->model->find(['column' => 'value', 'other' => 1])->recordAll();

// One page of records
$rows = $this->model->read('*')->orderBy('id')->limit(10)->offset(20)->recordAll();

// One record by the primary key, or null
$row = $this->model->get(1)->record();

// Number of records, all or matching a condition
$count = $this->model->count();
$count = $this->model->count(['column' => 'value']);

// Delete records by column value, or by more columns (an empty array is refused)
$this->model->delete('column', 'value')->execute();
$this->model->delete(['column' => 'value', 'other' => 1])->execute();
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

Vlastnost deklarovaná jako `bool` dostane hodnotu sloupce jako bool (`TINYINT(1)` 0/1) a vlastnost
deklarovaná jako backed enum dostane případ odpovídající hodnotě sloupce (`ValueError` pro neznámou
hodnotu). Obě se ukládají i předávají do dotazů jako své skalární hodnoty:
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

Použijte entitu v modelu, aby se záznamy vracely jako objekty `SampleEntity`:
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

Entitu lze vložit i přímo:
```php
$this->model->insert($entity)->execute();
```

A upravit, s podmínkou za ní:
```php
$this->model->update($entity)->where('id = ?', $entity->id)->execute();
```

## Transakce

`transaction()` po callbacku potvrdí transakci a vrátí jeho výsledek; když callback vyhodí výjimku,
transakci vrátí zpět a výjimku vyhodí dál. Transakce uvnitř jiné transakce se k ní připojí:
```php
$this->model->transaction(function () use ($entity): void {
	$this->model->save($entity);
});
```

Transakci lze řídit i ručně:
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

## Volitelné doplňky

Balíček funguje i bez nich. Pokud je projekt potřebuje, nastavte je ručně podle popisu níže.

### PHPStan

Traita `Database` čte připojení repository, což PHPStan ve `vendor/` nevidí, a soukromé `$connection` proto
hlásí jako nikdy nečtené. S `phpstan/extension-installer` se rozšíření tohoto balíčku zapne samo;
bez něj ho zapněte v `phpstan.neon`:

```neon
includes:
	- vendor/drago-ex/database/extension.neon
```

### Generování entit

Pro automatické generování entit můžete použít nástroj Drago Generator: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
