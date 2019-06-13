## Drago Database

Connect and work with the database.

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
	public function FindUser(int $id)
	{
		return $this
			->findById($id)->execute()
			->setRowClass(UserEntity::class)
			->fetch();
	}


	/**
	 * Find user by email.
	 * @return array|UserEntity|null
	 * @throws Dibi\Exception
	 */
	public function FindUserByEmail(string $email)
	{
		return $this
			->find(UserEntity::EMAIL, $email)->execute()
			->setRowClass(UserEntity::class)
			->fetch();
	}
}

```

The repository can also be used without entities. We can omit this UserRepository
and use the Repository Trait in Presenter.

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
