<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago Extension</h3>
<p align="center">Simple packages built on Nette Framework</p>

## Drago Database
Connecting to database.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fdatabase.svg)](https://badge.fury.io/ph/drago-ex%2Fdatabase)
[![Build Status](https://travis-ci.org/drago-ex/database.svg?branch=master)](https://travis-ci.org/drago-ex/database)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/database/badge)](https://www.codefactor.io/repository/github/drago-ex/database)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/database/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/database?branch=master)

## Requirements
- PHP 7.4 or higher
- composer

## Installation
```
composer require drago-ex/database
```

## Use
```
class Model extends Drago\Database\Connect
{
	use Drago\Database\Repository;
	
	public string $table = 'table';
	public string $columnId = 'id';
}
```

## Creating queries
```
$this->db;
```

## Basic queries in the Repository

Returns all records.
```
$this->repository->all();
```

Search for a record by column name.
```
$this->repository->discover('email', 'email@email.com');
```

Search for a record by id.
```
$this->repository->discoverId(1);
```

Delete a record from the database.
```
$this->repository->eraseId(1);
```

Save record (the update will be performed if a column with id is added).
```
$this->repository->put(['column' => 'record']);
```
