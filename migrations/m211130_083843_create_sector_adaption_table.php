<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_083843_create_sector_adaption_table extends Migration {

    public function safeUp() {
        $this->createTable('sector_adaption', [
            'id' => Schema::TYPE_PK,
			'sector_id' => 'integer NOT NULL REFERENCES sector(id) ON DELETE CASCADE',
            'adaption_id' => 'integer NOT NULL REFERENCES adaption(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('sector_adaption');
    }

}

