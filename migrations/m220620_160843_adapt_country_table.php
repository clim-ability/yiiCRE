<?php

use yii\db\Schema;
use yii\db\Migration;

class m220620_160843_adapt_country_table extends Migration {

    public function safeUp() {
        $this->addColumn("country", "gis", "varchar(15)"); 
        $this->update("country", ["gis" => "D"], "short='de'");
        $this->update("country", ["gis" => "F"], "short='fr'");
        $this->update("country", ["gis" => "CH"], "short='ch'");
    }

    public function safeDown() {
        $this->dropColumn("country", "gis");
    }

}
