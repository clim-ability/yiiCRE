<?php

use yii\db\Schema;
use yii\db\Migration;

class m190502_150124_modify_translation_labels extends Migration
{

    public function init()
    {
        $this->db = 'pgsql_cre';
        parent::init();
    }

    public function safeUp()
    {
		$this->dropColumn("hazard", "description_en");
		$this->addColumn("hazard", "description_en", Schema::TYPE_TEXT);
		$this->dropColumn("hazard", "description_de");
		$this->addColumn("hazard", "description_de", Schema::TYPE_TEXT);		
		$this->dropColumn("hazard", "description_fr");
		$this->addColumn("hazard", "description_fr", Schema::TYPE_TEXT);		

		$this->dropColumn("epoch", "description_en");
		$this->addColumn("epoch", "description_en", Schema::TYPE_TEXT);
		$this->dropColumn("epoch", "description_de");
		$this->addColumn("epoch", "description_de", Schema::TYPE_TEXT);		
		$this->dropColumn("epoch", "description_fr");
		$this->addColumn("epoch", "description_fr", Schema::TYPE_TEXT);		

		$this->dropColumn("scenario", "description_en");
		$this->addColumn("scenario", "description_en", Schema::TYPE_TEXT);
		$this->dropColumn("scenario", "description_de");
		$this->addColumn("scenario", "description_de", Schema::TYPE_TEXT);		
		$this->dropColumn("scenario", "description_fr");
		$this->addColumn("scenario", "description_fr", Schema::TYPE_TEXT);	
		
    }

    public function safeDown()
    {
		$this->dropColumn("hazard", "description_en");
		$this->addColumn("hazard", "description_en", "varchar(4095)");
		$this->dropColumn("hazard", "description_de");
		$this->addColumn("hazard", "description_de", "varchar(4095)");		
		$this->dropColumn("hazard", "description_fr");
		$this->addColumn("hazard", "description_fr", "varchar(4095)");		

		$this->dropColumn("epoch", "description_en");
		$this->addColumn("epoch", "description_en", "varchar(4095)");
		$this->dropColumn("epoch", "description_de");
		$this->addColumn("epoch", "description_de", "varchar(4095)");		
		$this->dropColumn("epoch", "description_fr");
		$this->addColumn("epoch", "description_fr", "varchar(4095)");		

		$this->dropColumn("scenario", "description_en");
		$this->addColumn("scenario", "description_en", "varchar(4095)");
		$this->dropColumn("scenario", "description_de");
		$this->addColumn("scenario", "description_de", "varchar(4095)");		
		$this->dropColumn("scenario", "description_fr");
		$this->addColumn("scenario", "description_fr", "varchar(4095)");
		
    }
}
