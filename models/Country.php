<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use app\modules\translation\models\Language;

class Country extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public gis;
//	  public $visible;
      public $label;
      public $description;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'country';
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
            [['name', 'gis', 'short'], 'required'],
            [['visible'], 'boolean'],
            [['name', 'gis', 'short'], 'string'],
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
	


	public static function inqAllCountries( $inclInvisible = false, $language=null ) {
	    $countries = Country::find();
		if(!$inclInvisible) {
		   $countries = $countries->where(['visible' => true]);	
		}
		$countries = $countries->limit(-1);
        $countries = $countries->orderBy(['short'=>SORT_ASC]);
        $result = $countries->all();
	$result = array_map(function($e) use ($language) { 
		           $e->label = \Yii::t('Country:name', $e->name, [], $language);
		           return $e; 
	} ,$result); 
        return $result;

	}
	
	public static function findById($id)
    {
        $country = Country::find()
            ->where(['id' => $id])
            ->one();
        return $country;
    }
	
	public static function findByName($name)
    {
        $country = Country::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $country;
    }

    public static function findByGis($name)
    {
        $country = Country::find()
            ->where(['gis' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $country;
    }   

	public static function findBy($idOrName)
	{
		$country = NULL;
		if(is_numeric($idOrName))
		{
		   $country = Country::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $country = Country::findByName($idOrName);
           if(!$country) {
            $country = Country::findByGis($idOrName);
           }
		}
		return $country;
	}	


    public static function findByElevation($elevation)
    {
      $result = null;
      $landscapes = Landscape::inqAllLandscapes();
      foreach($landscapes as $landscape) 
      { 
        if(($landscape->elevation_min <= $elevation) && ($landscape->elevation_max >= $elevation))
        {
            $result = $landscape;
        } 
      }  
      return $result;
    }  


public function search($params)
    {
        $query = Landscape::find();

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
