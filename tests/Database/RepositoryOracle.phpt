<?php

declare(strict_types = 1);

namespace Test\Database;

use Examples\SampleOracleEntity;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../../examples/SampleEntity.php';
require __DIR__ . '/../../examples/SampleRepository.php';


test(function () {
	$repository = new SampleOracleEntity(connectOracle());
	Assert::same(1, $repository->getSampleId());
});
