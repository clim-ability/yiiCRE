<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_073843_create_danger_adaption_table extends Migration {

    public function safeUp() {
        $this->createTable('danger_adaption', [
            'id' => Schema::TYPE_PK,
			'danger_id' => 'integer NOT NULL REFERENCES danger(id) ON DELETE CASCADE',
            'adaption_id' => 'integer NOT NULL REFERENCES adaption(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('danger_adaption');
    }

}

