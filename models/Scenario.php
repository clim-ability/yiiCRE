<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class Scenario extends ActiveRecord 
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
        return 'scenario';
    }
    
	public function fields()
    {
        $fields = parent::fields();
		$fields[] = 'label';
		return $fields;
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
	
public function search($params)
    {
        $query = Scenario::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
	
	
   
}
