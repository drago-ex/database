<?php declare(strict_types=1);

/**
 * Test: Drago\Attr\AttributeDetection
 */

use Drago\Attr\AttributeDetection;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class ModelWithoutTable
{
	use AttributeDetection;
}


#[Table('items')]
class ModelWithoutPrimaryKey
{
	use AttributeDetection;
}


#[Table('items', 'id', entity: stdClass::class)]
class ModelWithInvalidEntity
{
	use AttributeDetection;
}


#[Table('items', 'id', entity: TestEntity::class)]
class ModelWithEntity
{
	use AttributeDetection;
}


test('Table name, primary key and entity are read from the Table attribute', function () {
	$model = new ModelWithEntity;
	Assert::same('items', $model->getTableName());
	Assert::same('id', $model->getPrimaryKey());
	Assert::same(TestEntity::class, $model->getEntityClassName());
});


test('A model without the Table attribute is reported', function () {
	Assert::exception(
		fn() => (new ModelWithoutTable)->getTableName(),
		AttributeDetectionException::class,
		'In the model ModelWithoutTable you do not have a table name in the Table attribute.',
	);
});


test('A missing primary key is reported only when it is needed', function () {
	$model = new ModelWithoutPrimaryKey;
	Assert::same('items', $model->getTableName());
	Assert::null($model->getEntityClassName());
	Assert::exception(
		fn() => $model->getPrimaryKey(),
		AttributeDetectionException::class,
		'In the model ModelWithoutPrimaryKey you do not have a primary key in the Table attribute.',
	);
});


test('An entity class that is not a Dibi row is rejected', function () {
	Assert::exception(
		fn() => (new ModelWithInvalidEntity)->getTableName(),
		AttributeDetectionException::class,
		'Class "stdClass" in the Table attribute of ModelWithInvalidEntity is not an instance of Dibi\Row.',
	);
});
