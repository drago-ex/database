<?php declare(strict_types=1);

/** An entity whose properties are named as the columns. */
class TestEntitySnake extends Drago\Database\Entity
{
	public ?int $id = null;
	public ?string $sample_note = null;
}
