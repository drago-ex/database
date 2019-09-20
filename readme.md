<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/50230834?s=400&u=3551f498f489486fb0ee563171d5fb2d43892a17&v=4" width="100" alt="logo">
</p>

<h3 align="center">Compostrap</h3>
<p align="center">Simple and fast components build on Bootstrap 4</p>

## Info

Connect and work with the database.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/database/master/license.md)

## Requirements

- PHP 7.1 or higher
- composer

## Installation

```
composer require drago-ex/database
```

## Example entity

```php
class UserEntity extends Drago\Database\Entity
{
	const TABLE   = 'users';
	const USER_ID = 'userId';
	const EMAIL   = 'email';

	/** @var int */
	public $userId;

	/** @var string */
	public $email;


	public function setUserId(int $userId)
	{
		$this['userId'] = $userId;
	}


	public function getUserId(): int
	{
		return $this->userId;
	}


	public function setEmail(string $email)
	{
		$this['email'] = $email;
	}


	public function getEmail(): string
	{
		return $this->email;
	}
}

```

## Example repository

```php
class UserRepository extends Connection
{
	use Repository;

	/** @var string table name */
	private $table = UserEntity::TABLE;

	/** @var int primary id */
	private $primaryId = UserEntity::USER_ID;


	/**
	 * Find user by id.
	 * @return array|UserEntity|null
	 * @throws Dibi\Exception
	 */
	public function find(int $id)
	{
		return $this
			->discoverId($id)
			->setRowClass(UserEntity::class)
			->fetch();
	}


	/**
	 * Find user by email.
	 * @return array|UserEntity|null
	 * @throws Dibi\Exception
	 */
	public function findBy(string $email)
	{
		return $this
			->discover(UserEntity::EMAIL, $email)
			->setRowClass(UserEntity::class)
			->fetch();
	}


	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function save(UserEntity $entity)
	{
		$id = $entity->getUserId();
		return $this->add($entity, $id);
	}
}

```

If you want to use a very simple query without an entity, we can 
use the Repository trait in the Presenter.

## Let's give an example

```php
class HomePresenter extends Presenter
{
	use Repository;

	/** @var string table name */
	private $table = 'users';

	/** @var int primary id */
	private $primaryId = 'userId';


	protected function beforeRender(): void
	{
		// Get all records form table.
		$allRecords = $this->all();
	}
}
```

You can find all available methods in the Repository.

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
