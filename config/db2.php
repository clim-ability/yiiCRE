<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => LOCAL_CONFIG_DB2_CONNECTION,
    'username' => LOCAL_CONFIG_DB2_USER,
    'password' => LOCAL_CONFIG_DB2_PASSWORD,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
