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
		
		$this->insert('sector', [ 'name' => 'Lieferanten', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Verkehrsinfrastruktur', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Energieversorgung', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Wasserversorgung', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Gebäude und Flächen', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Produktionsanlagen', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Lagerbestände', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Mitarbeiter', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'IT und Kommunikation', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Kunden', 'visible' => true]);
		$this->insert('sector', [ 'name' => 'Management und Compliance', 'visible' => true]);
	
								   
    }

    public function safeDown() {
        $this->dropTable('sector');
    }

}
