<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Risk extends ActiveRecord 
{
//    public $id;
//    public $name;
//    public $year_begin;
//    public $year_end;
//	  public $visible;
      //public $label;
      //public $description;
	
    public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }

    public static function tableName()
    {
        return 'risk';
    }

    public function fields()
    {
        $fields = parent::fields();
		//$fields[] = 'label';
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
            [['negative'], 'boolean'],
            [['name'], 'string'],
            [['description'], 'string'],
            [['details'], 'string'],
            //[['label'], 'safe']
			// [['label', 'description'], 'safe']
			
        ];
    }


    private $_label;
    public function getLabel() {
	    return $this->_label;	
	}	 
	public function setLabel($l) {
	    $this->_label = $l;	
	}

    public function inqRelatedZones($inclInvisible = false) {
        $zones = Zone::find();
		if(!$inclInvisible) {
		   $zones = $zones->where(['visible' => true]);	
		}
        $zones = $zones->join('INNER JOIN', 'zone_risk', 'zone_risk.zone_id = zone.id')->andWhere(['zone_risk.risk_id' => $this->id]);
		$zones = $zones->limit(-1);
        $zones = $zones->orderBy(['name'=>SORT_ASC]);
        return $zones->all();       
    }
	
    public function inqRelatedCountries($inclInvisible = false) {
        $countries = Country::find();
		if(!$inclInvisible) {
		   $countries = $countries->where(['visible' => true]);	
		}
        $countries = $countries->join('INNER JOIN', 'country_risk', 'country_risk.country_id = country.id')->andWhere(['country_risk.risk_id' => $this->id]);
		$countries = $countries->limit(-1);
        $countries = $countries->orderBy(['name'=>SORT_ASC]);
        return $countries->all();       
    }

    public function inqRelatedLandscapes($inclInvisible = false) {
        $landscapes = Landscape::find();
		if(!$inclInvisible) {
		   $landscapes = $landscapes->where(['visible' => true]);	
		}
        $landscapes = $landscapes->join('INNER JOIN', 'landscape_risk', 'landscape_risk.landscape_id = landscape.id')->andWhere(['landscape_risk.risk_id' => $this->id]);
		$landscapes = $landscapes->limit(-1);
        $landscapes = $landscapes->orderBy(['name'=>SORT_ASC]);
        return $landscapes->all();       
    }

	public function inqAllRisks( $inclInvisible = false ) {
	    $risks = Risk::find();
		if(!$inclInvisible) {
		   $risks = $risks->where(['visible' => true]);	
		}
		$risks = $risks->limit(-1);
        $risks = $risks->orderBy(['name'=>SORT_ASC]);
        return $risks->all();
	}

	public function inqRisksByDangerAndNegative( $dangerId, $negative=true, $sector=NULL, $inclInvisible = false ) {
	    $risks = Risk::find()->where(['negative' => $negative]);
        $risks = $risks->join('INNER JOIN', 'danger_risk', 'danger_risk.risk_id = risk.id')->andWhere(['danger_risk.danger_id' => $dangerId]);
		if(!$inclInvisible) {
		   $risks = $risks->andWhere(['visible' => true]);	
		}
        if($sector) {
            $sectorModel = Sector::findBy($sector);
            if($sectorModel) {
                $sectorId = $sectorModel['id'];
                $risks = $risks->join('INNER JOIN', 'sector_risk', 'sector_risk.risk_id = risk.id')->andWhere(['sector_risk.sector_id' => $sectorId]); 
            }
        }
		$risks = $risks->limit(-1);
        $risks = $risks->orderBy(['name'=>SORT_ASC]);
        return $risks->all();
	}    

	public function inqRisksByDangerSectorLandscapeCountry( $dangerId=NULL, $sectorId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {
	    $risks = Risk::find();
        if($dangerId) {
          $risks = $risks->join('INNER JOIN', 'danger_risk', 'danger_risk.risk_id = risk.id')->andWhere(['danger_risk.danger_id' => $dangerId]);
        } 
		if(!$inclInvisible) {
		   $risks = $risks->andWhere(['visible' => true]);	
		}
        if($sectorId) {
           $risks = $risks->join('INNER JOIN', 'sector_risk', 'sector_risk.risk_id = risk.id')->andWhere(['sector_risk.sector_id' => $sectorId]); 
        }
        if($landscapeId) {
            $risks = $risks->join('INNER JOIN', 'landscape_risk', 'landscape_risk.risk_id = risk.id')->andWhere(['landscape_risk.landscape_id' => $landscapeId]); 
         }
         if($countryId) {
            $risks = $risks->join('INNER JOIN', 'country_risk', 'country_risk.risk_id = risk.id')->andWhere(['country_risk.country_id' => $countryId]); 
         }         
		$risks = $risks->limit(-1);
        $risks = $risks->orderBy(['name'=>SORT_ASC]);
        return $risks->all();
	}      

	public static function findById($id)
    {
        $risk = Risk::find()
            ->where(['id' => $id])
            ->one();
        return $risk;
    }
	
	public static function findByName($name)
    {
        $risk = Risk::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $risk;
    }
	
	public static function findBy($idOrName)
	{
		$risk = NULL;
		if(is_numeric($idOrName))
		{
		   $risk = Risk::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $risk = Risk::findByName($idOrName);
		}
		return $risk;
	}	
  
public function search($params)
    {
        $query = Risk::find();

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
