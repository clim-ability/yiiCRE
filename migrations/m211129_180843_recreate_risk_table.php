<?php

use yii\db\Schema;
use yii\db\Migration;

class m211129_180843_recreate_risk_table extends Migration {

    public function safeUp() {
		$this->dropTable('danger_risk');
		$this->dropTable('sector_risk');
        $this->dropTable('risk');
        $this->createTable('risk', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
			'description' => Schema::TYPE_STRING . '(4096) NOT NULL',
			'details' => Schema::TYPE_STRING . '(4096) NOT NULL',
			'negative'  => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);		
    }

    public function safeDown() {
		$this->dropTable('risk');
        $this->createTable('risk', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'zone_id' => 'integer NOT NULL REFERENCES zone(id) ON DELETE CASCADE',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);	
		$this->createTable('danger_risk', [
            'id' => Schema::TYPE_PK,
			'danger_id' => 'integer NOT NULL REFERENCES danger(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'impact' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		$this->createTable('sector_risk', [
            'id' => Schema::TYPE_PK,
			'sector_id' => 'integer NOT NULL REFERENCES sector(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'factor' => Schema::TYPE_DOUBLE . ' DEFAULT 0.5',
			'offset' => Schema::TYPE_DOUBLE . ' DEFAULT 0.5',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

}
