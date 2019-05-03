<?php

use yii\db\Schema;
use yii\db\Migration;

class m190503_120124_add_colors_to_hazards extends Migration
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
		$this->update("hazard", ["color_min" => "#6dce70", "color_max" => "#724702"], "name='cddp'");
        $this->update("hazard", ["color_min" => "#f2a9ad", "color_max" => "#ea0914"], "name='hd'");	
        $this->update("hazard", ["color_min" => "#b7e3e8", "color_max" => "#09d7ea"], "name='fd'");	
        $this->update("hazard", ["color_min" => "#f2a9ad", "color_max" => "#ea0914"], "name='tr'");	
        $this->update("hazard", ["color_min" => "#d9d9f4", "color_max" => "#0202f4"], "name='rr20'");	
        $this->update("hazard", ["color_min" => "#e3d7f4", "color_max" => "#7e29f4"], "name='rr_winter'");	
        $this->update("hazard", ["color_min" => "#dcf9db", "color_max" => "#06b203"], "name='rr_summer'");	
        $this->update("hazard", ["color_min" => "#f9d1dd", "color_max" => "#d80444"], "name='hq'");	
        $this->update("hazard", ["color_min" => "#cae03e", "color_max" => "#f2cd8a"], "name='height'");		
    }

    public function safeDown()
    {
        $this->dropColumn("hazard", "color_min");
	    $this->dropColumn("hazard", "color_max");
    }
}
