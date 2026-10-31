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

## Základní příklad repository

Repository si v konstruktoru vytvoří `Table` a pracuje přes ni. Předá jí název tabulky, primární klíč a třídu
entity, jako kterou se záznamy načítají (viz [Entity](#entity)):

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

Bez entity se záznamy vracejí jako objekty `Dibi\Row`. Pohled bez primárního klíče nemůže použít `get()`
a `save()`:

```php
$this->table = new Table($db, 'table_name', 'id');
```

## Běžné dotazy

Metody pro dotazy vracejí sestavovač dotazů (`ExtraFluent`), takže dotaz lze ještě doplnit
(`where()`, `orderBy()`…). Výsledek načtěte přes `record()` (select bez limitu načte jeden řádek)
nebo `recordAll()`, zápisový dotaz spusťte přes `execute()`. Zahozený sestavovač dotazu, například
`delete()` bez `execute()`, nahlásí PHP 8.5 i PHPStan (`#[\NoDiscard]`):

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

Záznam uložíte jako pole. Když pole obsahuje primární klíč s hodnotou větší než nula,
záznam se aktualizuje, jinak se vloží nový:
```php
$this->table->save(['column' => 'value']);
$this->table->save(['id' => 1, 'column' => 'value']);
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

$this->table->find('status', Status::Published)->recordAll();
```

`Table` entity vrací své záznamy jako objekty `SampleEntity`:
```php
$row = $this->table->get(1)->record();
echo $row->sample;
```

## Ukládání entit

Entity se ukládají stejně jako pole (aktualizují se, když je nastavený primární klíč):
```php
$entity = new SampleEntity;
$entity->id = 1;
$entity->sample = 'sample';
$entity->sampleNote = 'note';

$this->table->save($entity);
```

Klíče v camelCase se převádějí na sloupce i u polí a hodnot z formulářů, také v `insert()` a `update()`:
```php
$this->table->save(['id' => 1, 'sampleNote' => 'note']);
```

Entitu lze vložit i přímo:
```php
$this->table->insert($entity)->execute();
```

A upravit, s podmínkou za ní:
```php
$this->table->update($entity)->where('id = ?', $entity->id)->execute();
```

## Transakce

`transaction()` po callbacku potvrdí transakci a vrátí jeho výsledek; když callback vyhodí výjimku,
transakci vrátí zpět a výjimku vyhodí dál. Transakce uvnitř jiné transakce se k ní připojí:
```php
$this->table->transaction(function () use ($entity): void {
	$this->table->save($entity);
});
```

Transakci lze řídit i ručně:
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

## Volitelné doplňky

Balíček funguje i bez nich. Pokud je projekt potřebuje, nastavte je ručně podle popisu níže.

### Generování entit

Pro automatické generování entit můžete použít nástroj Drago Generator: [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
