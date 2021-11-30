<?php

use yii\db\Schema;
use yii\db\Migration;

class m211129_160843_create_country_table extends Migration {

    public function safeUp() {
        $this->createTable('country', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'short' => Schema::TYPE_STRING . '(8) NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		$this->insert('country', [ 'name' => 'Germany', 'short' => 'de','visible' => true]);
		$this->insert('country', [ 'name' => 'France', 'short' => 'fr','visible' => true]);
		$this->insert('country', [ 'name' => 'Switzerland', 'short' => 'ch','visible' => true]);
    }

    public function safeDown() {
        $this->dropTable('country');
    }

}
