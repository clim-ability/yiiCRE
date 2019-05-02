<?php
require_once __DIR__ . '/../../db-config.php';

foreach(inqDbConnections() as $dbkey=>$dbdata) {
  if ('pgsql:cre' == $dbkey ) {	
    define('MIGRATION_CONFIG_DB_USER', $dbdata['user']);
    define('MIGRATION_CONFIG_DB_PASSWORD', $dbdata['password']);
    define('MIGRATION_CONFIG_DB_CONNECTION', $dbdata['pdo']);	
  }	
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => MIGRATION_CONFIG_DB_CONNECTION,
    'username' => MIGRATION_CONFIG_DB_USER,
    'password' => MIGRATION_CONFIG_DB_PASSWORD,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
