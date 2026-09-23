<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_100843_create_zone_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('zone_risk', [
            'id' => Schema::TYPE_PK,
			'zone_id' => 'integer NOT NULL REFERENCES zone(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('zone_risk');
    }

}

