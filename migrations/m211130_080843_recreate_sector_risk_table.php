<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_080843_recreate_sector_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('sector_risk', [
            'id' => Schema::TYPE_PK,
			'sector_id' => 'integer NOT NULL REFERENCES sector(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('sector_risk');
    }

}

