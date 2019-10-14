<?php
declare(strict_types = 1);

require __DIR__ . '/../bootstrap.php';

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID=xe)))";
$connect = ocilogon('travis', 'travis', $db) == true ? true : ocierror();
Tester\Assert::true($connect);

