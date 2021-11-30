<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_090843_create_landscape_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('landscape_risk', [
            'id' => Schema::TYPE_PK,
			'landscape_id' => 'integer NOT NULL REFERENCES landscape(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('landscape_risk');
    }

}

