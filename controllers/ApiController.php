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
    public function actionHazards($mode='visible-only') {
       // returns list of all Hazards.
	   $result = Hazard::inqAllHazards();
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 
   
    public function actionParameters($mode='visible-only') {
       // returns list of all Parameters.
	   $result = Parameter::inqAllParameters();
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }    
   
    public function actionEpochs($mode='visible-only') {
       // returns list of all Epochs.
	   $result = Epoch::inqAllEpochs(); 
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }     
    
	public function actionScenarios($mode='visible-only') {
       // returns list of all Scenarios.
	   $result = Scenario::inqAllScenarios();
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
		  $relHazard = $hazard['name'].'_'.$epoch['name'].'_delta';
          $result[$relHazard] = Gis::getRasterValue($table, $hazard['name'], $latitude, $longitude);
		  
	      $refEpoch = Epoch::findBy('1970-2000');
	      $refParameter = Parameter::findBy('mean');
	      $table = Gis::getRasterTable($hazard, $refParameter, $refEpoch, null);
		   if(is_string($table)) {
		      $refHazard = $hazard['name'].'_'.$refEpoch['name'].'_absolute';
              $result[$refHazard] = Gis::getRasterValue($table, $hazard['name'], $latitude, $longitude);
		   }
	   }
	   $result['elevation_raster'] = Gis::getRasterValue('elevation_mean', 'elev', $latitude, $longitude);
	   $result['elevation_iso'] = Gis::getIsoElevation($latitude, $longitude);
	   $result['distance_river'] = Gis::getDistanceToRiver($latitude, $longitude);
	   $result['distance_city'] = Gis::getDistanceToCity($latitude, $longitude);
	   //$result['table'] = $table;
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }
   
   public function actionHazardValues($latitude, $longitude, $epoch='', $scenario='', $parameter='mean', $resolution=0.1) 
   {
	   $hazards = Hazard::inqAllHazards();
       $epoch = Epoch::findBy($epoch);
	   $scenario = Scenario::findBy($scenario);
	   $parameter = Parameter::findBy($parameter);
       $result = Gis::getRasterValue('cddp_mean_rcp45_2021-2050_minus_knp', 47.99, 7.85);
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }    
   

}


