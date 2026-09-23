<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_070843_recreate_danger_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('danger_risk', [
            'id' => Schema::TYPE_PK,
			'danger_id' => 'integer NOT NULL REFERENCES danger(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('danger_risk');
    }

}

