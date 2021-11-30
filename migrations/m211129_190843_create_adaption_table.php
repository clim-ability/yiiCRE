<?php

use yii\db\Schema;
use yii\db\Migration;

class m211129_190843_create_adaption_table extends Migration {

    public function safeUp() {
        $this->createTable('adaption', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
			'description' => Schema::TYPE_STRING . '(4096) NOT NULL',
			'details' => Schema::TYPE_STRING . '(4096) NOT NULL',
			//'negative'  => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);	
    }

    public function safeDown() {
	    $this->dropTable('adaption');
    }

}
