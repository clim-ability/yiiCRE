<?php

use yii\db\Schema;
use yii\db\Migration;

class m190920_130843_create_danger_table extends Migration {

    public function safeUp() {
        $this->createTable('danger', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		$this->insert('station', [ 'name' => 'Überschwemmung', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Sturm', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Starkregen', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Hitzewellen', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Schnee', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Frost', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Hagel', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Trockenperiode', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Blitzschlag', 'visible' => true]);
		$this->insert('station', [ 'name' => 'Allgemein', 'visible' => true]);
		$this->insert('station', [ 'name' => 'milde Winter', 'visible' => true]);
		$this->insert('station', [ 'name' => 'längere Sommer', 'visible' => true]);
								   
    }

    public function safeDown() {
        $this->dropTable('danger');
    }

}
