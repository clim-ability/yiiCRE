<?php

use yii\db\Schema;
use yii\db\Migration;

class m211123_140843_refill_hazard_danger_table extends Migration {

    public function safeUp() {
		
        $connections = [
			['h'=>'fd', 'd'=>'Kälte & Frost', 'c'=>1.0],
			['h'=>'fd', 'd'=>'Schnee', 'c'=>0.4],
			['h'=>'fd', 'd'=>'Schneemangel', 'c'=>-0.4],

		    ['h'=>'tr', 'd'=>'Hitze', 'c'=>1.0],
			['h'=>'tr', 'd'=>'Dürre', 'c'=>0.2],

			['h'=>'rr20', 'd'=>'Starkregen', 'c'=>1.0],
			['h'=>'rr_winter', 'd'=>'Hochwasser', 'c'=>1.0],
			['h'=>'rr_winter', 'd'=>'Schnee', 'c'=>0.2],
			['h'=>'rr_winter', 'd'=>'Schneemangel', 'c'=>-0.2],

			['h'=>'rr_summer', 'd'=>'Dürre', 'c'=>-1.0],
			['h'=>'rr_summer', 'd'=>'Gewitter & Hagel', 'c'=>0.2],

			['h'=>'sd', 'd'=>'Temperaturanstieg', 'c'=>1.0],
			['h'=>'sd', 'd'=>'Gewitter & Hagel', 'c'=>0.1],
			['h'=>'sd', 'd'=>'Dürre', 'c'=>0.1]
		];
        foreach($connections as $c) {
            $hazard = $this->findHazard($c['h']);
			$danger = $this->findDanger($c['d']);
			$this->insert('hazard_danger', [ 'hazard_id' => $hazard['id'], 'danger_id' => $danger['id'],  
		      'abs_pos' => $c['c'], 'abs_neg' => -$c['c'], 'rel_pos' => $c['c'], 'rel_neg' => -$c['c']]);
		}
	
    }

    public function safeDown() {
		$this->delete('hazard_danger');
    }


	private function findHazard($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM hazard WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

	private function findDanger($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM danger WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 

}

