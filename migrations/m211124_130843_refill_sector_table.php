<?php

use yii\db\Schema;
use yii\db\Migration;

class m211124_130843_refill_sector_table extends Migration {

    public function safeUp() {
		$this->delete('sector');
		$sectors = [];
		$negImpacts = $this->readCsv('impacts_negative.csv', 20);
		foreach($negImpacts as $row) {
			$branches = $this->extractBranches($row, 'Branche(n)');
			foreach($branches as $branch) {
               $sector = trim($branch);
			   if(!in_array($sector, $sectors) && strlen($sector)>1) {
				$sectors[] = $sector;  
			   }
			}
		}
		$posImpacts = $this->readCsv('impacts_positive.csv', 13);
		foreach($posImpacts as $row) {
			$branches = $this->extractBranches($row, 'Branche(n)');
			foreach($branches as $branch) {
               $sector = trim($branch);
			   if(!in_array($sector, $sectors) && strlen($sector)>1) {
				$sectors[] = $sector;  
			   }
			}
		}
		$posImpacts = $this->readCsv('adaptations.csv', 19);
		foreach($posImpacts as $row) {
			$branches = $this->extractBranches($row, 'Branche(n)');
			foreach($branches as $branch) {
               $sector = trim($branch);
			   if(!in_array($sector, $sectors) && strlen($sector)>1) {
				$sectors[] = $sector;  
			   }
			}
		}
        var_dump($sectors);
		foreach($sectors as $sector) {
		    $this->insert('sector', [ 'name' => $sector, 'visible' => true]);
		}
    }

    public function safeDown() {
        $this->delete('sector');

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

    private function extractBranches($row, $header='Branche(n)'){
		$field = $row[$header];
		//$field = str_replace('/',';',$field);
		$field = str_replace('Verbeitendes Gewerbe','Verarbeitendes Gewerbe',$field);
        $field = str_replace('Bauwirtschaft','Bausektor',$field);
		$field = str_replace('Energiewirtschaft','Energieversorgung',$field);

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
