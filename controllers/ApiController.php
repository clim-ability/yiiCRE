<?php
/**
 * @copyright 2018 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Epoch;
use app\models\Hazard;
use app\models\Parameter;
use app\models\Scenario;
use app\models\Gis;
use app\modules\translation\models\Language;
//use yii\web\NotFoundHttpException;
//use yii\data\ActiveDataProvider;
//use app\components\RssFormater;

/**
 * Class ApiController
 * @package app\controllers
 * @author Michael Kahle <michael.kahle@geography.uni-freiburg.de>
 * @since 2.0
 */
class ApiController extends Controller
{
	/*
	public function behaviors()
    {
    $behaviors = parent::behaviors();
    $behaviors['corsFilter'] = [
        'class' => \yii\filters\Cors::className(),
        'cors' => [
            'Origin'                           => ["*"],
            'Access-Control-Request-Method'    => ['POST', 'GET'],
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Max-Age'           => 3600,
        ],
    ];
    return $behaviors;
    }
	*/
	
    public function actionLanguages($mode='visible-only') {
       // returns list of all Languages.
	   //$result = Hazard::inqAllHazards('invisible'==$mode);
	   //$result = array_map(function($e) { $e->label = $e->name; return $e; },$result);
       $result = [['name' => 'en', 'label' => 'english', 'description'=>'english/englisch/anglaise'],
                  ['name' => 'de', 'label' => 'deutsch', 'description'=>'german/deutsch/alemagne'], 
                  ['name' => 'fr', 'label' => 'francaise', 'description'=>'french/französisch/francaise'], 	   
	             ];	   
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 	
	
    public function actionHazards($mode='visible-only', , $language='en') {
       // returns list of all Hazards.
	   $result = Hazard::inqAllHazards('invisible'==$mode);
	   $result = array_map(function($e) { 
	      $e->label = \Yii::t('Hazard:name', $e->name, [], $language);
		  $e->description = \Yii::t('Hazard:description', $e->name, [], $language);
		  return $e; 
		  } ,$result); 
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 
   
    public function actionParameters($mode='visible-only') {
       // returns list of all Parameters.
	   $result = Parameter::inqAllParameters('invisible'==$mode);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }    
   
    public function actionEpochs($mode='visible-only') {
       // returns list of all Epochs.
	   $result = Epoch::inqAllEpochs('invisible'==$mode);
       $result = array_map(function($e) { $e->label = $e->name; return $e; },$result);	   
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }     
    
	public function actionScenarios($mode='visible-only') {
       // returns list of all Scenarios.
	   $result = Scenario::inqAllScenarios('invisible'==$mode);
	   $result = array_map(function($e) { $e->label = $e->name; return $e; },$result);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 
   
   
   public function actionHazardValue($latitude, $longitude, $hazard='', $epoch='', $scenario='', $parameter='mean', $resolution=0.1) 
   {
	   $result = [];
	   $hazard = Hazard::findBy($hazard);
       $epoch = Epoch::findBy($epoch);
	   $scenario = Scenario::findBy($scenario);
	   $parameter = Parameter::findBy($parameter);
	   $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);
	   if(is_string($table)) {
		  $result['layer'] = $table;
		  $result['layer_'.$hazard['name']] = $table;
		  $relHazard = $hazard['name'].'_'.$epoch['name'].'_delta';
          $result[$relHazard.'_calculated'] = Gis::getCalculatedValue($table, $hazard['name'], $latitude, $longitude);
		  $result[$relHazard.'_raster'] = Gis::getRasterValue($table, $hazard['name'], $latitude, $longitude);
	      $refEpoch = Epoch::findBy('1970-2000');
	      $refParameter = Parameter::findBy('mean');
	      $table = Gis::getRasterTable($hazard, $refParameter, $refEpoch, null);
		   if(is_string($table)) {
		      $refHazard = $hazard['name'].'_'.$refEpoch['name'].'_absolute';
              $result[$refHazard] = Gis::getCalculatedValue($table, $hazard['name'], $latitude, $longitude);
		   }
	   }
	   $result['elevation_calculated'] = Gis::getCalculatedValue('elevation_mean', 'elev', $latitude, $longitude);
	   $result['elevation_iso_raster'] = Gis::getIsoElevation($latitude, $longitude);
	   $result['nearest_river'] = Gis::getDistanceToRiver($latitude, $longitude);
	   $result['nearest_city'] = Gis::getDistanceToCity($latitude, $longitude);
	   $result['country'] = Gis::getCountry($latitude, $longitude);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }
   
   public function actionHazardExtremes($hazard='', $epoch='', $scenario='')
   {
      $hazardsList = [];
	  $inclInvisible = false;
	  //$parameters = ['pctl15', 'pctl85'];
	  $parameters = ['mean'];
	  //$parameters = ['pctl15', 'pctl85', 'mean'];
	  //$parameters = ['mean', 'pctl15', 'pctl85', 'mean'];
	  $hazards = Hazard::inqAllHazards($inclInvisible);
	  if ('' != $hazard) {
	    $hazards = [Hazard::findBy($hazard)];
	  }
	  $epochs = Epoch::inqAllEpochs( $inclInvisible);
	  if ('' != $epoch) {
	    $epochs = [Epoch::findBy($epoch)];
	  }
	  $scenarios = Scenario::inqAllScenarios( $inclInvisible);
	  if ('' != $scenario) {
	    $scenarios = [Scenario::findBy($scenario)];
	  }
      foreach($parameters as $parameter)
	  {	 
	   $parameter = Parameter::findBy($parameter);
	   foreach($hazards as $hazard)
	   {
	    foreach($epochs as $epoch)
	    {
	     foreach($scenarios as $scenario)
	     {
	       $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);	
           $hazardsList[$table] = $hazard['name'];
	     }
	    }
	   }
	  } 
	  /*
	  foreach($hazardsList as $table=>$hazard) {
		var_dump($table);
		var_dump($hazard);
		echo " ";
	  }
      var_dump($hazardsList);
      */	  
	  $result = Gis::getHazardExtremes($hazardsList);
	  \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return $result;	  
   }
   
   public function actionHazardTimeChart($hazard, $latitude = null, $longitude = NULL)
   {
	  $hazard = Hazard::findBy($hazard);
	  $parameter='mean';
	  $parameter = Parameter::findBy($parameter);
	  $inclInvisible = false;
	  $epochs = Epoch::inqAllEpochs( $inclInvisible);
	  $scenarios = Scenario::inqAllScenarios( $inclInvisible);
	  $resultList = [];
      foreach($scenarios as $scenario)
	  {
		$resultList[$scenario['name']] = []; 
		$resultList[$scenario['name']]['1970-2000'] = ['value' => 0.0];
	    foreach($epochs as $epoch)
	    {
	     $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);	
         $resultList[$scenario['name']][$epoch['name']] = Gis::getCalculatedValue($table, $hazard['name'], $latitude, $longitude);
	    }
	  }	
	  $result = [];
	  foreach($resultList as $szenario=>$data) {
		 $column = [$szenario];
 		  foreach($data as $epoch=>$values) {
			 $column[] = $values['value'];  
		  }	  
		  $result[] = $column;
	  }	  
	  //$result =  $resultList;
	  \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return $result;	  
   }
   
   public function actionHazardValues($latitude, $longitude, $epoch='', $scenario='', $parameter='mean', $resolution=0.1) 
   {
	   $result = [];
	   $hazards = Hazard::inqAllHazards();
       $epoch = Epoch::findBy($epoch);
	   $scenario = Scenario::findBy($scenario);
	   $parameter = Parameter::findBy($parameter);
	   foreach($hazards as $hazard)
	   {
		 $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);
	     if(is_string($table)) {
		  $result['layer_'.$hazard['name']] = $table;
		  //$relHazard = $hazard['name'].'_'.$epoch['name'].'_delta';
		  //$relHazard = str_replace('-', '_', $relHazard);
		  $relHazard = $hazard['name'].'_delta';
          $result[$relHazard.'_calculated'] = Gis::getCalculatedValue($table, $hazard['name'], $latitude, $longitude);
		  $result[$relHazard.'_raster'] = Gis::getRasterValue($table, $hazard['name'], $latitude, $longitude);
	      $refEpoch = Epoch::findBy('1970-2000');
	      $refParameter = Parameter::findBy('mean');
	      $table = Gis::getRasterTable($hazard, $refParameter, $refEpoch, null);
		   if(is_string($table)) {
		      //$refHazard = $hazard['name'].'_'.$refEpoch['name'].'_absolute';
			  //$refHazard = str_replace('-', '_', $refHazard);
			  $refHazard = $hazard['name'].'_absolute';
              $result[$refHazard] = Gis::getCalculatedValue($table, $hazard['name'], $latitude, $longitude);
		   }
	     } 
	   }
	   $result['elevation_calculated'] = Gis::getCalculatedValue('elevation_mean', 'elev', $latitude, $longitude);
	   $result['elevation_iso_raster'] = Gis::getIsoElevation($latitude, $longitude);
	   $result['nearest_river'] = Gis::getDistanceToRiver($latitude, $longitude);
	   $result['nearest_city'] = Gis::getDistanceToCity($latitude, $longitude);
       $result['country'] = Gis::getCountry($latitude, $longitude);	 
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }    
   
   public function actionHazardGeom($hazard='', $epoch='', $scenario='', $parameter='mean', $resolution=0.1, $bbox='') 
   {
	   $hazard = Hazard::findBy($hazard);
       $epoch = Epoch::findBy($epoch);
	   $scenario = Scenario::findBy($scenario);
	   $parameter = Parameter::findBy($parameter);
	   $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);
	   $features = [];
	   if(is_string($table)) {
		  $rows = Gis::getHazardGeometry($table, $hazard['name'], $bbox);
          foreach($rows as $row) {
		     $feature = ['type' => 'Feature', 'geometry' => json_decode($row['geojson'], true), 'properties' => ['value' => $row['value']]];
             $features[] = $feature;
		  }
	   }
       $result = ['type' => 'FeatureCollection', 'features'  => $features];
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }   
 
   public function actionHazardsStatistic($epoch='', $scenario='', $absolute='')
   {
      $hazardsList = [];
	  $inclInvisible = false;
	  $parameters = ['mean'];
	  $hazards = Hazard::inqAllHazards($inclInvisible);
	  $epochs = Epoch::inqAllEpochs( $inclInvisible);
	  if ('' != $epoch) {
	    $epochs = [Epoch::findBy($epoch)];
	  }
	  $scenarios = Scenario::inqAllScenarios( $inclInvisible);
	  if ('' != $scenario) {
	    $scenarios = [Scenario::findBy($scenario)];
	  }
      foreach($parameters as $parameter)
	  {	 
	   $parameter = Parameter::findBy($parameter);
	   foreach($hazards as $hazard)
	   {
	    foreach($epochs as $epoch)
	    {
	     foreach($scenarios as $scenario)
	     {
	       $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);	
           $hazardsList[$table] = $hazard['name'];
	     }
	    }
	   }
	  } 

	  $result = [];
	  $collected = Gis::getHazardsStatistic($hazardsList, !empty($absolute));
	  foreach($collected as $row) {
		$new = [];   
		$new['latitude']  = floatval($row['latitude']);
		$new['longitude'] = floatval($row['longitude']);  
		$values = [];
	    foreach($hazards as $hazard) {
			$values[] = [ 'hazard' => $hazard['name'].'_plus', 'value' => floatval($row[$hazard['name'].'_plus']) ]; 
			$values[] = [ 'hazard' => $hazard['name'].'_minus', 'value' => floatval($row[$hazard['name'].'_minus']) ]; 
		}
		$values[] = [ 'hazard' => 'none', 'value' => 0.01 ]; 
		$new['values'] = $values;
		$result[] = $new;
	  }	
	  \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return $result;	  
   }
  

}


