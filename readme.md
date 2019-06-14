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


	/**
	 * Save record.
	 * @return Dibi\Result|int|null
	 * @throws Dibi\Exception
	 */
	public function saveUser(UserEntity $entity)
	{
		$id = $entity->getUserId();
		$query = $id
			? $this->saveById($entity->getModify(), $id)
			: $this->save($entity->getModify());

		return $query->execute();
	}
}

```

## Use Find and update record

```php
// Find user.
$row = $this->userRepository->FindUserByEmail($email);

// Save update record.
$entity = $row;
$entity->setRealname('Change Username');
$this->userRepository->saveUser($entity);
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
