<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = LOCAL_CONFIG_DB_TEST_CONNECTION;

return $db;
