<?php

use yii\db\Schema;
use yii\db\Migration;

class m211129_150843_create_landscape_table extends Migration {

    public function safeUp() {
        $this->createTable('landscape', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'elevation_min' => Schema::TYPE_DOUBLE . ' DEFAULT -999.0',
			'elevation_max' => Schema::TYPE_DOUBLE . ' DEFAULT 9999.0',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		$this->insert('landscape', [ 'name' => 'Tieflagen', 'elevation_min' => -999.0, 'elevation_max' => 300.0,'visible' => true]);
		$this->insert('landscape', [ 'name' => 'mittlere Lagen', 'elevation_min' => 300.0, 'elevation_max' => 550.0,'visible' => true]);
		$this->insert('landscape', [ 'name' => 'Hochlagen', 'elevation_min' => 550.0, 'elevation_max' => 9999.0,'visible' => true]);
    }

    public function safeDown() {
        $this->dropTable('landscape');
    }

}
