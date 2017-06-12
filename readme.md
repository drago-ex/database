## Drago Database

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5b1b3033f0e541c4abf4d19cbdf8a028)](https://www.codacy.com/app/zdenek.papucik/database?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=drago-ex/database&amp;utm_campaign=Badge_Grade)

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

## Instruction

For models (repository) use extends to the class and queries do so:

```php
$this->db
```

## Documentation
- [Dibi - smart database layer](https://github.com/dg/dibi)
