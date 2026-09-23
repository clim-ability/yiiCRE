<?php

use yii\db\Migration;

class m171220_170843_add_parameter_to_table extends Migration {

    public function safeUp() {
      $this->addParameter('mean', true);
      $this->addParameter('pctl15', false);
      $this->addParameter('pctl85', false);
    }

    public function safeDown() {
        $this->remParameter('mean');
        $this->remParameter('pctl15');
        $this->remParameter('pctl85');
    }

    private function addParameter($name, $visible=true) {
        return $this->insert('parameter', [
            'name' => $name,
            'description' => $name,
            'visible' => $visible   
        ]);        
    }
    
    private function remParameter($name) {
        return $this->delete('parameter', ['name' => $name]);     
    }
    
    
}


         