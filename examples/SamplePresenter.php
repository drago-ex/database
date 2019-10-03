<?php

declare(strict_types = 1);

namespace Examples;

use Drago\Database\Repository;
use Nette\Application\UI\Presenter;


class SamplePresenter extends Presenter
{
	use Repository;

	/** @var string */
	private $table = 'table';

	/** @var int */
	private $primaryId = 'sampleId';


	protected function beforeRender(): void
	{
		// Get all records form table.
		$records = $this->all();
		$this->template->records = $records;
	}
}
