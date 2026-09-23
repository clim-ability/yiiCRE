<?php

use yii\db\Schema;
use yii\db\Migration;

class m171201_130843_create_parameter_table extends Migration {

    public function safeUp() {
        $this->createTable('parameter', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(4095)',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('parameter');
    }

}
