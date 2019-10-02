<?php

use yii\db\Schema;
use yii\db\Migration;

class m191002_090843_expand_hazard_danger_table extends Migration {

    public function safeUp() {
		
		// unknowns
		foreach([13,14,15] as $danger_id) {
		  foreach([51,52,53,54,55,56,57,58,59,61,62] as $hazard_id) {	
		    $r1 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$r2 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$r3 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$r4 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$this->insert('hazard_danger', [ 'hazard_id' => $hazard_id, 'danger_id' => 1,  
		      'abs_pos' => $r1, 'abs_neg' => $r2, 'rel_pos' => $r3, 'rel_neg' => $r4]);
		  }
        } 
		// hazards
		foreach([16,17,18,19,20,21,22,23,24,25] as $danger_key => $danger_id) {
		  foreach([51,52,53,54,55,56,57,58,59,61,62] as $hazard_key => $hazard_id) {	
		    $r1 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$r2 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$r3 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			$r4 = 0.001*((rand(0, 2000)/1000.0)-1.0);
			if($danger_key == $hazard_key) {
				$r1 += 0.7;
                $r2 += -0.7;
				$r1 += 0.3;
                $r2 += -0.3;				
			}
			$this->insert('hazard_danger', [ 'hazard_id' => $hazard_id, 'danger_id' => $danger_id,  
		      'abs_pos' => $r1, 'abs_neg' => $r2, 'rel_pos' => $r3, 'rel_neg' => $r4]);
		  }
        }	
    }

    public function safeDown() {
		foreach([13,14,15,16,17,18,19,20,21,22,23,24,25] as $danger_id) {
		   $this->delete('hazard_danger', ['danger_id' => $danger_id]);
		}
    }

}

