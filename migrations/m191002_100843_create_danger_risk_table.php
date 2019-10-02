<?php

use yii\db\Schema;
use yii\db\Migration;

class m191002_100843_create_danger_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('danger_risk', [
            'id' => Schema::TYPE_PK,
			'danger_id' => 'integer NOT NULL REFERENCES danger(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'impact' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		$this->insert('risk', [ 'name' => 'Finanzierungsrisiken', 'zone_id' => 11,'visible' => true]);
		
		// initialize each combination
        foreach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25] in $danger_id) {
			foreach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45] in $risk_id) {
				$r1 = 0.00001+0.0001*(rand(0, 1000)/1000.0);
		        $this->insert('danger_risk', [ 'risk_id' => $risk_id, 'danger_id' => $danger_id, 'impact' => $r1]);
		    }
		}
		this->updateRisk(1, [1,2,3,4], 0.5);
		this->updateRisk(1, [5,6], 0.25);
		this->updateRisk(2, [1,2,3,4], 0.5);
		this->updateRisk(2, [5,6], 0.25);
		this->updateRisk(3, [1,2,3,4], 0.5);
		this->updateRisk(3, [5,6], 0.25);
		this->updateRisk(4, [1,2,3,4], 0.5);
		this->updateRisk(4, [5,6], 0.25);
		this->updateRisk(5, [1,8], 1.0);		
		this->updateRisk(6, [1,2,3,7,9], 0.5);		
		this->updateRisk(7, [1,2,3,7,9], 0.5);
		this->updateRisk(8, [10], 1.0);	
		this->updateRisk(9, [8], 1.0);			
		this->updateRisk(10, [8], 1.0);	
		this->updateRisk(11, [8], 1.0);	
		this->updateRisk(12, [3,1], 1.0);
		this->updateRisk(13, [3], 0.9);	
		this->updateRisk(14, [3], 0.9);			
		this->updateRisk(15, [2], 0.4);	
		this->updateRisk(16, [1], 1.0);			
		this->updateRisk(17, [7], 1.0);	
		this->updateRisk(18, [4], 1.0);			
		this->updateRisk(19, [9], 0.8);	
		this->updateRisk(20, [1,2,3], 1.0);			
		this->updateRisk(21, [1], 1.0);	
		this->updateRisk(22, [1], 1.0);			
		this->updateRisk(23, [9], 1.0);	
		this->updateRisk(24, [4], 0.8);			
		this->updateRisk(25, [1], 1.0);	
		this->updateRisk(26, [2], 0.5);			
		this->updateRisk(27, [7], 1.0);	
		this->updateRisk(28, [4], 1.0);			
		this->updateRisk(29, [4], 1.0);	
		this->updateRisk(30, [4], 1.0);			
		this->updateRisk(31, [7,3,9,5,6], 0.5);	
		this->updateRisk(32, [3], 0.7);			
		this->updateRisk(33, [1], 1.0);	
		this->updateRisk(34, [2], 0.4);			
		this->updateRisk(35, [11,12], 1.0);	
		this->updateRisk(36, [8,11], 1.0);			
		this->updateRisk(37, [1,3], 1.0);	
		this->updateRisk(38, [4], 1.0);			
		this->updateRisk(39, [1,2,3], 0.9);	
		this->updateRisk(40, [10], 1.0);			
		this->updateRisk(41, [10], 1.0);	
		this->updateRisk(42, [10], 1.0);			
		this->updateRisk(43, [10], 1.0);	
		this->updateRisk(44, [10], 1.0);			
		this->updateRisk(45, [10], 1.0);		
    }

    public function safeDown() {
        $this->dropTable('danger_risk');
    }
	
	public function updateRisk($risk_id, $dangers, $impact) {
		foreach($dangers in $danger_id) {
		   $r1 = $impact+0.0001*(rand(0, 1000)/1000.0);
		   $this->update('danger_risk', ['impact' => $r1], ['risk_id' => $risk_id, 'danger_id' => $danger_id]);
		}
	}

}

