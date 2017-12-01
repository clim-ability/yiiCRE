<?php

use yii\db\Schema;
use yii\db\Migration;

class m171201_100843_create_epoch_table extends Migration {

    public function safeUp() {
        $this->createTable('epoch', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'year_begin' => Schema::TYPE_INTEGER . ' NOT NULL',
            'year_end' => Schema::TYPE_INTEGER . ' NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('epoch');
    }

}
