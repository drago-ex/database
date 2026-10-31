<?php declare(strict_types=1);

class TestEntityTyped extends Drago\Database\Entity
{
	public const string Table = 'typed';
	public const string PrimaryKey = 'id';

	public ?int $id = null;
	public bool $active;
	public ?bool $inMenu = null;
	public TestStatus $status;
	public ?TestPriority $priority = null;
}
