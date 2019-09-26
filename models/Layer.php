<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Layer extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $description;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'layer';
    }

    public function rules()
    {
        return [
            [['name', 'variable', 'hazard_id', 'parameter_id', 'epoch_id', 'scenario_id', 'layer'], 'required'],
            [['visible'], 'boolean'],
            [['name', 'description', 'layer', 'variable'], 'string'],
            [['hazard_id', 'parameter_id', 'epoch_id', 'scenario_id'], 'integer']

        ];
    }
	
	public function inqAllEpochs( $inclInvisible = false ) {
	    $epochs = Layer::find();
		if(!$inclInvisible) {
		   $epochs = $epochs->where(['visible' => true]);	
		}
        $epochs = $epochs->orderBy(['name'=>SORT_ASC]);
        return $epochs->all();
	}
	
	public static function findById($id)
    {
        $epoch = Layer::find()
            ->where(['id' => $id])
            ->one();
        return $epoch;
    }
	
	public static function findByName($name)
    {
        $epoch = Layer::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $epoch;
    }
	
	public static function findBy($idOrName)
	{
		$epoch = NULL;
		if(is_numeric($idOrName))
		{
		   $epoch = Layer::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $epoch = Layer::findByName($idOrName);
		}
		return $epoch;
	}	
   
}
