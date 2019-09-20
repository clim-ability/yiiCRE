<?php

use yii\db\Schema;
use yii\db\Migration;

class m190920_150843_create_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('risk', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'zone_id' => 'integer NOT NULL REFERENCES zone(id) ON DELETE CASCADE',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		$this->insert('risk', [ 'name' => 'Beschaffungsprobleme bei Lieferanten', 'zone_id' => 1,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschaffungsprobleme bei Sublieferanten', 'zone_id' => 1,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Störung Straßentransportlogistik', 'zone_id' => 2,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Störung Schienentransportlogistik', 'zone_id' => 2,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Störung Binnenschifffahrt', 'zone_id' => 2,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Stromausfälle', 'zone_id' => 3,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Ausfälle eigene Energieproduktion', 'zone_id' => 3,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Anstieg Energiekosten', 'zone_id' => 3,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Eingeschränkte Wasserverfügbarkeit', 'zone_id' => 4,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Verschärfte Wärmegrenzwerte Abwasser', 'zone_id' => 4,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Schwankungen der Brauchwasserqualität', 'zone_id' => 4,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Durchfeuchtung und Schädigung der Bausubstanz', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Gebäudeschäden durch Rückstau in Kanalisation', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Gebäudeschäden durch Erdrutsch', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Sturmschäden an Flächen und Gebäuden', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Überschwemmungschäden an Flächen und Gebäuden', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Hagelschäden an Gebäuden', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Überhitzung von Gebäuden', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Gebäudeschäden durch Blitzschlag, anschliessende Schwellbrände oder Feuer', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Versicherungsprobleme', 'zone_id' => 5,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschädigung von Anlagen durch Überschwemmung', 'zone_id' => 6,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschädigung kritischer Anlagen durch Überschwemmung mit Betriebsunterbrechung', 'zone_id' => 6,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Überspannungsschäden an Anlagen und Maschinen durch Blitzschlag', 'zone_id' => 6,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Effizienzrückgang bei Maschinen und Anlagen durch erhöhte Außentemperaturen', 'zone_id' => 6,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschädigung von Lagerbeständen durch Überschwemmungen', 'zone_id' => 7,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschädigung von Lagerbeständen durch Stürme', 'zone_id' => 7,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschädigung von Lagerbeständen durch Hagel', 'zone_id' => 7,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Beschädigung von Lagerbeständen durch Hitze', 'zone_id' => 7,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Hitzebedingter Verlust an Mitarbeiterproduktivität - Verschärfung durch Tropennächte', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Hitzebedingter Verlust an Mitarbeiterproduktivität', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Behinderung von Arbeiten unter freiem Himmel durch Hagel', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Behinderung von Arbeiten unter freiem Himmel durch einen Erdrutsch', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Behinderung von Arbeiten  durch Überschwemmungen', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Behinderung von Arbeiten unter freiem Himmel durch Stürme', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Zunahme krankheitsbedingter Ausfälle', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Verlust an Mitarbeiterproduktivität und Zunahme krankheitsbedingte Ausfälle durch Allergien', 'zone_id' => 8,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Ausfall und Beschädigung des betrieblichen IT-Systems durch Überschwemmung', 'zone_id' => 9,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Ausfall und Beschädigung des betrieblichen IT-Systems durch Überhitzung', 'zone_id' => 9,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Störungen und Ausfälle des Telekommunikationsnetztes', 'zone_id' => 9,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Nachfrageschwankungen', 'zone_id' => 10,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Reputationsrisiken', 'zone_id' => 10,'visible' => true]);	
		$this->insert('risk', [ 'name' => 'Zunahme von Managementanforderungen', 'zone_id' => 11,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Höhere Compliance-Kosten', 'zone_id' => 11,'visible' => true]);
		$this->insert('risk', [ 'name' => 'Kosten für Emissionszertifikate', 'zone_id' => 11,'visible' => true]);
    }

    public function safeDown() {
        $this->dropTable('risk');
    }

}
