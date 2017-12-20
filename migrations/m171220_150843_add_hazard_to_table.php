<?php

use yii\db\Migration;

class m171220_150843_add_hazard_to_table extends Migration {

    public function safeUp() {
        $this->addHazard('cdd', 'consecutive dry days', true);
        $this->addHazard('hd', 'number of hot days', false);
        $this->addHazard('fd', 'number of frost days', true);
        $this->addHazard('rr20', 'number of days with heavy rain (>20mm)', true);
        $this->addHazard('prwin', 'mean precipitation in winter', false);
        $this->addHazard('prsum', 'mean precipitation in summer', false);
        $this->addHazard('hq', 'flood risk return period', false);
        $this->addHazard('height', 'height above sea-level', false);
    }

    public function safeDown() {
        $this->remHazard('cdd');
        $this->remHazard('hd');
        $this->remHazard('fd');
        $this->remHazard('rr20');
        $this->remHazard('prwin');
        $this->remHazard('prsum');
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
