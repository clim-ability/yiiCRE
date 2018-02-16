<?php

use yii\db\Migration;

class m171220_140843_add_scenario_to_table extends Migration {

    public function safeUp() {
      $this->addScenario('rcp2.6', false);
      $this->addScenario('rcp4.5', true);
      $this->addScenario('rcp6.0', false);
      $this->addScenario('rcp8.5', true);
    }

    public function safeDown() {
        $this->remScenario('rcp2.6');
        $this->remScenario('rcp4.5');
        $this->remScenario('rcp6.0');
        $this->remScenario('rcp8.5');
    }

    private function addScenario($name, $visible=true) {
        return $this->insert('scenario', [
            'name' => str_replace($name,'.',''),
            'description' => $name,
            'visible' => $visible   
        ]);        
    }
    
    private function remScenario($name) {
        return $this->delete('scenario', ['name' => str_replace($name,'.','')]);     
    }
    
    
}


         