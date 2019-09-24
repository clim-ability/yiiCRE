<?php

use yii\db\Schema;
use yii\db\Migration;

class m190924_150843_create_hazard_danger_table extends Migration {

    public function safeUp() {
		
		$this->insert('hazard', [ 'name' => 'const', 'visible' => false]);

		
        $this->createTable('hazard_danger', [
            'id' => Schema::TYPE_PK,
            'hazard_id' => 'integer NOT NULL REFERENCES hazard(id) ON DELETE CASCADE',
			'danger_id' => 'integer NOT NULL REFERENCES danger(id) ON DELETE CASCADE',
            'abs_pos' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'abs_neg' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'rel_pos' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'rel_neg' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		// Überschwemmung
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 1,  
		   'abs_pos' => -0.1, 'abs_neg' => 0.11, 'rel_pos' => -0.5, 'rel_neg' => 0.5]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 1,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.001, 'rel_neg' => 0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 1,  
		   'abs_pos' => 0.002, 'abs_neg' => -0.002, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 1,  
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.001, 'rel_neg' => -0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 1, 
		   'abs_pos' => 0.25, 'abs_neg' => -0.2, 'rel_pos' => 1.0, 'rel_neg' => -0.8]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 1, 
		   'abs_pos' => 0.2, 'abs_neg' => -0.15, 'rel_pos' => 0.8, 'rel_neg' => -0.6]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 1, 
		   'abs_pos' => 0.15, 'abs_neg' => -0.1, 'rel_pos' => 0.7, 'rel_neg' => -0.5]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 1, 
		   'abs_pos' => 0.3, 'abs_neg' => -0.2, 'rel_pos' => 1.0, 'rel_neg' => -0.5]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 1,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.001, 'rel_neg' => 0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 1,  
		   'abs_pos' => 0.002, 'abs_neg' => -0.001, 'rel_pos' => 0.005, 'rel_neg' => -0.004]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 1,  
		   'abs_pos' => 0.001, 'abs_neg' => 0.001, 'rel_pos' => 0.001, 'rel_neg' => 0.001]);

		// Sturm
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 2,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.001, 'rel_neg' => 0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 2,  
		   'abs_pos' => +0.02, 'abs_neg' => -0.02, 'rel_pos' => +0.03, 'rel_neg' => -0.03]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 2,  
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 2,  
		   'abs_pos' => 0.15, 'abs_neg' => -0.1, 'rel_pos' => 0.25, 'rel_neg' => -0.2]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 2, 
		   'abs_pos' => 0.05, 'abs_neg' => -0.02, 'rel_pos' => 0.2, 'rel_neg' => -0.1]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 2, 
		   'abs_pos' => 0.03, 'abs_neg' => -0.01, 'rel_pos' => 0.1, 'rel_neg' => -0.05]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 2, 
		   'abs_pos' => 0.03, 'abs_neg' => -0.01, 'rel_pos' => 0.1, 'rel_neg' => -0.05]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 2, 
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 2,  
		   'abs_pos' => 0.01, 'abs_neg' => -0.01, 'rel_pos' => 0.02, 'rel_neg' => -0.02]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 2,  
		   'abs_pos' => 0.02, 'abs_neg' => -0.01, 'rel_pos' => 0.02, 'rel_neg' => -0.01]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 2,  
		   'abs_pos' => 0.001, 'abs_neg' => 0.001, 'rel_pos' => 0.001, 'rel_neg' => 0.001]);

        // Starkregen
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 3,  
		   'abs_pos' => -0.13, 'abs_neg' => 0.14, 'rel_pos' => -0.6, 'rel_neg' => 0.6]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 3,  
		   'abs_pos' => -0.002, 'abs_neg' => 0.002, 'rel_pos' => -0.003, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 3,  
		   'abs_pos' => 0.001, 'abs_neg' => -0.002, 'rel_pos' => 0.002, 'rel_neg' => -0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 3,  
		   'abs_pos' => 0.002, 'abs_neg' => -0.001, 'rel_pos' => 0.004, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 3, 
		   'abs_pos' => 0.31, 'abs_neg' => -0.32, 'rel_pos' => 1.1, 'rel_neg' => -0.9]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 3, 
		   'abs_pos' => 0.15, 'abs_neg' => -0.17, 'rel_pos' => 0.6, 'rel_neg' => -0.5]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 3, 
		   'abs_pos' => 0.16, 'abs_neg' => -0.12, 'rel_pos' => 0.65, 'rel_neg' => -0.55]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 3, 
		   'abs_pos' => 0.02, 'abs_neg' => -0.012, 'rel_pos' => 0.03, 'rel_neg' => -0.025]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 3,  
		   'abs_pos' => -0.002, 'abs_neg' => 0.002, 'rel_pos' => -0.003, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 3,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.002, 'rel_pos' => -0.004, 'rel_neg' => 0.003]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 3,  
		   'abs_pos' => 0.002, 'abs_neg' => -0.001, 'rel_pos' => 0.003, 'rel_neg' => -0.001]);	

        // Hitzewellen
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 4,  
		   'abs_pos' => 0.2, 'abs_neg' => -0.18, 'rel_pos' => 0.7, 'rel_neg' => -0.5]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 4,  
		   'abs_pos' => 0.25, 'abs_neg' => -0.22, 'rel_pos' => 0.8, 'rel_neg' => -0.6]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 4,  
		   'abs_pos' => -0.1, 'abs_neg' => 0.15, 'rel_pos' => -0.5, 'rel_neg' => 0.6]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 4,  
		   'abs_pos' => 0.27, 'abs_neg' => -0.21, 'rel_pos' => 0.7, 'rel_neg' => -0.65]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 4, 
		   'abs_pos' => 0.005, 'abs_neg' => -0.002, 'rel_pos' => 0.002, 'rel_neg' => -0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 4, 
		   'abs_pos' => 0.005, 'abs_neg' => -0.002, 'rel_pos' => 0.002, 'rel_neg' => -0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 4, 
		   'abs_pos' => -0.05, 'abs_neg' => 0.02, 'rel_pos' => -0.02, 'rel_neg' => 0.01]);	   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 4, 
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 4,  
		   'abs_pos' => -0.15, 'abs_neg' => 0.1, 'rel_pos' => -0.2, 'rel_neg' => 0.25]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 4,  
		   'abs_pos' => 0.32, 'abs_neg' => -0.3, 'rel_pos' => 0.92, 'rel_neg' => -0.81]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 4,  
		   'abs_pos' => 0.001, 'abs_neg' => 0.001, 'rel_pos' => 0.001, 'rel_neg' => 0.001]);	

        // Schnee
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 5,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.002, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 5,  
		   'abs_pos' => -0.002, 'abs_neg' => +0.002, 'rel_pos' => -0.003, 'rel_neg' => +0.003]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 5,  
		   'abs_pos' => 0.15, 'abs_neg' => -0.13, 'rel_pos' => 0.62, 'rel_neg' => -0.55]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 5,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.02, 'rel_neg' => +0.02]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 5, 
		   'abs_pos' => 0.05, 'abs_neg' => -0.02, 'rel_pos' => 0.1, 'rel_neg' => -0.08]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 5, 
		   'abs_pos' => 0.23, 'abs_neg' => -0.21, 'rel_pos' => 0.5, 'rel_neg' => -0.45]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 5, 
		   'abs_pos' => -0.003, 'abs_neg' => +0.001, 'rel_pos' => -0.01, 'rel_neg' => 0.02]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 5, 
		   'abs_pos' => -0.01, 'abs_neg' => 0.01, 'rel_pos' => -0.002, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 5,  
		   'abs_pos' => 0.15, 'abs_neg' => -0.13, 'rel_pos' => 0.55, 'rel_neg' => -0.45]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 5,  
		   'abs_pos' => -0.01, 'abs_neg' => 0.012, 'rel_pos' => -0.2, 'rel_neg' => 0.17]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 5,  
		   'abs_pos' => -0.001, 'abs_neg' => -0.001, 'rel_pos' => -0.001, 'rel_neg' => -0.001]);		
		   
		// Frost
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 6,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.002, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 6,  
		   'abs_pos' => -0.002, 'abs_neg' => +0.002, 'rel_pos' => -0.003, 'rel_neg' => +0.003]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 6,  
		   'abs_pos' => 0.15, 'abs_neg' => -0.13, 'rel_pos' => 0.72, 'rel_neg' => -0.65]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 6,  
		   'abs_pos' => -0.05, 'abs_neg' => 0.04, 'rel_pos' => -0.2, 'rel_neg' => +0.22]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 6, 
		   'abs_pos' => -0.001, 'abs_neg' => 0.002, 'rel_pos' => -0.001, 'rel_neg' => +0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 6, 
		   'abs_pos' => -0.003, 'abs_neg' => 0.003, 'rel_pos' => -0.004, 'rel_neg' => +0.004]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 6, 
		   'abs_pos' => 0.002, 'abs_neg' => -0.001, 'rel_pos' => 0.003, 'rel_neg' => -0.002]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 6, 
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.002, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 6,  
		   'abs_pos' => 0.15, 'abs_neg' => -0.13, 'rel_pos' => 0.45, 'rel_neg' => -0.35]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 6,  
		   'abs_pos' => -0.017, 'abs_neg' => 0.013, 'rel_pos' => -0.22, 'rel_neg' => 0.18]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 6,  
		   'abs_pos' => -0.001, 'abs_neg' => +0.001, 'rel_pos' => -0.001, 'rel_neg' => +0.001]);	

        // Hagel
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 7,  
		   'abs_pos' => -0.07, 'abs_neg' => 0.06, 'rel_pos' => -0.3, 'rel_neg' => 0.2]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 7,  
		   'abs_pos' => +0.13, 'abs_neg' => -0.12, 'rel_pos' => +0.4, 'rel_neg' => -0.3]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 7,  
		   'abs_pos' => 0.001, 'abs_neg' => -0.002, 'rel_pos' => 0.002, 'rel_neg' => -0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 7,  
		   'abs_pos' => 0.003, 'abs_neg' => -0.002, 'rel_pos' => 0.004, 'rel_neg' => -0.003]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 7, 
		   'abs_pos' => 0.21, 'abs_neg' => -0.12, 'rel_pos' => 0.6, 'rel_neg' => -0.7]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 7, 
		   'abs_pos' => 0.05, 'abs_neg' => -0.07, 'rel_pos' => 0.09, 'rel_neg' => -0.1]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 7, 
		   'abs_pos' => 0.16, 'abs_neg' => -0.12, 'rel_pos' => 0.65, 'rel_neg' => -0.55]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 7, 
		   'abs_pos' => 0.002, 'abs_neg' => -0.0012, 'rel_pos' => 0.003, 'rel_neg' => -0.0025]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 7,  
		   'abs_pos' => -0.002, 'abs_neg' => 0.002, 'rel_pos' => -0.003, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 7,  
		   'abs_pos' => 0.01, 'abs_neg' => -0.02, 'rel_pos' => 0.04, 'rel_neg' => -0.03]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 7,  
		   'abs_pos' => 0.002, 'abs_neg' => -0.001, 'rel_pos' => 0.003, 'rel_neg' => -0.001]);	

        //Trockenperiode
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 8,  
		   'abs_pos' => 0.25, 'abs_neg' => -0.22, 'rel_pos' => 0.9, 'rel_neg' => -0.85]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 8,  
		   'abs_pos' => 0.24, 'abs_neg' => -0.21, 'rel_pos' => 0.8, 'rel_neg' => -0.7]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 8,  
		   'abs_pos' => -0.07, 'abs_neg' => 0.06, 'rel_pos' => -0.06, 'rel_neg' => 0.05]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 8,  
		   'abs_pos' => 0.057, 'abs_neg' => -0.041, 'rel_pos' => 0.15, 'rel_neg' => -0.17]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 8, 
		   'abs_pos' => -0.085, 'abs_neg' => 0.072, 'rel_pos' => -0.072, 'rel_neg' => 0.077]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 8, 
		   'abs_pos' => -0.08, 'abs_neg' => 0.07, 'rel_pos' => -0.07, 'rel_neg' => 0.071]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 8, 
		   'abs_pos' => -0.095, 'abs_neg' => 0.088, 'rel_pos' => -0.11, 'rel_neg' => 0.12]);   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 8, 
		   'abs_pos' => -0.002, 'abs_neg' => 0.002, 'rel_pos' => -0.003, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 8,  
		   'abs_pos' => -0.005, 'abs_neg' => 0.004, 'rel_pos' => -0.007, 'rel_neg' => 0.0075]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 8,  
		   'abs_pos' => 0.22, 'abs_neg' => -0.21, 'rel_pos' => 0.42, 'rel_neg' => -0.41]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 8,  
		   'abs_pos' => 0.003, 'abs_neg' => -0.002, 'rel_pos' => 0.006, 'rel_neg' => -0.002]);
        		   
		// Blitzschlag
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 9,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.001, 'rel_neg' => 0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 9,  
		   'abs_pos' => +0.02, 'abs_neg' => -0.02, 'rel_pos' => +0.03, 'rel_neg' => -0.03]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 9,  
		   'abs_pos' => -0.04, 'abs_neg' => 0.03, 'rel_pos' => -0.06, 'rel_neg' => 0.07]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 9,  
		   'abs_pos' => 0.14, 'abs_neg' => -0.11, 'rel_pos' => 0.24, 'rel_neg' => -0.19]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 9, 
		   'abs_pos' => 0.06, 'abs_neg' => -0.03, 'rel_pos' => 0.25, 'rel_neg' => -0.15]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 9, 
		   'abs_pos' => 0.003, 'abs_neg' => -0.001, 'rel_pos' => 0.01, 'rel_neg' => -0.01]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 9, 
		   'abs_pos' => 0.04, 'abs_neg' => -0.03, 'rel_pos' => 0.2, 'rel_neg' => -0.25]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 9, 
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 9,  
		   'abs_pos' => 0.02, 'abs_neg' => -0.02, 'rel_pos' => 0.03, 'rel_neg' => -0.035]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 9,  
		   'abs_pos' => 0.005, 'abs_neg' => -0.006, 'rel_pos' => 0.014, 'rel_neg' => -0.012]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 9,  
		   'abs_pos' => 0.001, 'abs_neg' => 0.001, 'rel_pos' => 0.001, 'rel_neg' => 0.001]);		   

        // Allgemein
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 10,  
		   'abs_pos' => 0.05, 'abs_neg' => -0.05, 'rel_pos' => 0.15, 'rel_neg' => -0.11]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 10,  
		   'abs_pos' => 0.07, 'abs_neg' => -0.08, 'rel_pos' => 0.17, 'rel_neg' => -0.16]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 10,  
		   'abs_pos' => -0.07, 'abs_neg' => 0.06, 'rel_pos' => -0.16, 'rel_neg' => 0.15]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 10,  
		   'abs_pos' => 0.058, 'abs_neg' => -0.038, 'rel_pos' => 0.14, 'rel_neg' => -0.18]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 10, 
		   'abs_pos' => 0.015, 'abs_neg' => -0.012, 'rel_pos' => 0.034, 'rel_neg' => -0.032]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 10, 
		   'abs_pos' => 0.02, 'abs_neg' => -0.017, 'rel_pos' => 0.045, 'rel_neg' => -0.041]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 10, 
		   'abs_pos' => -0.015, 'abs_neg' => 0.021, 'rel_pos' => -0.061, 'rel_neg' => 0.062]);   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 10, 
		   'abs_pos' => 0.002, 'abs_neg' => -0.002, 'rel_pos' => 0.003, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 10,  
		   'abs_pos' => -0.001, 'abs_neg' => 0.001, 'rel_pos' => -0.002, 'rel_neg' => 0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 10,  
		   'abs_pos' => 0.08, 'abs_neg' => -0.09, 'rel_pos' => 0.38, 'rel_neg' => -0.32]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 10,  
		   'abs_pos' => 0.005, 'abs_neg' => -0.003, 'rel_pos' => 0.009, 'rel_neg' => -0.003]);	

        // milde Winter		   
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 11,  
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 11,  
		   'abs_pos' => 0.03, 'abs_neg' => -0.04, 'rel_pos' => 0.09, 'rel_neg' => -0.08]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 11,  
		   'abs_pos' => -0.25, 'abs_neg' => 0.23, 'rel_pos' => -0.72, 'rel_neg' => 0.65]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 11,  
		   'abs_pos' => 0.015, 'abs_neg' => -0.017, 'rel_pos' => 0.045, 'rel_neg' => -0.052]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 11, 
		   'abs_pos' => -0.005, 'abs_neg' => 0.002, 'rel_pos' => -0.01, 'rel_neg' => 0.008]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 11, 
		   'abs_pos' => -0.0023, 'abs_neg' => 0.0021, 'rel_pos' => -0.005, 'rel_neg' => 0.0045]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 11, 
		   'abs_pos' => 0.003, 'abs_neg' => -0.001, 'rel_pos' => 0.01, 'rel_neg' => -0.02]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 11, 
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 11,  
		   'abs_pos' => -0.12, 'abs_neg' => 0.14, 'rel_pos' => -0.34, 'rel_neg' => 0.27]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 11,  
		   'abs_pos' => 0.02, 'abs_neg' => -0.022, 'rel_pos' => 0.18, 'rel_neg' => -0.19]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 11,  
		   'abs_pos' => 0.001, 'abs_neg' => 0.001, 'rel_pos' => 0.001, 'rel_neg' => 0.001]);	

        // längere Sommer
		$this->insert('hazard_danger', [ 'hazard_id' => 51, 'danger_id' => 12,  
		   'abs_pos' => 0.04, 'abs_neg' => -0.036, 'rel_pos' => 0.14, 'rel_neg' => -0.1]);
		$this->insert('hazard_danger', [ 'hazard_id' => 52, 'danger_id' => 12,  
		   'abs_pos' => 0.15, 'abs_neg' => -0.12, 'rel_pos' => 0.6, 'rel_neg' => -0.5]);
		$this->insert('hazard_danger', [ 'hazard_id' => 53, 'danger_id' => 12,  
		   'abs_pos' => -0.01, 'abs_neg' => 0.015, 'rel_pos' => -0.05, 'rel_neg' => 0.06]);
		$this->insert('hazard_danger', [ 'hazard_id' => 54, 'danger_id' => 12,  
		   'abs_pos' => 0.28, 'abs_neg' => -0.22, 'rel_pos' => 0.65, 'rel_neg' => -0.63]);
		$this->insert('hazard_danger', [ 'hazard_id' => 55, 'danger_id' => 12, 
		   'abs_pos' => -0.005, 'abs_neg' => 0.002, 'rel_pos' => -0.002, 'rel_neg' => 0.001]);
		$this->insert('hazard_danger', [ 'hazard_id' => 56, 'danger_id' => 12, 
		   'abs_pos' => 0.004, 'abs_neg' => -0.002, 'rel_pos' => 0.003, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 57, 'danger_id' => 12, 
		   'abs_pos' => -0.005, 'abs_neg' => 0.002, 'rel_pos' => -0.002, 'rel_neg' => 0.001]);	   
		$this->insert('hazard_danger', [ 'hazard_id' => 58, 'danger_id' => 12, 
		   'abs_pos' => 0.001, 'abs_neg' => -0.001, 'rel_pos' => 0.002, 'rel_neg' => -0.002]);
		$this->insert('hazard_danger', [ 'hazard_id' => 59, 'danger_id' => 12,  
		   'abs_pos' => -0.015, 'abs_neg' => 0.01, 'rel_pos' => -0.02, 'rel_neg' => 0.025]);
		$this->insert('hazard_danger', [ 'hazard_id' => 61, 'danger_id' => 12,  
		   'abs_pos' => 0.29, 'abs_neg' => -0.27, 'rel_pos' => 0.89, 'rel_neg' => -0.79]);		   
		$this->insert('hazard_danger', [ 'hazard_id' => 62, 'danger_id' => 12,  
		   'abs_pos' => 0.001, 'abs_neg' => 0.001, 'rel_pos' => 0.001, 'rel_neg' => 0.001]);	
		   
    }

    public function safeDown() {
        $this->dropTable('hazard_danger');
		$this->delete('hazard', ['name' => 'const']);

    }

}

