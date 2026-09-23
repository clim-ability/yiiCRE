<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_103843_create_zone_adaption_table extends Migration {

    public function safeUp() {
        $this->createTable('zone_adaption', [
            'id' => Schema::TYPE_PK,
			'zone_id' => 'integer NOT NULL REFERENCES zone(id) ON DELETE CASCADE',
            'adaption_id' => 'integer NOT NULL REFERENCES adaption(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('zone_adaption');
    }

}

