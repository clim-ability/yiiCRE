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
		
		$this->insert('danger', [ 'name' => 'Überschwemmung', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Sturm', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Starkregen', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Hitzewellen', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Schnee', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Frost', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Hagel', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Trockenperiode', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Blitzschlag', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'Allgemein', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'milde Winter', 'visible' => true]);
		$this->insert('danger', [ 'name' => 'längere Sommer', 'visible' => true]);
								   
    }

    public function safeDown() {
        $this->dropTable('danger');
    }

}
