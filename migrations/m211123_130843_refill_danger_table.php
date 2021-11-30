<?php

use yii\db\Schema;
use yii\db\Migration;

class m211123_130843_refill_danger_table extends Migration {

    public function safeUp() {
		$this->delete('danger');
		$dangers = [];
		$negImpacts = $this->readCsv('impacts_negative.csv');
		foreach($negImpacts as $row) {
			$stressors = $this->extractStressors($row);
			foreach($stressors as $stressor) {
               $danger = trim($stressor);
			   if(!in_array($danger, $dangers) && strlen($danger)>1) {
				$dangers[] = $danger;  
			   }
			}
		}
		$posImpacts = $this->readCsv('impacts_positive.csv');
		foreach($posImpacts as $row) {
			$stressors = $this->extractStressors($row);
			foreach($stressors as $stressor) {
               $danger = trim($stressor);
			   if(!in_array($danger, $dangers) && strlen($danger)>1) {
				$dangers[] = $danger;  
			   }
			}
		}
		$allAdaptions = $this->readCsv('adaptations.csv', 19);
		foreach($allAdaptions as $row) {
			$stressors = $this->extractStressors($row, 'klimatische(r) Stressor(en)');
			foreach($stressors as $stressor) {
				$danger = trim($stressor);
				if(!in_array($danger, $dangers) && strlen($danger)>1) {
				 $dangers[] = $danger;  
				}
			 }
		}

        var_dump($dangers);
		foreach($dangers as $danger) {
		    $this->insert('danger', [ 'name' => $danger, 'visible' => true]);
		}
    }

    public function safeDown() {
        $this->delete('danger');

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
		$this->insert('danger', [ 'name' => 'unknown1', 'visible' => false]);	
		$this->insert('danger', [ 'name' => 'unknown2', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'unknown3', 'visible' => false]);	
    }

    private function extractStressors($row, $header='klimatischer Stressor'){
		$field = $row[$header];
		$field = str_replace('/',';',$field);
		$field = str_replace('Hitzewellen','Hitze',$field);
		$field = str_replace('allg. Temperaturanstieg','Temperaturanstieg',$field);
		$field = str_replace('allgemeiner Temperaturanstieg','Temperaturanstieg',$field);
		$field = str_replace('Gewitter&Hagel','Gewitter & Hagel',$field);
		$stressors = explode(';', $field);
		return $stressors;
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
