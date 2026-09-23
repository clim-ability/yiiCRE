<?php

use yii\db\Schema;
use yii\db\Migration;

class m190920_120843_create_station_table extends Migration {

    public function safeUp() {
        $this->createTable('station', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
			'location' => 'geography(Point,4326)',
			'elevation' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'sd' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'tr' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'fd' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'rr20' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'rr_winter' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'rr_summer' => Schema::TYPE_DOUBLE . ' DEFAULT 0.0',
			'begin' => Schema::TYPE_INTEGER,
			'end' => Schema::TYPE_INTEGER,
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at' => 'timestamp with time zone NOT NULL DEFAULT now()',
        ]);
		
		$this->insert('station', [ 'name' => 'Karlsruhe-Rheinstetten', 'location' => 'POINT(8.3301 48.9726)',   'elevation' => 112.0, 
		                           'sd' => 58.9, 'tr' => 1.2, 'fd' => 60.6, 'rr20' => 6.8, 'rr_winter' => 92.9, 'rr_summer' => 249.5, 
		                           'visible' => true, 'begin'=>1971, 'end'=>2000]);
		$this->insert('station', [ 'name' => 'Straßburg-Entzheim', 'location' => 'POINT(7.6403 48.5494)',   'elevation' => 150.0, 
		                           'sd' => 50.7, 'tr' => 0.3, 'fd' => 68.7, 'rr20' => 3.4, 'rr_winter' => 111, 'rr_summer' => 201.1, 
		                           'visible' => true, 'begin'=>1971, 'end'=>2000]);
		$this->insert('station', [ 'name' => 'Freiburg', 'location' => 'POINT(7.8344 48.0233)',   'elevation' => 236.0, 
		                           'sd' => 54.2, 'tr' => 3.7, 'fd' => 52.3, 'rr20' => 7.2, 'rr_winter' => 180.6, 'rr_summer' => 292.2, 
		                           'visible' => true, 'begin'=>1971, 'end'=>2000]);
		$this->insert('station', [ 'name' => 'Basel-Binningen', 'location' => 'POINT(7.57109  47.5394)',   'elevation' => 316.0, 
		                           'sd' => 41.6, 'tr' => 2.0, 'fd' => 71, 'rr20' => 5.7, 'rr_winter' => 154, 'rr_summer' => 253, 
		                           'visible' => true, 'begin'=>1961, 'end'=>2009]);
		$this->insert('station', [ 'name' => 'Wangenbourg', 'location' => 'POINT(7.31 48.64)',   'elevation' => 465.0, 
		                           'sd' => 33.4, 'tr' => 1.4, 'fd' => 76.8, 'rr20' => 11.0, 'rr_winter' => 321, 'rr_summer' => 265.2, 
		                           'visible' => true, 'begin'=>1990, 'end'=>2010]);
		$this->insert('station', [ 'name' => 'Weinbiet (Pfälzer Wald)', 'location' => 'POINT(8.12133 49.3758)',   'elevation' => 553.0, 
		                           'sd' => 20.7, 'tr' => 2.7, 'fd' => 88.3, 'rr20' => 2.2, 'rr_winter' => 133.3, 'rr_summer' => 159.1, 
		                           'visible' => true, 'begin'=>1971, 'end'=>2000]);
		$this->insert('station', [ 'name' => 'Hornisgrinde', 'location' => 'POINT(8.2034 48.6136)',   'elevation' => 1119.0, 
		                           'sd' => 4.7, 'tr' => 0.08, 'fd' => 135.7, 'rr20' => 28.8, 'rr_winter' => 501.1, 'rr_summer' => 491.4, 
		                           'visible' => true, 'begin'=>1971, 'end'=>2000]);
		$this->insert('station', [ 'name' => 'Feldberg (Schwarzwald)', 'location' => 'POINT(8.0038 47.8749)',   'elevation' => 1490.0, 
		                           'sd' => 0.3, 'tr' => 0.07, 'fd' => 158.9, 'rr20' => 22.7, 'rr_winter' => 445.3, 'rr_summer' => 467.8, 
		                           'visible' => true, 'begin'=>1971, 'end'=>2000]);								   
								   
    }

    public function safeDown() {
        $this->dropTable('station');
    }

}
