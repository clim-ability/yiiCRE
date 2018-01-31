<?php

use yii\db\Migration;

class m171220_150843_add_hazard_to_table extends Migration {


    public function safeUp() {
        $this->addHazard('cddp', 'consecutive dry days', true);
        $this->addHazard('hd', 'number of hot days', false);  // propably summer days 
        $this->addHazard('fd', 'number of frost days', true);
		$this->addHazard('tr', 'number of tropical nights', true);
        $this->addHazard('rr20', 'number of days with heavy rain (>20mm)', true);
        $this->addHazard('rr_winter', 'mean precipitation in winter', true);
        $this->addHazard('rr_summer', 'mean precipitation in summer', true);
        $this->addHazard('hq', 'flood risk return period', false);
        $this->addHazard('height', 'height above sea-level', false);
    }

    public function safeDown() {
        $this->remHazard('cddp');
        $this->remHazard('hd');
        $this->remHazard('fd');
	    $this->remHazard('tr');
        $this->remHazard('rr20');
        $this->remHazard('rr_winter');
        $this->remHazard('rr_summer');
        $this->remHazard('hq');
        $this->remHazard('height');
    }

    private function addHazard($name, $description, $visible = true) {
        return $this->insert('hazard', [
                    'name' => $name,
                    'description' => $description,
                    'visible' => $visible
        ]);
    }

    private function remHazard($name) {
        return $this->delete('hazard', ['name' => $name]);
    }

}
