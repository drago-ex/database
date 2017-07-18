## Drago Database

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0a573beafc964543af530617a71467fd)](https://www.codacy.com/app/accgit/database?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/database&utm_campaign=badger)

Connect to database with dibi.

## Settings database server

```
extensions:

	# Connect to database with dibi.
	dibi: Dibi\Bridges\Nette\DibiExtension22

# Credentials to the database.
dibi:
	host:
	driver:
	username:
	password:
	database:
	lazy: TRUE
	#substitutes:
		#prefix:
```

## Usage

```php
$this->db
```

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
