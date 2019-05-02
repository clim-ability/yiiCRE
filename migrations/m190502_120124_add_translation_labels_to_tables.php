<?php

use yii\db\Schema;
use yii\db\Migration;

class m190502_120124_add_translation_labels_to_tables extends Migration
{

    public function init()
    {
        $this->db = 'pgsql_cre';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn("label_en", "hazard", "varchar(255)");
		$this->renameColumn("hazard", "decription", "description_en"); 
        $this->addColumn("label_de", "hazard", "varchar(255)");
		$this->addColumn("description_de", "hazard", "varchar(4095)");
        $this->addColumn("label_fr", "hazard", "varchar(255)");
		$this->addColumn("description_fr", "hazard", "varchar(4095)");	

        $this->addColumn("label_en", "epoch", "varchar(255)");
		$this->renameColumn("epoch", "decription", "description_en"); 
        $this->addColumn("label_de", "epoch", "varchar(255)");
		$this->addColumn("description_de", "epoch", "varchar(4095)");
        $this->addColumn("label_fr", "epoch", "varchar(255)");
		$this->addColumn("description_fr", "epoch", "varchar(4095)");	

        $this->addColumn("label_en", "scenario", "varchar(255)");
		$this->renameColumn("scenario", "decription", "description_en"); 
        $this->addColumn("label_de", "scenario", "varchar(255)");
		$this->addColumn("description_de", "scenario", "varchar(4095)");
        $this->addColumn("label_fr", "scenario", "varchar(255)");
		$this->addColumn("description_fr", "scenario", "varchar(4095)");			
    }

    public function safeDown()
    {
        $this->dropColumn("label_en", "hazard");
		$this->renameColumn("hazard", "decription_en", "description");
        $this->dropColumn("label_de", "hazard");
        $this->dropColumn("description_de", "hazard");
        $this->dropColumn("label_fr", "hazard");
        $this->dropColumn("description_fr", "hazard");		
		
        $this->dropColumn("label_en", "epoch");
		$this->renameColumn("epoch", "decription_en", "description");
        $this->dropColumn("label_de", "epoch");
        $this->dropColumn("description_de", "epoch");
        $this->dropColumn("label_fr", "epoch");
        $this->dropColumn("description_fr", "epoch");	

        $this->dropColumn("label_en", "scenario");
		$this->renameColumn("scenario", "decription_en", "description");
        $this->dropColumn("label_de", "scenario");
        $this->dropColumn("description_de", "scenario");
        $this->dropColumn("label_fr", "scenario");
        $this->dropColumn("description_fr", "scenario");			
    }
}
