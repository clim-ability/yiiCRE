<?php

use yii\db\Schema;
use yii\db\Migration;

class m211130_113843_create_country_adaption_table extends Migration {

    public function safeUp() {
        $this->createTable('country_adaption', [
            'id' => Schema::TYPE_PK,
			'country_id' => 'integer NOT NULL REFERENCES country(id) ON DELETE CASCADE',
            'adaption_id' => 'integer NOT NULL REFERENCES adaption(id) ON DELETE CASCADE',			
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
    }

    public function safeDown() {
        $this->dropTable('country_adaption');
    }

}

