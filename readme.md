## Drago Database

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0a573beafc964543af530617a71467fd)](https://www.codacy.com/app/accgit/database?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/database&utm_campaign=badger)

Připojení k databázovému serveru.

## Požadavky

- PHP 7.0.8 nebo vyšší
- composer

## Instalace

```
composer require drago-ex/database
```

## Registrace rozšíření

```
extensions:

	# Připojení k databázovému serveru.
	dibi: Dibi\Bridges\Nette\DibiExtension22

# Nastavení databáze.
dibi:
	host:
	driver:
	username:
	password:
	database:
	lazy: true
	#substitutes:
		#prefix:
```

## Základní třída pro entity

V balíčku se nachází abstraktní třída pro entity, která nám může posloužit pro vložení nebo zjištění id záznamu.

## Třída Iterator

Abychom mohli vložit nebo upravit hodnoty z entit do databáze, musíme je předat metodám insert a update v podobě pole.
V tomto případě můžeme využít třídu Iterator, která nám ty hodnoty vráti jako pole.

## Příklad, jak vložit nebo aktualizovat záznam

```php
/**
 * @param mixed
 * @return void
 */
public function save(Entity $entity)
{
	if (!$entity->getId()) {
		return $this->db
			->insert('table', Database\Iterator::set($entity))
			->execute();
	} else {
		return $this->db
			->update('table', Database\Iterator::set($entity))
			->where('id = ?', $entity->getId())
			->execute();
	}
}
```

## Dokumentace
- [Dibi - smart database layer](https://github.com/dg/dibi)
