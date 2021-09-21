<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago Extension</h3>
<p align="center">Extension for Nette Framework</p>

## Drago Database
Connecting to database.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Tests](https://github.com/drago-ex/database/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/database/actions/workflows/tests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/database/badge)](https://www.codefactor.io/repository/github/drago-ex/database)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/database/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/database?branch=master)

## Technology
- PHP 8.0 or higher
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
class Model extends Drago\Database\Connect {}
```

## Creating queries
```php
$this->db;
```

## Basic queries in the Repository

Returns all records.
```php
$this->model->all();
```

Search for a record by column name.
```php
$this->model->discover('email', 'email@email.com');
```

Search for a record by id.
```php
$this->model->get(1);
```

Delete a record from the database.
```php
$this->model->erase(1);
```

Save record (the update will be performed if a column with id is added).
```php
$this->model->put(['column' => 'record']);
```

## Tips
You can also use entities and have them generated. [https://github.com/drago-ex/generator](https://github.com/drago-ex/generator)
