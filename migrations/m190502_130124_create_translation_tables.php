<?php

use yii\db\Schema;
use yii\db\Migration;

class m190502_130124_create_translation_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('language', [
            'id'                   => Schema::TYPE_PK,
            'language'             => Schema::TYPE_STRING . '(16) NOT NULL',
            'name'                 => Schema::TYPE_STRING . '(255)',
			'iso'                  => Schema::TYPE_STRING . '(16)',
            'visible'              => Schema::TYPE_BOOLEAN . ' DEFAULT false',
		    'requests'             => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
            'modified'             => 'timestamp with time zone DEFAULT now()',
        ]);
        $this->createIndex('language_unique_id', 'language', 'id', true);
		
        $this->insert('language', [ 'language' => 'en', 'name' => 'english',   'iso' => 'eng', 'visible' => true ]); 		
        $this->insert('language', [ 'language' => 'de', 'name' => 'deutsch',   'iso' => 'deu', 'visible' => true ]); 		
        $this->insert('language', [ 'language' => 'fr', 'name' => 'francaise', 'iso' => 'fra', 'visible' => true ]); 

        $this->createTable('table_message_source', [
            'id'                   => Schema::TYPE_PK,
            'category'             => Schema::TYPE_STRING . '(32)',
            'message'              => Schema::TYPE_TEXT,
        ]);
        $this->createIndex('table_message_source_id', 'table_message_source', 'id', true);

        $this->createTable('table_message_suggestion', [
			'id'                   => Schema::TYPE_INTEGER . ' NOT NULL REFERENCES table_message_source(id) ON DELETE CASCADE',
            'language'             => Schema::TYPE_STRING . '(16)',
            'translation'          => Schema::TYPE_TEXT,
			'created_by'           => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
			'modified'             => 'timestamp with time zone DEFAULT now()',
        ]);
        $this->createIndex('table_suggestion_id_lang_translation_user', 'table_message_suggestion',
                            ['id', 'language', 'translation', 'created_by'], true);	

        $this->createTable('table_message_target', [
			'id'                   => Schema::TYPE_INTEGER . ' NOT NULL REFERENCES table_message_source(id) ON DELETE CASCADE',
            'language'             => Schema::TYPE_STRING . '(16)',
            'translation'          => Schema::TYPE_TEXT,
            'voting'               => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',			
			'created_by'           => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
			'modified'             => 'timestamp with time zone DEFAULT now()',
        ]);
        $this->createIndex('table_translation_id_lang', 'table_message_target',
                            ['id', 'language'], true);	
							
    }

    public function safeDown()
    {
		$this->dropTable('table_message_target');
		$this->dropTable('table_message_suggestion');
		$this->dropTable('table_message_source');
        $this->dropTable('language');
		
    }
    
}
