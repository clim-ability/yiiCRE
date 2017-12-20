<?php

use yii\db\Schema;
use yii\db\Migration;

class m171220_120843_create_layer_table extends Migration {

    public function safeUp() {
        $this->createTable('layer', [
            'id' => Schema::TYPE_PK,
            'hazard_id' => 'integer NOT NULL REFERENCES hazard(id) ON DELETE CASCADE',
            'epoch_id' => 'integer NOT NULL REFERENCES epoch(id) ON DELETE CASCADE',
            'scenario_id' => 'integer NOT NULL REFERENCES scenario(id) ON DELETE CASCADE',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(4095)',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'variable' => Schema::TYPE_STRING . '(255) NOT NULL',
            'layer' => Schema::TYPE_STRING . '(255) NOT NULL',
            'srid' => Schema::TYPE_INTEGER . ' DEFAULT 4326',
            'relative' => 'integer REFERENCES layer(id)',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
        $this->execute('ALTER TABLE layer ADD UNIQUE ("hazard_id" ,"epoch_id", "scenario_id")');
    }

    public function safeDown() {
        $this->dropTable('layer');
    }

}
