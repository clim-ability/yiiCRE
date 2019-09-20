<?php

use yii\db\Schema;
use yii\db\Migration;

class m190920_140843_create_zone_table extends Migration {

    public function safeUp() {
        $this->createTable('zone', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		$this->insert('zone', [ 'name' => 'Lieferanten', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Verkehrsinfrastruktur', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Energieversorgung', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Wasserversorgung', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Gebäude und Flächen', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Produktionsanlagen', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Lagerbestände', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Mitarbeiter', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'IT und Kommunikation', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Kunden', 'visible' => true]);
		$this->insert('zone', [ 'name' => 'Management und Compliance', 'visible' => true]);
	
								   
    }

    public function safeDown() {
        $this->dropTable('zone');
    }

}
