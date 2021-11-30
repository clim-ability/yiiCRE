<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_110843_create_country_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('country_risk', [
            'id' => Schema::TYPE_PK,
			'country_id' => 'integer NOT NULL REFERENCES country(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('country_risk');
    }

}

