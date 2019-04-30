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
	
    public function actionHazards($mode='visible-only') {
       // returns list of all Hazards.
	   $result = Hazard::inqAllHazards('invisible'==$mode);
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 
   
    public function actionParameters($mode='visible-only') {
       // returns list of all Parameters.
	   $result = Parameter::inqAllParameters('invisible'==$mode);
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }    
   
    public function actionEpochs($mode='visible-only') {
       // returns list of all Epochs.
	   $result = Epoch::inqAllEpochs('invisible'==$mode); 
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }     
    
	public function actionScenarios($mode='visible-only') {
       // returns list of all Scenarios.
	   $result = Scenario::inqAllScenarios('invisible'==$mode);
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
	   }
	   $result['elevation_calculated'] = Gis::getCalculatedValue('elevation_mean', 'elev', $latitude, $longitude);
	   $result['elevation_iso_raster'] = Gis::getIsoElevation($latitude, $longitude);
	   $result['nearest_river'] = Gis::getDistanceToRiver($latitude, $longitude);
	   $result['nearest_city'] = Gis::getDistanceToCity($latitude, $longitude);
       $result['country'] = Gis::getCountry($latitude, $longitude);	   
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
   

}


