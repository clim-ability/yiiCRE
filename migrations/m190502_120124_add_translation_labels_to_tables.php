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
        $this->addColumn("hazard", "label_en", "varchar(255)");
		$this->renameColumn("hazard", "description", "description_en"); 
        $this->addColumn("hazard", "label_de", "varchar(255)");
		$this->addColumn("hazard", "description_de", "varchar(4095)");
        $this->addColumn("hazard", "label_fr", "varchar(255)");
		$this->addColumn("hazard", "description_fr", "varchar(4095)");	

        $this->addColumn( "epoch", "label_en","varchar(255)");
		$this->renameColumn("epoch", "description", "description_en"); 
        $this->addColumn( "epoch", "label_de", "varchar(255)");
		$this->addColumn( "epoch", "description_de", "varchar(4095)");
        $this->addColumn( "epoch", "label_fr", "varchar(255)");
		$this->addColumn( "epoch", "description_fr", "varchar(4095)");	

        $this->addColumn("scenario", "label_en", "varchar(255)");
		$this->renameColumn("scenario", "description", "description_en"); 
        $this->addColumn("scenario", "label_de", "varchar(255)");
		$this->addColumn("scenario", "description_de", "varchar(4095)");
        $this->addColumn("scenario", "label_fr", "varchar(255)");
		$this->addColumn("scenario", "description_fr", "varchar(4095)");			
    }

    public function safeDown()
    {
        $this->dropColumn("hazard", "label_en");
		$this->renameColumn("hazard", "description_en", "description");
        $this->dropColumn("hazard", "label_de");
        $this->dropColumn("hazard", "description_de");
        $this->dropColumn("hazard", "label_fr");
        $this->dropColumn("hazard", "description_fr");		
		
        $this->dropColumn("epoch", "label_en");
		$this->renameColumn("epoch", "description_en", "description");
        $this->dropColumn("epoch", "label_de");
        $this->dropColumn("epoch", "description_de");
        $this->dropColumn("epoch", "label_fr");
        $this->dropColumn("epoch", "description_fr");	

        $this->dropColumn("epoch", "label_en");
		$this->renameColumn("scenario", "description_en", "description");
        $this->dropColumn("epoch", "label_de");
        $this->dropColumn("epoch", "description_de");
        $this->dropColumn("epoch", "label_fr");
        $this->dropColumn("epoch", "description_fr");			
    }
}
