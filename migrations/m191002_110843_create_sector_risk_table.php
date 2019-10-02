<?php

use yii\db\Schema;
use yii\db\Migration;

class m191002_110843_create_sector_risk_table extends Migration {

    public function safeUp() {
        $this->createTable('sector_risk', [
            'id' => Schema::TYPE_PK,
			'sector_id' => 'integer NOT NULL REFERENCES sector(id) ON DELETE CASCADE',
            'risk_id' => 'integer NOT NULL REFERENCES risk(id) ON DELETE CASCADE',			
            'impact' => Schema::TYPE_DOUBLE . ' DEFAULT 0.5',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		// initialize each combination
        foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $section_id) {
			foreach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45] as $risk_id) {
				$r1 = 0.5+0.1*(rand(0, 1000)/1000.0);
		        $this->insert('sector_risk', [ 'risk_id' => $risk_id, 'sector_id' => $section_id, 'impact' => $r1]);
		    }
		}
		$this->updateRisk(1, [4,6,8,12], 0.9);
		$this->updateRisk(1, [1,3,5,7],  0.3);
		$this->updateRisk(2, [4], 1.0);
		$this->updateRisk(2, [1,2,3,5,9,10,11], 0.2);
		$this->updateRisk(3, [4,6,8,12], 0.9);
		$this->updateRisk(3, [7], 0.35);
		$this->updateRisk(4, [4,6,8,12], 0.9);
		$this->updateRisk(4, [1,2,3,5,7,9,10,11], 0.2);
		$this->updateRisk(5, [3,6,8,12], 0.9);
		$this->updateRisk(5, [5,7,9,11], 0.3);		
		$this->updateRisk(6, [4,5,7,8], 0.9);
		$this->updateRisk(6, [1,2,3], 0.4);		
		$this->updateRisk(7, [4], 0.9);
		$this->updateRisk(7, [2,3], 0.3);		
		$this->updateRisk(8, [1,3,4,5,6,7,8,9,11,12], 0.9);	
		$this->updateRisk(9, [1,2,3,4,11], 0.9);			
		$this->updateRisk(10, [4], 0.9);
		$this->updateRisk(10, [1,2,3,5,6,7], 0.2);		
		$this->updateRisk(11, [1,2,3,4,9,10,11], 0.8);	
		$this->updateRisk(12, [1,4,5,6,7,8,9,10,11], 0.9);
		$this->updateRisk(12, [2,3,12], 0.2);		
		$this->updateRisk(13, [1,4,5,6,7,8,9,10,11], 0.8);
		$this->updateRisk(13, [2,3,12], 0.15);	
		$this->updateRisk(14, [1,4,5,6,7,8,9,10,11], 0.7);
		$this->updateRisk(14, [2,3], 0.1);				
		$this->updateRisk(15, [1,4,5,6,7,8,9,10,11], 0.9);
		$this->updateRisk(15, [2,3,12], 0.35);		
		$this->updateRisk(16, [1,4,5,6,7,8,9,10,11], 0.8);
		$this->updateRisk(16, [2,3,12], 0.15);		
		$this->updateRisk(17, [4,5,6,7,8,11,12], 0.8);
		$this->updateRisk(17, [2,3], 0.2);		
		$this->updateRisk(18, [1,4,5,6,7,8,10], 0.9);			
		$this->updateRisk(19, [1,4,5,7,8], 0.7);	
		$this->updateRisk(20, [1,4,5,6,7,8,9,10,11], 0.9);			
		$this->updateRisk(21, [4], 0.9);
		$this->updateRisk(21, [6,7,8,9,10,11,12], 0.4);		
		$this->updateRisk(22, [4], 0.9);
		$this->updateRisk(22, [6,7,8,9,10,11,12], 0.3);			
		$this->updateRisk(23, [4], 0.8);
		$this->updateRisk(23, [6,7,8,9,10,11,12], 0.2);	
		$this->updateRisk(24, [4], 0.9);
		$this->updateRisk(24, [6,7,8,9,10,11,12], 0.4);	
		$this->updateRisk(25, [1,2,4,6,8,12], 0.9);
		$this->updateRisk(25, [7,9,10,11], 0.2);
		$this->updateRisk(26, [1,2,4,6,8,12], 0.7);
		$this->updateRisk(26, [7,9,10,11], 0.1);		
		$this->updateRisk(27, [1,2,4,6,8,12], 0.7);
		$this->updateRisk(27, [7,9,10,11], 0.1);	
		$this->updateRisk(28, [1,2,4,6,8,12], 0.9);
		$this->updateRisk(28, [7,9,10,11], 0.2);		
		$this->updateRisk(29, [1,2,4,5,6,7,8,10,12], 0.85);	
		$this->updateRisk(30, [1,2,4,5,6,7,8,10,12], 0.9);			
		$this->updateRisk(31, [1,2,3,9,10], 0.9);
		$this->updateRisk(31, [4,5,6,7], 0.2);		
		$this->updateRisk(32, [1,2,3,9,10], 0.7);
		$this->updateRisk(32, [4,5,6,7], 0.1);				
		$this->updateRisk(33, [1,2,4,5,6,7,8], 0.9);	
		$this->updateRisk(34, [1,2,3,9,10], 0.7);
		$this->updateRisk(34, [4,5,6,7], 0.1);			
		$this->updateRisk(35, [1,2,4,7,8,12], 0.8);	
		$this->updateRisk(36, [1,2,4,7,8,12], 0.9);			
		$this->updateRisk(37, [4,5,6,7,8], 0.9);	
		$this->updateRisk(38, [4,5,6,7,8], 0.9);			
		$this->updateRisk(39, [4,5,6,7,8], 0.9);	
		$this->updateRisk(40, [1,4,6,9,11,12], 0.9);			
		$this->updateRisk(41, [1,4,6,12], 0.9);	
		$this->updateRisk(42, [4,5,6,7,8,12], 0.7);			
		$this->updateRisk(43, [4,5,6,7,8,12], 0.8);	
		$this->updateRisk(44, [4,12,8], 0.85);			
		$this->updateRisk(45, [1,2,4,5,9], 0.75);		
    }

    public function safeDown() {
        $this->dropTable('sector_risk');
    }
	
	private function updateRisk($risk_id, $sections, $impact) {
		foreach($sections as $section_id) {
		   $r1 = $impact+0.0001*(rand(0, 1000)/1000.0);
		   $this->update('sector_risk', ['impact' => $r1], ['risk_id' => $risk_id, 'sector_id' => $section_id]);
		}
	}

}

