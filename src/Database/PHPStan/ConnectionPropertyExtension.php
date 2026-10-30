<?php declare(strict_types=1);

namespace Drago\Database\PHPStan;

use Drago\Database\Database;
use PHPStan\Reflection\ExtendedPropertyReflection;
use PHPStan\Rules\Properties\ReadWritePropertiesExtension;


/**
 * Tells PHPStan that the $connection of a class using the Database trait is read: the trait reads it,
 * but PHPStan does not see that from vendor/, so a private connection would be reported as never read.
 */
final class ConnectionPropertyExtension implements ReadWritePropertiesExtension
{
	public function isAlwaysRead(ExtendedPropertyReflection $property, string $propertyName): bool
	{
		return $propertyName === 'connection'
			&& isset($property->getDeclaringClass()->getTraits(recursive: true)[Database::class]);
	}


	public function isAlwaysWritten(ExtendedPropertyReflection $property, string $propertyName): bool
	{
		return false;
	}


	public function isInitialized(ExtendedPropertyReflection $property, string $propertyName): bool
	{
		return false;
	}
}
