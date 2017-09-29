## Drago Database

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0a573beafc964543af530617a71467fd)](https://www.codacy.com/app/accgit/database?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/database&utm_campaign=badger)

Připojení k databázovému serveru.

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
	lazy: TRUE
	#substitutes:
		#prefix:
```

## Základní třída pro entity

V balíčku se nachází abstraktní třída pro entity, která má připravené metody pro vložení nebo zjištění id záznamu.

## Třída Iterator

Abychom mohli vložit hodnoty z entit do databáze, musíme je nejprve projít a předat jako pole,
k tomu nám dobře poslouží třída Iterator.

## Příklad vložení nebo aktualizování záznamů

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
