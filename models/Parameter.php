<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Parameter extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $description;
//	  public $visible;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'parameter';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['visible'], 'boolean'],
            [['name', 'description'], 'string']
        ];
    }
	
	public function inqAllParameters( $inclInvisible = false ) {
	    $parameters = Parameter::find();
		if(!$inclInvisible) {
		   $parameters = $parameters->where(['visible' => true]);	
		}
        $parameters = $parameters->orderBy(['name'=>SORT_ASC]);
        return $parameters->all();
	}
	
	public static function findById($id)
    {
        $parameter = Parameter::find()
            ->where(['id' => $id])
            ->one();
        return $parameter;
    }
	
	public static function findByName($name)
    {
        $parameter = Parameter::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $parameter;
    }
	
	public static function findBy($idOrName)
	{
		$parameter = NULL;
		if(is_numeric($idOrName))
		{
		   $parameter = Parameter::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $parameter = Parameter::findByName($idOrName);
		}
		return $parameter;
	}	
   
}
