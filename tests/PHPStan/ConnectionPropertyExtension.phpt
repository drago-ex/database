<?php declare(strict_types=1);

/**
 * Test: Drago\Database\PHPStan\ConnectionPropertyExtension
 *
 * PHPStan analyses only TestDatabase, whose private $connection is read by the Database trait alone. The trait
 * is outside the analysed paths, as in vendor/ of a project, so without the extension the property is reported.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function phpstan(bool $extension): string
{
	$root = dirname(__DIR__, 2);
	$config = TempDir . '/phpstan-' . ($extension ? 'with' : 'without') . '.neon';
	file_put_contents($config, implode("\n", [
		'includes:',
		$extension ? "\t- $root/extension.neon" : '',
		'parameters:',
		"\tlevel: 8",
		"\ttmpDir: " . TempDir . '/phpstan',
		"\tscanDirectories:",
		"\t\t- $root/tests",
	]));

	exec(implode(' ', [
		escapeshellarg(PHP_BINARY),
		escapeshellarg("$root/vendor/phpstan/phpstan/phpstan.phar"),
		'analyse --no-progress --error-format=raw',
		'--autoload-file=' . escapeshellarg("$root/vendor/autoload.php"),
		'-c ' . escapeshellarg($config),
		escapeshellarg("$root/tests/TestDatabase.php"),
		'2>&1',
	]), $output);

	return implode("\n", $output);
}


test('Without the extension the connection read by the trait is reported', function () {
	Assert::contains('TestDatabase::$connection is never read, only written.', phpstan(extension: false));
});


test('With the extension the connection is read', function () {
	Assert::notContains('$connection', phpstan(extension: true));
});
