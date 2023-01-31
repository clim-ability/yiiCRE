<?php
/**
 * @copyright 2018 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Adaption;
use app\models\Country;
use app\models\Danger;
use app\models\Epoch;
use app\models\Hazard;
use app\models\Parameter;
use app\models\Landscape;
use app\models\Risk;
use app\models\Scenario;
use app\models\Station;
use app\models\Sector;
use app\models\Zone;
use app\models\Gis;
use app\models\User;
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
	
   public function actionInqRisks($danger=null, $sector, $landscape, $country, $language='en') {
    $dangerId = null; 
	if($danger) {
		$dangerModel = Danger::findBy($danger);
		if($dangerModel) {
			$dangerId = $dangerModel['id'];
		}
	}
    $sectorId = null; 
	if($sector) {
		$sectorModel = Sector::findBy($sector);
		if($sectorModel) {
			$sectorId = $sectorModel['id'];
		}
	}
	$landscapeId = null; 
	if($landscape) {
		$landscapeModel = Landscape::findBy($landscape);
		if($landscapeModel) {
			$landscapeId = $landscapeModel['id'];
		}
	}
	$countryId = null; 
	if($country) {
		$countryModel = Country::findBy($country);
		if($countryModel) {
			$countryId = $countryModel['id'];
		}
	}
	$result = Risk::inqRisksByDangerSectorLandscapeCountry($dangerId, $sectorId, $landscapeId, $countryId);
	$result = array_map(function($e) use ($language) { 
		$e->label = \Yii::t('Risk:name', $e->name, [], $language);
		$e->description = \Yii::t('Risk:description', $e->description, [], $language);
		$e->details = \Yii::t('Risk:details', $e->details, [], $language);
		//$e->zones = $e->inqZones();
		return $e; 
		} ,$result); 
		//var_dump($result);
	 \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	 return $result;
   }
 
   public function actionInqRelatedInfoByRisk($risk) {
	$zones = []; 
	$countries = [];
	$landscapes = [];
	if($risk) {
		$riskModel = Risk::findBy($risk);
		if($riskModel) {
			$zones = $riskModel->inqRelatedZones();
			$countries = $riskModel->inqRelatedCountries();
			$landscapes = $riskModel->inqRelatedLandscapes();
		}
	}
	$result = ['zones'=>$zones, 'countries'=>$countries, 'landscapes'=>$landscapes];
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result; 
   }

   public function actionInqAdaptions($danger=null, $sector, $landscape, $country, $language='en') {
    $dangerId = null; 
	if($danger) {
		$dangerModel = Danger::findBy($danger);
		if($dangerModel) {
			$dangerId = $dangerModel['id'];
		}
	}
    $sectorId = null; 
	if($sector) {
		$sectorModel = Sector::findBy($sector);
		if($sectorModel) {
			$sectorId = $sectorModel['id'];
		}
	}
	$landscapeId = null; 
	if($landscape) {
		$landscapeModel = Landscape::findBy($landscape);
		if($landscapeModel) {
			$landscapeId = $landscapeModel['id'];
		}
	}
	$countryId = null; 
	if($country) {
		$countryModel = Country::findBy($country);
		if($countryModel) {
			$countryId = $countryModel['id'];
		}
	}
	$result = Adaption::inqAdaptionsByDangerSectorLandscapeCountry($dangerId, $sectorId, $landscapeId, $countryId);
	$result = array_map(function($e) use ($language) { 
		$e->label = \Yii::t('Adaption:name', $e->name, [], $language);
		$e->description = \Yii::t('Adaption:description', $e->description, [], $language);
		$e->details = \Yii::t('Adaption:details', $e->details, [], $language);
		return $e; 
		} ,$result); 
	 \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	 return $result;
   }

   public function actionInqRelatedInfoByAdaption($adaption) {
	$zones = []; 
	$countries = [];
	$landscapes = [];
	if($adaption) {
		$adaptionModel = Adaption::findBy($adaption);
		if($adaptionModel) {
			$zones = $adaptionModel->inqRelatedZones();
			$countries = $adaptionModel->inqRelatedCountries();
			$landscapes = $adaptionModel->inqRelatedLandscapes();
		}
	}
	$result = ['zones'=>$zones, 'countries'=>$countries, 'landscapes'=>$landscapes];
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result; 
   }

   public function actionDangers($mode='visible-only', $language='en') {
	// returns list of all Hazards.
	$result = Danger::inqAllDangers('invisible'==$mode);
	$result = array_map(function($e) use ($language) { 
	   $e->label = \Yii::t('Danger:name', $e->name, [], $language);
	   $e->description = \Yii::t('Danger:description', $e->name, [], $language);
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   } 

   public function actionDangersCountingRisks($sector, $landscape, $country, $mode='visible-only', $language='en', $all='') {
	// returns list of all Scenarios.
	$sectorId = null; 
	if($sector) {
		$sectorModel = Sector::findBy($sector);
		if($sectorModel) {
			$sectorId = $sectorModel['id'];
		}
	}
	$landscapeId = null; 
	if($landscape) {
		$landscapeModel = Landscape::findBy($landscape);
		if($landscapeModel) {
			$landscapeId = $landscapeModel['id'];
		}
	}
	$countryId = null; 
	if($country) {
		$countryModel = Country::findBy($country);
		if($countryModel) {
			$countryId = $countryModel['id'];
		}
	}
	$result = Danger::inqAllDangersWithCountOfRisks($sectorId, $landscapeId, $countryId, 'invisible'==$mode, $all=='incl');
	$result = array_map(function($e) use ($language) { 
	   $e['label'] = \Yii::t('Danger:name', $e['name'], [], $language);
	   $e['description'] = \Yii::t('Danger:description', $e['name'], [], $language);
	   if(!$e['counting']) {
		$e['counting'] = 0;
	   }
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   } 

   public function actionDangersCountingAdaptions($sector, $landscape, $country, $mode='visible-only', $language='en', $all='') {
	// returns list of all Scenarios.
	$sectorId = null; 
	if($sector) {
		$sectorModel = Sector::findBy($sector);
		if($sectorModel) {
			$sectorId = $sectorModel['id'];
		}
	}
	$landscapeId = null; 
	if($landscape) {
		$landscapeModel = Landscape::findBy($landscape);
		if($landscapeModel) {
			$landscapeId = $landscapeModel['id'];
		}
	}
	$countryId = null; 
	if($country) {
		$countryModel = Country::findBy($country);
		if($countryModel) {
			$countryId = $countryModel['id'];
		}
	}
	$result = Danger::inqAllDangersWithCountOfAdaptions($sectorId, $landscapeId, $countryId, 'invisible'==$mode, $all=='incl');
	$result = array_map(function($e) use ($language) { 
	   $e['label'] = \Yii::t('Danger:name', $e['name'], [], $language);
	   $e['description'] = \Yii::t('Danger:description', $e['name'], [], $language);
	   if(!$e['counting']) {
		$e['counting'] = 0;
	   }
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   } 

   public function actionZones($mode='visible-only', $language='en') {
	// returns list of all Landscapes.
	$result = Zone::inqAllZones('invisible'==$mode);
	$result = array_map(function($e) use ($language) { 
	   $e->label = \Yii::t('Zone:name', $e->name, [], $language);
	   // $e->description = \Yii::t('Danger:description', $e->name, [], $language);
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   } 

   public function actionLandscapes($mode='visible-only', $language='en') {
	// returns list of all Landscapes.
	$result = Landscape::inqAllLandscapes('invisible'==$mode);
	$result = array_map(function($e) use ($language) { 
	   $e->label = \Yii::t('Landscape:name', $e->name, [], $language);
	   // $e->description = \Yii::t('Danger:description', $e->name, [], $language);
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   } 

   public function actionCountries($mode='visible-only', $language='en') {
	// returns list of all Landscapes.
	$result = Gis::getCountries();

	$result = array_map(function($e) use ($language) { 
	   $e['label'] = \Yii::t('Country:name', $e['country'], [], $language);
	   // $e->description = \Yii::t('Danger:description', $e->name, [], $language);
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   } 

    public function actionHazards($mode='visible-only', $language='en') {
       // returns list of all Hazards.
	   $result = Hazard::inqAllHazards('invisible'==$mode);
	   $result = array_map(function($e) use ($language) { 
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
    
	public function actionScenarios($mode='visible-only', $language='en') {
       // returns list of all Scenarios.
	   $result = Scenario::inqAllScenarios('invisible'==$mode);
//	   $result = array_map(function($e) { $e->label = $e->name; return $e; },$result);
   	   $result = array_map(function($e) use ($language) { 
	      $e->label = \Yii::t('Scenario:name', $e->name, [], $language);
		  $e->description = \Yii::t('Scenario:description', $e->name, [], $language);
		  return $e; 
		  } ,$result); 
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 
 
	public function actionSectors($mode='visible-only', $language='en') {
       // returns list of all Scenarios.
	   $result = Sector::inqAllSectors('invisible'==$mode);
	   $result = array_map(function($e) use ($language) { 
	      $e->label = \Yii::t('Sector:name', $e->name, [], $language);
		  $e->description = \Yii::t('Sector:description', $e->name, [], $language);
		  return $e; 
		  } ,$result); 
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   } 
   
   public function actionSectorsCountingRisks($danger, $landscape, $country, $mode='visible-only', $language='en') {
	// returns list of all Scenarios.
	$dangerId = null; 
	if($danger) {
		$dangerModel = Danger::findBy($danger);
		if($dangerModel) {
			$dangerId = $dangerModel['id'];
		}
	}
	$landscapeId = null; 
	if($landscape) {
		$landscapeModel = Landscape::findBy($landscape);
		if($landscapeModel) {
			$landscapeId = $landscapeModel['id'];
		}
	}
	$countryId = null; 
	if($country) {
		$countryModel = Country::findBy($country);
		if($countryModel) {
			$countryId = $countryModel['id'];
		}
	}
	$result = Sector::inqAllSectorsWithCountOfRisks($dangerId, $landscapeId, $countryId, 'invisible'==$mode);
	$result = array_map(function($e) use ($language) { 
	   $e['label'] = \Yii::t('Sector:name', $e['name'], [], $language);
	   $e['description'] = \Yii::t('Sector:description', $e['name'], [], $language);
	   if(!$e['counting']) {
		$e['counting'] = 0;
	   }
	   return $e; 
	   } ,$result); 
	\Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	return $result;
   }    
 
   public function actionSectorsCountingAdaptions($danger, $landscape, $country, $mode='visible-only', $language='en') {
	// returns list of all Scenarios.
	$dangerId = null; 
	if($danger) {
		$dangerModel = Danger::findBy($danger);
		if($dangerModel) {
			$dangerId = $dangerModel['id'];
		}
	}
	$landscapeId = null; 
	if($landscape) {
		$landscapeModel = Landscape::findBy($landscape);
		if($landscapeModel) {
			$landscapeId = $landscapeModel['id'];
		}
	}
	$countryId = null; 
	if($country) {
		$countryModel = Country::findBy($country);
		if($countryModel) {
			$countryId = $countryModel['id'];
		}
	}
	$result = Sector::inqAllSectorsWithCountOfAdaptions($dangerId, $landscapeId, $countryId, 'invisible'==$mode);
	$result = array_map(function($e) use ($language) { 
	   $e['label'] = \Yii::t('Sector:name', $e['name'], [], $language);
	   $e['description'] = \Yii::t('Sector:description', $e['name'], [], $language);
	   if(!$e['counting']) {
		$e['counting'] = 0;
	   }
	   return $e; 
	   } ,$result); 
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
           $elevation = (1.5*$result['elevation_calculated']['value'] + 3.0*$result['elevation_iso_raster']['value'])/4.0;
           $result['landscape'] = Landscape::findByElevation($elevation);
	   $result['nearest_river'] = Gis::getDistanceToRiver($latitude, $longitude);
	   $result['nearest_city'] = Gis::getDistanceToCity($latitude, $longitude);
	   $result['country'] = Gis::getCountry($latitude, $longitude);
           $result['landcover'] = Gis::getLandcover($latitude, $longitude);
           $result['municipal'] = Gis::getMunicipal($latitude, $longitude);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }

   public function actionNormalizedHazards($latitude, $longitude, $epoch='', $scenario='')
   {
	  $hazards = Gis::getNormalizedHazards($latitude, $longitude, $epoch, $scenario);
	  \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return $hazards;		   
   }
   
   
   public function actionHazardExtremes($hazard='', $epoch='', $scenario='', $absolute='')
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
	  $result = Gis::getHazardExtremes($hazardsList, !empty($absolute));
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
  
   public function actionStationData($latitude, $longitude, $language='en', $elevmin=null, $elevmax=null)
   {
	   $result = [];	  	   
       $result = Station::getNearestStation($latitude, $longitude, $language, $elevmin, $elevmax);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
   }

   public function actionStationsGeojson($mode='visible-only', $language='en')
   {
	   $results = [];
       $stations = Station::inqAllStations('invisible'==$mode);	   
       foreach($stations as $station) {
	   $station['abbreviation'] = $station['name'];	   
 	   $results[] = ['type'=>'Feature',
			              'properties'=>['abbreviation' => yii::t('Station:abbreviation', $station['name'])],
			              'geometry'=>['type'=>'Point', 
						    'coordinates'=>[floatval($station['longitude']),
							                floatval($station['latitude'])]]
			             ]; 
	   }
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $results;
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
           $result['landscape'] = null;
           $elevation = (1.5*floatval($result['elevation_calculated']['value']) +
                         10.0*floatval($result['elevation_iso_raster']['value'])) / 11.0;
           $landscape = Landscape::findByElevation($elevation);
           if($landscape) {
               $result['landscape'] = ['name'=>$landscape->name, 'elevMin'=>$landscape->elevation_min, 'elevMax'=>$landscape->elevation_max];
           }
	   $result['nearest_river'] = Gis::getDistanceToRiver($latitude, $longitude);
	   $result['nearest_city'] = Gis::getDistanceToCity($latitude, $longitude);
           $result['country'] = Gis::getCountry($latitude, $longitude);	 
           $result['landcover'] = Gis::getLandcover($latitude, $longitude);
           $result['municipal'] = Gis::getMunicipal($latitude, $longitude);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
       
   }    
   
   public function actionHazardGeom($hazard='', $epoch='', $scenario='', $parameter='mean', $resolution=0.1, $bbox='', $absolute='') 
   {
	   $hazard = Hazard::findBy($hazard);
       $epoch = Epoch::findBy($epoch);
	   $scenario = Scenario::findBy($scenario);
	   $parameter = Parameter::findBy($parameter);
	   $table = Gis::getRasterTable($hazard, $parameter, $epoch, $scenario);
	   $features = [];
	   if(is_string($table)) {
		  $rows = Gis::getHazardGeometry($table, $hazard['name'], $bbox, ('abs' == $absolute));
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

   public function actionBorders()
   {
        $features = [];
	   	$rows = Gis::getCountryBorders();
        foreach($rows as $row) {
		    $feature = ['type' => 'Feature', 'geometry' => json_decode($row['geojson'], true), 'properties' => ['country' => $row['country']]];
            $features[] = $feature;
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


