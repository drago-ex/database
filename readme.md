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
			->findById($id)
			->setRowClass(UserEntity::class)
			->fetch();
	}


	/**
	 * Find user by email.
	 * @return array|UserEntity|null
	 * @throws Dibi\Exception
	 */
	public function findByEmail(string $email)
	{
		return $this
			->find(UserEntity::EMAIL, $email)
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
		return $this->saveRecord($entity, $id);
	}
}

```

## Use Find and update record

```php
// Find user by email.
$row = $this->userRepository->FindByEmail($email);

// Save update record.
$entity = $row;
$entity->setRealname('Change Username');
$this->userRepository->save($entity);
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
		$allRecords = $this->getRecords();
	}
}
```

You can find all available methods in the Repository.

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
