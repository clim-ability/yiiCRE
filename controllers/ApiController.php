<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;

//use app\models\UserSearch;
//use yii\web\NotFoundHttpException;
//use yii\data\ActiveDataProvider;
//use app\components\RssFormater;

/**
 * Class ResultController
 * @package app\controllers
 * @author Michael Kahle <michael,kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class ApiController extends Controller
{
   public function actionHazardValue($latitude, $longitude, $hazard='', $epoch='', $scenario='', $resolution=0.1) {
       
       $result = ['value'=>14.5, 'std'=>0.22];
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }
   
    
   

}
