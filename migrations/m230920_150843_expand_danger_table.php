<?php

use yii\db\Schema;
use yii\db\Migration;

class m190920_130843_create_danger_table extends Migration {

    public function safeUp() {
		$this->insert('danger', [ 'name' => 'cddp', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'hd', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'fd', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'tr', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'rr20', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'rr_winter', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'rr_summer', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'hq', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'height', 'visible' => false]);
		$this->insert('danger', [ 'name' => 'sd', 'visible' => false]);
    }

    public function safeDown() {
		$this->delete('danger', ['name' => 'cddp']);
		$this->delete('danger', ['name' => 'hd']);
		$this->delete('danger', ['name' => 'fd']);
		$this->delete('danger', ['name' => 'tr']);		
		$this->delete('danger', ['name' => 'rr20']);
		$this->delete('danger', ['name' => 'rr_winter']);
		$this->delete('danger', ['name' => 'rr_summer']);
		$this->delete('danger', ['name' => 'hq']);
		$this->delete('danger', ['name' => 'height']);
		$this->delete('danger', ['name' => 'sd']);
    }

}
