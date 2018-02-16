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
	   $hazard = Hazard::findBy($hazard);
       $epoch = Epoch::findBy($epoch);
	   $scenario = Scenario::findBy($scenario);
	   $parameter = Parameter::findBy($parameter);
	   $table = 'cddp_mean_rcp45_2021-2050_minus_knp';
       $result = Gis::getRasterValue($table, 47.99, 7.85);
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }
   
   public function actionHazardValues($latitude, $longitude, $epoch='', $scenario='', $parameter='mean', $resolution=0.1) 
   {
	   $hazards = $result = Hazard::inqAllHazards();
       $epoch = Epoch::findBy($epoch);
       $result = Gis::getRasterValue('cddp_mean_rcp45_2021-2050_minus_knp', 47.99, 7.85);
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }    
   

}


/**
 
  SELECT value/area as value, std , dlongitude, dlatitude FROM
(SELECT SUM(ST_Area(ST_Intersection(geom, ellipse))) as area, SUM(cddp*ST_Area(ST_Intersection(geom, ellipse))) AS value, STDDEV(cddp) as std,  
        regr_slope(cddp, ST_X(ST_Centroid(ST_Transform(geom, 4326)))) as dlongitude, regr_slope(cddp, ST_Y(ST_Centroid(ST_Transform(geom, 4326)))) as dlatitude
  FROM public.cddp_mean_rcp45_2021_2050_minus_knp,
(SELECT ST_Buffer(ST_Transform(ST_Translate(ST_SetSRID(ST_MakePoint(0, 0),4326)
       ,7.85,47.983333), 900915), AVG(sqrt(1.2*ST_Area(ST_SetSRID(geom, 900915))))) AS ellipse
      FROM public.cddp_mean_rcp45_2021_2050_minus_knp) AS foo 
  WHERE ST_Intersects(geom, ellipse)) AS sum
  
  
  
  8:X:Longitude
  48:Y:Latitude
  
 
 */