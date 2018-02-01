<?php

use yii\db\Migration;

class m180131_140843_add_layer_to_table extends Migration {

    private static function usedParameter()
	{ return ['meXan', 'pctl15', 'pctl85']; }

    private static function usedHazards()
	{  return ['cddp', 'fd', 'rr20', 'rr_summer', 'rr_winter', 'tr']; }

    private static function usedEpochs()
	{ return ['2021-2050', '2041-2070', '2071-2100']; }	
	
    private static function usedScenarios()
	{ return ['rcp45', 'rcp85']; }

	
    public function safeUp() {
       $this->handleCombinations(true);
    }

    public function safeDown() {
       $this->handleCombinations(false);
    }
    
	private function handleCombinations($add = true)
	{
	  foreach($this::usedParameter() as $paramName)
	  {
		$paramItem = $this->findParam($paramName);  
        foreach($this::usedHazards() as $hazardName)
		{
		  $hazardItem = $this->findHazard($hazardName);
          foreach($this::usedScenarios() as $scenarioName)
		  {		
		    $scenarioItem = $this->findScenario($scenarioName); 
            foreach($this::usedEpochs() as $epochName)
		    {	
			   $epochItem = $this->findEpoch($epochName);
               if ($add) 
			   {				   
                 $this->addLayer($hazardItem, $paramItem , $epochItem, $scenarioItem);
			   } else {
			     $this->remLayer($hazardName, $paramName , $epochName, $scenarioName);
			   }
		    }
	      }
		}
	  }		
	}
	
    private function makeName($hazard, $param, $epoch, $scenario) {
       return $hazard."_".$param."_".$scenario."_".$epoch."_minus_knp"; 
    }
    
    private function addLayer($hazard, $param, $epoch, $scenario, $visible=true) {
        $name = $this->makeName($hazard['name'], $param['name'], $epoch['name'], $scenario['name']);
  /*    
 	  return $this->insert('layer', [
            'name' => $name,
            'description' => $name,
            'year_begin' => $begin,
            'year_end' => $end,
            'visible' => $visible   
        ]);
*/
      return true;        
    }
    
    private function remLayer($hazard, $param, $epoch, $scenario) {
        $name = $this->makeName($hazard, $param, $epoch, $scenario);
		if($this->checkTableExists($name)) {
		   echo " Y \n";	
           return $this->delete('layer', ['name' => $name]);   
		}	
		echo $name;
        echo " N \n";		
		return true;
    }
 
	
	private function checkTableExists($name)
	{
		$connection = \Yii::$app->db2;
        $sql = "SELECT to_regclass('".$name."') AS tablename";
        $command = $connection->createCommand($sql);
        $table = $command->queryOne();
        return ('"'.$name.'"' === $table['tablename']);
	} 
 
	private function findHazard($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM hazard WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 
	private function findParam($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM parameter WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	}  
	private function findScenario($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM scenario WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	}  
 	private function findEpoch($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM epoch WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 
}


    /*
            'hazard_id' => 'integer NOT NULL REFERENCES hazard(id) ON DELETE CASCADE',
            'epoch_id' => 'integer NOT NULL REFERENCES epoch(id) ON DELETE CASCADE',
            'scenario_id' => 'integer NOT NULL REFERENCES scenario(id) ON DELETE CASCADE',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(4095)',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'variable' => Schema::TYPE_STRING . '(255) NOT NULL',
            'layer' => Schema::TYPE_STRING . '(255) NOT NULL',
            'srid' => Schema::TYPE_INTEGER . ' DEFAULT 4326',
            'relative' => 'integer REFERENCES layer(id)',
            'rastered' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            'resolution' => Schema::TYPE_DOUBLE. ' DEFAULT 4326',
     */     