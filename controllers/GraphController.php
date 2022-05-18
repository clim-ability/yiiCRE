<?php
/**
 * @copyright 2021 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Danger;
use app\models\Risk;

use app\models\Epoch;
use app\models\Hazard;
use app\models\Parameter;
use app\models\Scenario;
use app\models\Station;
use app\models\Sector;
use app\models\Gis;
use app\models\User;
use app\modules\translation\models\Language;
//use yii\web\NotFoundHttpException;
//use yii\data\ActiveDataProvider;
//use app\components\RssFormater;

/**
 * Class GraphController
 * @package app\controllers
 * @author Michael Kahle <michael.kahle@geography.uni-freiburg.de>
 * @since 2.0
 */
class GraphController extends Controller
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
	
    public function actionFullGraph($language='en', $current=0, $sector=NULL) {
       // returns svgs, nodes and links for all dangers, impacts and adaptions
	   //$result = Hazard::inqAllHazards('invisible'==$mode);
	   //$result = array_map(function($e) { $e->label = $e->name; return $e; },$result);
	   $svgs = [];
       $nodes = [];
	   $links = [];

	   $nodes[] = ['name'=>'root' , '_color'=>'#888888', 'id'=>0, '_cssClass'=>'root', '_labelClass'=>'root', 'type'=>'root']; 
	   $dangers = Danger::inqAllDangers();
	   foreach($dangers as $danger) {
		  $idDan = crc32('Danger:'.$danger['id'].':'.$danger['name']); 
          $idPos = crc32('Danger:'.$danger['id'].':posImpacts');
          $idNeg = crc32('Danger:'.$danger['id'].':negImpacts');
          $idAdp = crc32('Danger:'.$danger['id'].':Adaptions');
          if($current==0 or in_array($current ,[$idDan,$idPos,$idNeg,$idAdp])) {
            $node = ['name'=>$danger['name'], '_color'=>'#00aaff', 'id'=>$idDan, '_cssClass'=>'danger', '_labelClass'=>'danger', 'type'=>'danger'];
		    $nodes[] = $node;
		    $links[] = ['sid'=>0, 'tid'=>$idDan];
	      }
		  
		  if($current==$idPos or $current==$idDan) {
            $node = ['name'=>'Impacts(+)', '_color'=>'#00aa00', 'id'=>$idPos, '_cssClass'=>'impacts', '_labelClass'=>'impacts', 'type'=>'impacts'];
		    $nodes[] = $node;
		    $links[] = ['sid'=>$idDan, 'tid'=>$idPos];

			$risks = Risk::inqRisksByDangerAndNegative($danger['id'], false, $sector);
			foreach($risks as $risk) {
 			  $idRisk = crc32('Risk:'.$risk['id'].':'.$risk['name'].':'.$risk['negative']);
 			  if($current==$idPos or $current==$idRisk) {
				$node = ['name'=>$risk['name'], '_color'=>'#00ff00', 'id'=>$idRisk, '_cssClass'=>'posRisks', '_labelClass'=>'posRisks', 'type'=>'posRisks'];
				$nodes[] = $node;
				$links[] = ['sid'=>$idPos, 'tid'=>$idRisk];
			  } 
			}			

		  } 
		  
		  if($current==$idNeg or $current==$idDan) {
            $node = ['name'=>'Impacts(-)', '_color'=>'#aa0000', 'id'=>$idNeg, '_cssClass'=>'impacts', '_labelClass'=>'impacts', 'type'=>'impacts'];
		    $nodes[] = $node;
		    $links[] = ['sid'=>$idDan, 'tid'=>$idNeg];

			$risks = Risk::inqRisksByDangerAndNegative($danger['id'], true, $sector);
			foreach($risks as $risk) {
 			  $idRisk = crc32('Risk:'.$risk['id'].':'.$risk['name'].':'.$risk['negative']);
 			  if($current==$idNeg or $current==$idRisk) {
				$node = ['name'=>$risk['name'], '_color'=>'#ff0000', 'id'=>$idRisk, '_cssClass'=>'negRisks', '_labelClass'=>'negRisks', 'type'=>'negRisks'];
				$nodes[] = $node;
				$links[] = ['sid'=>$idNeg, 'tid'=>$idRisk];
			  } 
			}

		  } 
		  
		  if($current==$idAdp or $current==$idDan) {
            $node = ['name'=>'Adaptions', '_color'=>'#0000aa', 'id'=>$idAdp, '_cssClass'=>'adapts', '_labelClass'=>'adapts', 'type'=>'adapts'];
		    $nodes[] = $node;
		    $links[] = ['sid'=>$idDan, 'tid'=>$idAdp];
		  }		  
	   }



       $result = [ 'svgs'=>$svgs, 'nodes'=>$nodes, 'links'=>$links ];	   
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 	
	
   
  

}


