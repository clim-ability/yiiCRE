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
use app\models\Adaption;

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
       $subKnotsAlways = true;

	   $svgs = [];
       $nodes = [];
	   $links = [];

	   $nodes[] = ['name'=>'root' , '_color'=>'#888888', 'id'=>0, '_cssClass'=>'root', '_labelClass'=>'root', 'type'=>'root', 'hasChildren'=>true]; 
	   $dangers = Danger::inqAllDangers();
	   foreach($dangers as $danger) {
		  $idDan = crc32('Danger:'.$danger['id'].':'.$danger['name']); 
          $idPos = crc32('Danger:'.$danger['id'].':posImpacts');
          $idNeg = crc32('Danger:'.$danger['id'].':negImpacts');
          $idAdp = crc32('Danger:'.$danger['id'].':Adaptions');

		  
          $negativeExists = false;
		  if(in_array($current ,[$idDan,$idPos,$idNeg,$idAdp,0])) {
			$risks = Risk::inqRisksByDangerAndNegative($danger['id'], false, $sector);
			foreach($risks as $risk) {
 			  $idRisk = crc32('Risk:'.$risk['id'].':'.$risk['name'].':'.$risk['negative']);
			  $negativeExists = true;
 			  if($current==$idPos or $current==$idRisk) {
				$node = ['name'=>$risk['name'], '_color'=>'#00ff00', 'id'=>$idRisk, '_cssClass'=>'posRisks', '_labelClass'=>'posRisks', 'type'=>'posRisks', 'hasChildren'=>false];
				$nodes[] = $node;
				$links[] = ['sid'=>$idPos, 'tid'=>$idRisk];
			  } 
			}			
		  } 
		  //if($exists and ($current==$idPos or $current==$idDan or $subKnotsAlways)) {
		  if($negativeExists and in_array($current ,[$idDan,$idPos,$idNeg,$idAdp])) {	  
            $node = ['name'=>'Impacts(+)', '_color'=>'#00aa00', 'id'=>$idPos, '_cssClass'=>'impacts', '_labelClass'=>'impacts', 'type'=>'impacts', 'hasChildren'=>true];
		    $nodes[] = $node;
		    $links[] = ['sid'=>$idDan, 'tid'=>$idPos];
		  }
		  
          $positiveExists = false;
		  if(in_array($current ,[$idDan,$idPos,$idNeg,$idAdp,0])) {		  
			$risks = Risk::inqRisksByDangerAndNegative($danger['id'], true, $sector);
			foreach($risks as $risk) {
 			  $idRisk = crc32('Risk:'.$risk['id'].':'.$risk['name'].':'.$risk['negative']);
			  $positiveExists = true;
 			  if($current==$idNeg or $current==$idRisk) {
				$node = ['name'=>$risk['name'], '_color'=>'#ff0000', 'id'=>$idRisk, '_cssClass'=>'negRisks', '_labelClass'=>'negRisks', 'type'=>'negRisks', 'hasChildren'=>false];
				$nodes[] = $node;
				$links[] = ['sid'=>$idNeg, 'tid'=>$idRisk];
			  } 
			}
		  } 
		  //if($exists and ($current==$idNeg or $current==$idDan or $subKnotsAlways)) {
	  	  if($positiveExists and in_array($current ,[$idDan,$idPos,$idNeg,$idAdp])) {		  
            $node = ['name'=>'Impacts(-)', '_color'=>'#aa0000', 'id'=>$idNeg, '_cssClass'=>'impacts', '_labelClass'=>'impacts', 'type'=>'impacts', 'hasChildren'=>true];
		    $nodes[] = $node;
		    $links[] = ['sid'=>$idDan, 'tid'=>$idNeg];
		  }
		  

          $adaptionExists = false;
		  if(in_array($current ,[$idDan,$idPos,$idNeg,$idAdp,0])) {		  
			$adaptions = Adaption::inqAdaptionsByDanger($danger['id'], $sector);
			foreach($adaptions as $adaption) {
 			  $idAdaption = crc32('Adaption:'.$adaption['id'].':'.$adaption['name']);
			  $adaptionExists = true;
 			  if($current==$idAdp or $current==$idAdaption) {
				$node = ['name'=>$adaption['name'], '_color'=>'#0000ff', 'id'=>$idAdaption, '_cssClass'=>'Adaptions', '_labelClass'=>'Adaptions', 'type'=>'Adaptions', 'hasChildren'=>false];
				$nodes[] = $node;
				$links[] = ['sid'=>$idAdp, 'tid'=>$idAdaption];
			  } 
			}
		  }		  
		  //if($exists and ($current==$idAdp or $current==$idDan or $subKnotsAlways)) {
		  if($adaptionExists and in_array($current ,[$idDan,$idPos,$idNeg,$idAdp])) {		  
            $node = ['name'=>'Adaptions', '_color'=>'#0000aa', 'id'=>$idAdp, '_cssClass'=>'adapts', '_labelClass'=>'adapts', 'type'=>'adapts', 'hasChildren'=>true];
		    $nodes[] = $node;
		    $links[] = ['sid'=>$idDan, 'tid'=>$idAdp];
		  }

          
		  if($current==0 or in_array($current ,[$idDan,$idPos,$idNeg,$idAdp])) {
			  if($adaptionExists or $positiveExists or  $negativeExists) {
                $node = ['name'=>$danger['name'], '_color'=>'#00aaff', 'id'=>$idDan, '_cssClass'=>'danger', '_labelClass'=>'danger', 'type'=>'danger', 'hasChildren'=>true];
		        $nodes[] = $node;
		        $links[] = ['sid'=>0, 'tid'=>$idDan];
			  }
	      }

		}



       $result = [ 'svgs'=>$svgs, 'nodes'=>$nodes, 'links'=>$links ];	   
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 	
	
   
  

}


