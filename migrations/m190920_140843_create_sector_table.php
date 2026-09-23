<?php

use yii\db\Schema;
use yii\db\Migration;

class m190920_140843_create_sector_table extends Migration {

    public function safeUp() {
        $this->createTable('sector', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		$this->insert('sector', [ 'name' => 'Agriculture', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Forestry', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Fishery', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Manufactoring industry', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Construction sector', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Trade', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Services', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Logistik', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Winter Tourism', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Summer Tourism', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Tourism', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Transportation', 'visible' => true]);	
								   
    }

    public function safeDown() {
        $this->dropTable('sector');
    }

}
