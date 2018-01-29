<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Epoch extends ActiveRecord 
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
	
	public static function getValue()
	{
		$connection = Yii::$app->db2;
/**
        $command = $connection->createCommand("
    SELECT SUM(bets.balance_return) AS total_win
     , bets.user_id
     , users.user_name
     , users.user_status
    FROM bets INNER JOIN users ON bets.user_id = users.id
    WHERE users.user_status = 'verified'
    AND bets.date_time > :start_date
    GROUP BY bets.user_id
    ORDER BY total_win DESC", [':start_date' => '1970-01-01']);
**/
	
	$sql =  "SELECT value/area as value, std , dlongitude, dlatitude "
	. " FROM (SELECT SUM(ST_Area(ST_Intersection(geom, ellipse))) as area, "
	. " SUM(cddp*ST_Area(ST_Intersection(geom, ellipse))) AS value, "
	. " STDDEV(cddp) as std, "
	. " regr_slope(cddp, ST_X(ST_Centroid(ST_Transform(geom, 4326)))) as dlongitude, "
	. " regr_slope(cddp, ST_Y(ST_Centroid(ST_Transform(geom, 4326)))) as dlatitude "
	. " FROM public.cddp_mean_rcp45_2021_2050_minus_knp, "
	. " (SELECT ST_Buffer(ST_Transform(ST_Translate(ST_SetSRID(ST_MakePoint(0, 0),4326),7.85,47.983333), 900915), "
	. " AVG(sqrt(1.2*ST_Area(ST_SetSRID(geom, 900915))))) AS ellipse "
	. " FROM public.cddp_mean_rcp45_2021_2050_minus_knp) AS foo "
	. " WHERE ST_Intersects(geom, ellipse)) AS sum";
	
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 return $result;
	}	
	
   
}
