<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Landscape extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
      public $label;
      public $description;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'landscape';
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
            [['name', 'elevation_min', 'elevation_max'], 'required'],
            [['elevation_min', 'elevation_max'], 'integer'],
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
	


	public static function inqAllLandscapes( $inclInvisible = false ) {
	    $landscapes = Landscape::find();
		if(!$inclInvisible) {
		   $landscapes = $landscapes->where(['visible' => true]);	
		}
		$landscapes = $landscapes->limit(-1);
        $landscapes = $landscapes->orderBy(['elevation_min'=>SORT_ASC]);
        return $landscapes->all();
	}
/*	
	public static function findById($id)
    {
        $danger = Danger::find()
            ->where(['id' => $id])
            ->one();
        return $danger;
    }
	
	public static function findByName($name)
    {
        $danger = Danger::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $danger;
    }
	
	public static function findBy($idOrName)
	{
		$danger = NULL;
		if(is_numeric($idOrName))
		{
		   $danger = Danger::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $danger = Danger::findByName($idOrName);
		}
		return $danger;
	}	
*/

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
