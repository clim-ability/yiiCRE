<?php

use yii\db\Migration;

class m171201_130843_add_epoch_to_table extends Migration {

    public function safeUp() {
      $this->addEpoch(2021,2050);
      $this->addEpoch(2041,2070);
      $this->addEpoch(2071,2100);
    }

    public function safeDown() {
           $this->remEpoch(2021,2050);
           $this->addEpoch(2041,2070);
           $this->addEpoch(2071,2100);
    }
    
    private function makeName($begin, $end) {
       return "{$begin}-{$end}"; 
    }
    
    private function addEpoch($begin, $end) {
        $name = $this->makeName($begin, $end);
        return $this->insert('epoch', [
            'name' => $name,
            'description' => $name,
            'year_begin' => $begin,
            'year_end' => $end,
            'visible' => true   
        ]);        
    }
    
    private function remEpoch($begin, $end) {
        $name = $this->makeName($begin, $end);
        return $this->delete('epoch', ['name' => $name]);     
    }
    
    
}


         