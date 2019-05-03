<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Gis extends ActiveRecord 
{
//    public $id;
//    public $value;
//    public $geom;
	

    public static function tableName()
    {
        return 'tr_hazard_scenario_epoch_minus_knp';
    }

    public function rules()
    {
        return [
            [['value', 'geom'], 'required'],
            [['value'], 'double'],
            [['geom'], 'string']
        ];
    }
	
	/*  //Should be in Epoch...
	public function inqAllEpochs( $inclInvisible = false ) {
	    $epochs = Epoch::find();
		if(!$inclInvisible) {
		   $epochs = $epochs->where(['visible' => true]);	
		}
        $epochs = $epochs->orderBy(['year_begin'=>SORT_ASC, 'year_end'=>SORT_ASC]);
        return $epochs->all();
	}
	
	public static function findById($id)
    {
        $epoch = Epoch::find()
            ->where(['id' => $id])
            ->one();
        return $epoch;
    }
	*/
	
	public static function getCalculatedValue($table, $variable, $latitude, $longitude)
	{
	$coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	//$connection = Yii::$app->db2;
	$connection = Yii::$app->pgsql_gisdata;
	$sql =  "SELECT value/area as value, std , dlongitude, dlatitude "
	. " FROM (SELECT SUM(ST_Area(ST_Intersection(geom, ellipse))) as area, "
	. " SUM(".$variable."*ST_Area(ST_Intersection(geom, ellipse))) AS value, "
	. " STDDEV(".$variable.") as std, "
	. " regr_slope(".$variable.", ST_X(ST_Centroid(ST_Transform(geom, 4326)))) as dlongitude, "
	. " regr_slope(".$variable.", ST_Y(ST_Centroid(ST_Transform(geom, 4326)))) as dlatitude "
	. " FROM public.\"".$table."\", "
	. " (SELECT ST_Buffer(ST_Transform(ST_Translate(ST_SetSRID(ST_MakePoint(0, 0),4326),".$coordinate."), 25832), "
	. " AVG(sqrt(1.2*ST_Area(ST_SetSRID(geom, 25832))))) AS ellipse "
	. " FROM public.\"".$table."\") AS foo "
	. " WHERE ST_Intersects(geom, ellipse)) AS sum";
	
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;
	}	
	
	
	public static function getRasterValue($table, $variable, $latitude, $longitude)
	{
	$coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	//$connection = Yii::$app->db2;
	$connection = Yii::$app->pgsql_gisdata;
	$sql =  "SELECT ".$variable." AS value "
	 . " FROM public.\"".$table."\" "
	 . " WHERE ST_Contains(geom, ST_Transform(ST_SetSRID(ST_MakePoint(".$coordinate."),4326), 25832))";
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;
	}

    public static function getHazardExtremes($hazards)
	{
	   $sql = "SELECT hazard, MIN(min) as min, MAX(max) as max FROM (";
	   $first = true;
	   foreach($hazards as $table=>$hazard) {
		  if(!$first) { $sql .= " UNION ";}
		  $sql .= "SELECT '".$hazard."' as hazard, MIN(".$hazard.") as min, MAX(".$hazard.") as max "
		        . " FROM public.\"".$table."\" ";
		  $first = false;
		   //var_dump($sql);
	   }	   
	   $sql .= " ) as foo GROUP BY hazard ";
	   //var_dump($sql);
	   $connection = Yii::$app->pgsql_gisdata;	   
	   $command = $connection->createCommand($sql);
       $result = $command->queryAll();
	   return $result;	   
	}

	public static function getHazardGeometry($table, $variable, $bbox)
	{
	 $sql =  "SELECT ".$variable." AS value, "
	  . "ST_AsGeoJSON(ST_Transform((geom),4326),6) AS geojson "
      . " FROM public.\"".$table."\" ";

     if (is_string($bbox) && (strlen($bbox) > 6)) {
       $bbox = explode(',', $bbox);
	   if (4 == sizeof($bbox)) {
         $sql = $sql . " WHERE ST_Transform(geom, 4326) && ST_SetSRID(ST_MakeBox2D(ST_Point(".$bbox[0].", ".$bbox[1]."), ST_Point(".$bbox[2].", ".$bbox[3].")),4326);";
       }
     }	
     //$connection = Yii::$app->db2;
	 $connection = Yii::$app->pgsql_gisdata;
	 $command = $connection->createCommand($sql);
     $result = $command->queryAll();
	 return $result;
	}
	
	public static function getIsoElevation($latitude, $longitude)
	{
	$coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	//$connection = Yii::$app->db2;
	$connection = Yii::$app->pgsql_gisdata;
	$sql =  "SELECT MAX(elev) AS value FROM \"asterglobaldemv2_polygons_cliped\" "
	   ." WHERE ST_Contains(geom, ST_Translate(ST_SetSRID(ST_MakePoint(0, 0),4326),".$coordinate."))";
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;		
	}
	
	public static function getCountry($latitude, $longitude)
	{
	$coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	//$connection = Yii::$app->db2;
	$connection = Yii::$app->pgsql_gisdata;
	$sql = "SELECT nuts0 AS country FROM \"borders_dissolved_adminboundaries\" "
	     . " WHERE ST_Contains(geom, ST_Transform(ST_SetSRID(ST_MakePoint(".$coordinate."),4326), 25832)) LIMIT 1";
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;		
	}	
	
	public static function getDistanceToRiver($latitude, $longitude)
	{
	$coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	//$connection = Yii::$app->db2;
	$connection = Yii::$app->pgsql_gisdata;
	$sql =  "SELECT st_distance(geom, ST_Transform(ST_SetSRID(ST_MakePoint(".$coordinate."),4326), 25832)) as distance, cum_len as length, catchment_ as catchment "
     ." FROM public.clipped_georhena_rivers WHERE catchment_ > 1.0 ORDER BY distance LIMIT 1;";
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;		
	}	
	
	public static function getDistanceToCity($latitude, $longitude)
	{
	  $coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	  //$connection = Yii::$app->db2;
      $connection = Yii::$app->pgsql_gisdata;
	  $sql = "SELECT st_distance(geom, ST_Transform(ST_SetSRID(ST_MakePoint(".$coordinate."),4326), 25832)) as distance, name, population"
           . " FROM public.georhena_main_cities_25832_shp ORDER BY distance LIMIT 1;";
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;		
	}	
	
	public static function getRasterTable($hazard, $parameter, $epoch, $scenario)
	{
		if (empty($hazard) or empty($parameter) or empty($epoch)) {
		   return null;
		}
		if (empty($scenario)) {
		   return $hazard['name']."_".$parameter['name']."_".$epoch['name']."_knp";	
		}
       return $hazard['name']."_".$parameter['name']."_".$scenario['name']."_".$epoch['name']."_minus_knp"; 
	}		
	

	/**
	get minimal distance to nearest river
	
	SELECT st_distance(geom, ST_Transform(ST_SetSRID(ST_MakePoint(7.85,47.983333),4326), 25832)) as dist
     FROM public.clipped_georhena_rivers ORDER BY dist LIMIT 1;
	
	**/
   
}
