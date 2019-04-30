<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Hazard extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $description;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
      public $label;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'hazard';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['visible'], 'boolean'],
            [['name', 'description'], 'string'],
			[['label'], 'string']
			
        ];
    }

/*
    private $_label;
	
	public function getLabel() {
	    return $this->_label;	
	}	 
	public function setLabel($l) {
	    $this->_label = $l;	
	}
*/
	
	public function inqAllHazards( $inclInvisible = false ) {
	    $hazards = Hazard::find();
		if(!$inclInvisible) {
		   $hazards = $hazards->where(['visible' => true]);	
		}
        $hazards = $hazards->orderBy(['name'=>SORT_ASC]);
        return $hazards->all();
	}
	
	public static function findById($id)
    {
        $hazard = Hazard::find()
            ->where(['id' => $id])
            ->one();
        return $hazard;
    }
	
	public static function findByName($name)
    {
        $hazard = Hazard::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $hazard;
    }
	
	public static function findBy($idOrName)
	{
		$hazard = NULL;
		if(is_numeric($idOrName))
		{
		   $hazard = Hazard::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $hazard = Hazard::findByName($idOrName);
		}
		return $hazard;
	}	
   
}
