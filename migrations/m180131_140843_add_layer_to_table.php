<?php

use yii\db\Migration;

class m180131_140843_add_layer_to_table extends Migration {

    private static function usedParameter()
	{ return ['mean', 'pctl15', 'pctl85']; }

    private static function usedHazards()
	{  return ['cddp', 'fd', 'rr20', 'rr_summer', 'rr_winter', 'tr']; }

    private static function usedEpochs()
	{ return ['1970-2000', '2021-2050', '2041-2070', '2071-2100']; }	
	
    private static function usedScenarios($add)
	{ if ($add)
		{ return ['', 'rcp45', 'rcp85']; } 
	  else
		{ return ['rcp45', 'rcp85', '']; }   
	}
	
    public function safeUp() {
       $this->handleCombinations(true);
    }

    public function safeDown() {
       $this->handleCombinations(false);
    }
    
	private function handleCombinations($add = true)
	{
     foreach($this::usedScenarios($add) as $scenarioName)
	 {		
	  $scenarioItem = $this->findScenario($scenarioName); 		
	  foreach($this::usedParameter() as $paramName)
	  {
		$paramItem = $this->findParam($paramName);  
        foreach($this::usedHazards() as $hazardName)
		{
		  $hazardItem = $this->findHazard($hazardName);
            foreach($this::usedEpochs() as $epochName)
		    {	
			   $epochItem = $this->findEpoch($epochName);
               if ($add) 
			   {				   
                 $this->addLayer($hazardItem, $paramItem , $epochItem, $scenarioItem);
			   } else {
			     $this->remLayer($hazardItem, $paramItem , $epochItem, $scenarioItem);
			   }
		    }
	      }
		}
	  }		
	}
	
    private function makeName($hazard, $param, $epoch, $scenario) {
		if (empty($scenario)) {
		   return $hazard['name']."_".$param['name']."_".$epoch['name']."_knp";	
		}
       return $hazard['name']."_".$param['name']."_".$scenario['name']."_".$epoch['name']."_minus_knp"; 
    }
    
    private function addLayer($hazard, $param, $epoch, $scenario, $visible=true) {
		$relativeTo = null;
		if (is_null($scenario)) {
		   $visible = false;
		} else {
		   $meanItem = $this->findParam('mean'); 
		   $epochItem = $this->findEpoch('1970-2000');
		   $relativeName = $this->makeName($hazard, $meanItem, $epochItem, null);
		   $layerItem = $this->findLayer($relativeName);
		   var_dump($layerItem);
		   echo " relative Layer ".$relativeName." \n";
		   if (!is_null($layerItem)) {
		      $relativeTo = $layerItem['id'];
		   }
		}
        $name = $this->makeName($hazard, $param, $epoch, $scenario);
		$srid = 25832;
		if($this->checkTableExists($name)) {
   	      return $this->insert('layer', [
            'name' => $name,
            'description' => $name,
            'hazard_id' => $hazard['id'],
            'parameter_id' => $param['id'],
            'epoch_id' => $epoch['id'],
            'scenario_id' => $scenario['id'],
            'variable' => $hazard['name'],
            'layer' => $name,
            'srid' => $srid,
            'relative' => $relativeTo,
            'rastered' => true,
            'visible' => $visible   
            ]);
        } 
      return true;        
    }
    
    private function remLayer($hazard, $param, $epoch, $scenario) {
        $name = $this->makeName($hazard, $param, $epoch, $scenario);
        return $this->delete('layer', ['name' => $name]);   
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
	private function findLayer($name)
	{
		$connection = \Yii::$app->db;
        $sql = "SELECT * FROM layer WHERE name = '".$name."' ORDER BY id DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        return $result;
	} 
	
}


    