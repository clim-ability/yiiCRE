<?php
return [
        'translations' => [
            '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'tlh', // Developer language
                    'sourceMessageTable' => 'table_message_source',
                    'messageTable' => 'table_message_target',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,  
                    'on missingTranslation' => ['app\modules\translation\models\Language', 'missingTranslation']
                
                ],
        ],
];
?>