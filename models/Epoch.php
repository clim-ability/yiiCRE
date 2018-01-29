<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Epoch extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $description;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
	

    public static function tableName()
    {
        return 'epoch';
    }

    public function rules()
    {
        return [
            [['name', 'year_begin', 'year_end'], 'required'],
            [['visible'], 'boolean'],
            [['name', 'description'], 'string'],
            [['year_begin', 'year_end'], 'integer']

        ];
    }
	
	public function inqAllEpochs( $inclInvisible = false ) {
	    $epochs = Epoch::find();
		if(!inclInvisible) {
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
   
}
