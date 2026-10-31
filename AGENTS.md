# Agent Guide

Use this file as package-specific guidance when working in this repository. Keep changes aligned with the existing public API, project conventions, and supported PHP range.

## Package Overview
- **Package**: `drago-ex/database`
- **Type**: library
- **Purpose**: Connecting to database for Nette Framework
- **PHP**: >=8.3 <9
- **Runtime dependencies**: dibi/dibi (^5.0.2), drago-ex/utils (^2.1.1)
- **License**: MIT

## Repository Layout
- `src/`: package source code.
- `tests/`: automated tests.
- `.github/`: CI workflows; keep their PHP versions and commands consistent with Composer and the package scripts.
- `composer.json`: package metadata, dependency constraints, autoloading, and available Composer scripts.

## Key Parts
- `Drago\Database\Database` trait reads the table, primary key and entity class from the `Drago\Attr\Table` attribute and needs a `$connection` property.
- `ExtraFluent` adds no clause methods: Dibi's `Fluent::__call()` runs every clause (and so keeps `Fluent::Remove`), the class-level `@method $this ...` tags only give the clauses this class's type so chains keep `record()`/`recordAll()`; add a tag for a new Dibi clause, not a method. `AttributeDetection` caches the `Table` attribute itself.
- `ExtraFluent::record()` and `recordAll()` return the entity class when one is set, otherwise Dibi rows; never pass `null` to `Result::setRowClass()` (Dibi would return arrays). `record()` runs a SELECT without a limit as a clone with `limit(1)` (like Dibi's `Fluent::fetch()`, whose private `query()` would bypass the `execute()` that the tests replace), so the query itself keeps no limit.
- `Entity` properties are camelCase names of snake_case columns: the constructor (Dibi passes every fetched row there) converts keys with `CaseConverter::camelCase()` from drago-ex/utils, `toColumns()` and the static `toColumnNames()` convert back with `snakeCase()`; keys without a lowercase letter stay as they are. A property declared exactly as the column (`$sample_note`, entities generated with `camelCase: false`) keeps the column name. `EntityOracle` extends `Entity` and only lowercases the uppercase columns on the way in and uppercases them in `toColumns()`.
- `Entity` converts column values of properties declared as `bool` or as a `BackedEnum` (`from()`, so an unknown value throws `ValueError`); the conversions are detected by reflection once per class and cached in a static property. The column => property names are cached per class too (`$propertyNames`). Nothing converts them back: Dibi's `Translator` writes bools and `BackedEnum` values (also as `?` arguments) itself. Other types (dates, ints) come from Dibi's own type detection.
- The trait methods returning `ExtraFluent` (`command`, `read`, `find`, `get`, `delete`, `insert`, `update`) have `#[\NoDiscard]`: a builder thrown away without `execute()`/`record()` gives an `E_USER_WARNING` on PHP 8.5 and the PHPStan error `method.resultDiscarded` (PHPStan 2.3); they return the builder so the query can be extended (`update()` gets its `where()` afterwards, `setFlag('IGNORE')`, `test()`).
- `transaction(callable)` delegates to Dibi's `Connection::transaction()` (nesting by depth, rollback and rethrow on any `Throwable`); the manual `beginTransaction()`/`commit()`/`rollBack()` stay. `count(...$cond)` takes the arguments of `where()` (an array `[column => value]` included), without them it counts the whole table. `insert()` accepts an entity (`toColumns()`) or an array.
- `save()` updates when the primary key value is greater than zero, otherwise inserts; an entity is saved through `toColumns()` (uppercase primary key for `EntityOracle`), arrays and form values through `Entity::toColumnNames()`, which `insert()` and `update()` apply to their arrays too. A column whose part is only a digit (`address_2`) does not survive the conversion.
- `extension.neon` registers `Drago\Database\PHPStan\ConnectionPropertyExtension` (PHPStan ReadWritePropertiesExtension): the `$connection` of a class using the `Database` trait counts as read, because PHPStan does not analyse the trait in `vendor/` in the context of the class and reports a `private readonly Connection $connection` as never read (user, 2026-10-30: repositories use `private`). Projects and packages include it in `phpstan.neon`; their DressCode CI job also needs `drago-ex/database` in `temp/dresscode` (DressCode builds PHPStan from its own vendor). This repository does not include it, as PHPStan sees the trait here; `tests/PHPStan/ConnectionPropertyExtension.phpt` runs PHPStan on `TestDatabase` with and without it.
- `tests/Database/Database.phpt` needs MySQL (CI provides it and fails there instead of skipping); the other tests run without a database.

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include nette/bootstrap (^3.2), nette/tester (^2.5), tracy/tracy (^2.10), phpstan/phpstan-nette (^2.0).

## Essential Commands

```bash
composer tester                 # tester tests -s
composer phpstan                # phpstan analyse
```

## Conventions & Rules

- Preserve backward compatibility unless the task explicitly permits a breaking change. Check callers and public signatures before changing behavior.
- Follow the existing PHP style: strict types, explicit parameter and return types, tabs for indentation, and single-quoted strings where practical. Match nearby code when a convention is unclear.
- Keep package-specific configuration, resources, and migrations in their established directories. Do not edit generated or consuming-project files without confirming they are part of this repository’s intended output.
- Add or update focused tests for behavioral changes when this package has a runnable test setup. Do not invent test commands; use only the scripts and configuration present here.
- Update README examples and Composer metadata when a public API or supported requirement changes.
