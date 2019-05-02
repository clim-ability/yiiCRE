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
            [['name', 'description'], 'string'],
			[['label'], 'safe']
			
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
  
public function search($params)
    {
        $query = Hazard::find();

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
