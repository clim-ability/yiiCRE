<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => LOCAL_CONFIG_DB_CONNECTION,
    'username' => LOCAL_CONFIG_DB_USER,
    'password' => LOCAL_CONFIG_DB_PASSWORD,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
