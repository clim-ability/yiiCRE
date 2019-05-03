<?php

use yii\db\Schema;
use yii\db\Migration;

class m190503_120124_add_color_to_hazards extends Migration
{

    public function init()
    {
        $this->db = 'pgsql_cre';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn("hazard", "color_min", "varchar(15)"); 
		$this->addColumn("hazard", "color_max", "varchar(15)");
		$this->update("hazard", ["color_min", "color_max"], "name='cddp'", ["#6dce70","#724702"]);
        $this->update("hazard", ["color_min", "color_max"], "name='hd'", ["#f2a9ad","#ea0914"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='fd'", ["#b7e3e8","#09d7ea"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='tr'", ["#f2a9ad","#ea0914"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='rr20'", ["#d9d9f4","#0202f4"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='rr_winter'", ["#e3d7f4F","#7e29f4"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='rr_summer'", ["#dcf9db","#06b203"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='hq'", ["#f9d1dd","#d80444"]);	
        $this->update("hazard", ["color_min", "color_max"], "name='height'", ["#cae03e","#f2cd8a"]);		
    }

    public function safeDown()
    {
        $this->dropColumn("hazard", "color_min");
	    $this->dropColumn("hazard", "color_max");
    }
}
