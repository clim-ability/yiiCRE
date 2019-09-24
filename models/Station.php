<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\db\Expression;




class Station extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
      public $label;
      public $description;
      public $abbreviation;
      public $longitude;
      public $latitude;	  
	  
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'station';
    }

    public function fields()
    {
        $fields = parent::fields();
		$fields[] = 'label';
		return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->label = $this->name;
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['visible'], 'boolean'],
            [['name'], 'string'],
			[['label', 'description'], 'safe']
			
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
	
	public function inqAllStations( $inclInvisible = false ) {
	    $stations = Station::find();
		$longitude = new Expression('ST_X(location::geometry) AS longitude'); 
		$latitude = new Expression('ST_X(location::geometry) AS latitude');
		$stations = $stations->select(['*', $longitude, $latitude]);
		if(!$inclInvisible) {
		   $stations = $stations->where(['visible' => true]);	
		}
		$stations = $stations->limit(-1);
        $stations = $stations->orderBy(['name'=>SORT_ASC]);
        return $stations->all();
	}
	
	public static function getNearestStation($latitude, $longitude, $language='en')
	{
	  $coordinate = ''.(float)$longitude.','.(float)$latitude.'';
	  $connection = Station::getDb();
      //$connection = Yii::$app->pgsql_gisdata;
	  $sql = "SELECT st_distance(location, ST_SetSRID(ST_MakePoint(".$coordinate."),4326)) as distance, "
	        ." ST_X(location::geometry) as longitude, ST_Y(location::geometry) as  latitude, *"
           . " FROM public.station WHERE visible ORDER BY distance LIMIT 1;";
	 $command = $connection->createCommand($sql);
     $result = $command->queryOne();
	 $result['abbreviation'] = \Yii::t('Hazard:abbreviation', $result['name'], [], $language);
	 return $result;		
	}
	
	public static function findById($id)
    {
        $station = Station::find()
            ->where(['id' => $id])
            ->one();
        return $station;
    }
	
	public static function findByName($name)
    {
        $station = Station::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $station;
    }
	
	public static function findBy($idOrName)
	{
		$station = NULL;
		if(is_numeric($idOrName))
		{
		   $station = Station::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $station = Station::findByName($idOrName);
		}
		return $station;
	}	
  
public function search($params)
    {
        $query = Station::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
/*
        $query->andFilterWhere([
            'id' => $this->id,
            'source_id' => $this->source_id,
            'project_id' => $this->project_id,
            'doi' => $this->doi,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'public' => $this->public,
            'modified_at' => $this->modified_at,
            //'verified' => $this->verified,
        ]);

        if ($source_id) {
            $query->andFilterWhere([
                'source_id' => $source_id,
            ]);
        }
        if ($project_id) {
            $query->andFilterWhere([
                'project_id' => $project_id,
            ]);
        }

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'page', $this->page])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'text_vector', $this->text_vector])
            ->andFilterWhere(['like', 'comment', $this->comment]);
*/
        return $dataProvider;
    }

  
}
