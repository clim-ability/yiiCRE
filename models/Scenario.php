<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Scenario extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $description;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_gisdata;
    }

    public static function tableName()
    {
        return 'scenario';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['visible'], 'boolean'],
            [['name', 'description'], 'string']
        ];
    }
	
	public function inqAllScenarios( $inclInvisible = false ) {
	    $scenarios = Scenario::find();
		if(!$inclInvisible) {
		   $scenarios = $scenarios->where(['visible' => true]);	
		}
        $scenarios = $scenarios->orderBy(['name'=>SORT_ASC]);
        return $scenarios->all();
	}
	
	public static function findById($id)
    {
        $scenario = Scenario::find()
            ->where(['id' => $id])
            ->one();
        return $scenario;
    }
	
	public static function findByName($name)
    {
        $scenario = Scenario::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $scenario;
    }
	
	public static function findBy($idOrName)
	{
		$scenario = NULL;
		if(is_numeric($idOrName))
		{
		   $scenario = Scenario::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $scenario = Scenario::findByName($idOrName);
		}
		return $scenario;
	}	
   
}
