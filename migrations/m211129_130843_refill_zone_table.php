<?php

use yii\db\Schema;
use yii\db\Migration;

class m211129_130843_refill_zone_table extends Migration {

    public function safeUp() {
		$this->delete('zone');
		$zones = [];
		$negImpacts = $this->readCsv('impacts_negative.csv', 20);
		foreach($negImpacts as $row) {
			$bereiche = $this->extractBereiche($row, 'Unternehmensbereich');
			foreach($bereiche as $bereich) {
               $zone = trim($bereich);
			   if(!in_array($zone, $zones) && strlen($zone)>1) {
				$zones[] = $zone;  
			   }
			}
		}
		$posImpacts = $this->readCsv('impacts_positive.csv', 20);
		foreach($posImpacts as $row) {
			$bereiche = $this->extractBereiche($row, 'Unternehmensbereich');
			foreach($bereiche as $bereich) {
               $zone = trim($bereich);
			   if(!in_array($zone, $zones) && strlen($zone)>1) {
				$zones[] = $zone;  
			   }
			}
		}
		$posImpacts = $this->readCsv('adaptations.csv', 19);
		foreach($posImpacts as $row) {
			$bereiche = $this->extractBereiche($row, 'Unternehmensbereich / Handlungsfeld');
			foreach($bereiche as $bereich) {
               $zone = trim($bereich);
			   if(!in_array($zone, $zones) && strlen($zone)>1) {
				$zones[] = $zone;  
			   }
			}
		}
        var_dump($zones);
		foreach($zones as $zone) {
		    $this->insert('zone', [ 'name' => $zone, 'visible' => true]);
		}
    }

    public function safeDown() {
        $this->delete('zone');

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
		$this->insert('zone', [ 'name' => 'todo', 'visible' => false]);
    }

    private function extractBereiche($row, $header='Unternehmensbereich'){
		$field = $row[$header];
		//$field = str_replace('/',';',$field);
		$field = str_replace('Unternehmen gesamt','Unternehmen (gesamt)',$field);
		$field = str_replace('Kritische Infrastrukur','Kritische Infrastruktur',$field);
		$field = str_replace('Kritische Infrastrukturen','Kritische Infrastruktur',$field);
		$field = str_replace('Sonstige (Imageschaden)', 'Image & PR',$field);
		$field = str_replace('Rohstoffe & Beschaffung', 'Rohstoffe', $field);
		$field = str_replace('Rohstoffe', 'Rohstoffe & Beschaffung',$field);
		$field = str_replace('bauliche Maßnahmen', 'Bauliche Maßnahmen',$field);
		$field = str_replace('Management & Arbeitsorganisation', 'Management', $field);
		$field = str_replace('Management', 'Management & Arbeitsorganisation',$field);
		$field = str_replace('Verhalten', 'KurzMalMerken',$field);
		$field = str_replace('Personal', 'Personal & Verhalten',$field);
		$field = str_replace('KurzMalMerken', 'Personal & Verhalten',$field);
		$field = str_replace('Innovationen im Geschäftsmodell', 'Innovation im Geschäftsmodell',$field);
        $field = str_replace('sonstige technische Maßnahmen', 'Technische Maßnahmen',$field);
        $field = str_replace('Produktion / Betrieb', 'Produktion & Betrieb',$field);
		

		$branches = explode(';', $field);
		return $branches;
	}

	private function readCsv($csvFile, $columns=20) {
        $path = Yii::$app->basePath . '/migrations';
        $filename = $path . '/' . $csvFile;
		$csvData = [];
		if (file_exists($filename)) {
            $handle = fopen($filename, "r");
            if (!is_null($handle)) {
                $lineNumber = 1;
                $header = null;
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    if (sizeof($data) == $columns) {
                        // $data = array_map("utf8_encode", $data); //added
                        if (1 == $lineNumber) {
                            $header = $data;
                        } else {
                            $row = array_combine($header, $data);
                            $csvData[] = $row;
                        }
                    } else {
                        printf("\n Wrong Column Size (" . sizeof($data) . " instead of ".$columns.") for linenumber [" . $lineNumber . "] in csv-file \n");
                    }
                    $lineNumber++;
                }

                fclose($handle);
                printf("\n ok \n");
            } else {
                printf("\n Failed to Import csv-file \n");
            }
        } else {
            printf("\n Required csv-file does not exist. \n");
        }
        return $csvData;
	}




}
