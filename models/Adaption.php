<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Adaption extends ActiveRecord 
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
        return 'adaption';
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
        $zones = $zones->join('INNER JOIN', 'zone_adaption', 'zone_adaption.zone_id = zone.id')->andWhere(['zone_adaption.adaption_id' => $this->id]);
		$zones = $zones->limit(-1);
        $zones = $zones->orderBy(['name'=>SORT_ASC]);
        return $zones->all();       
    }
	
    public function inqRelatedCountries($inclInvisible = false) {
        $countries = Country::find();
		if(!$inclInvisible) {
		   $countries = $countries->where(['visible' => true]);	
		}
        $countries = $countries->join('INNER JOIN', 'country_adaption', 'country_adaption.country_id = country.id')->andWhere(['country_adaption.adaption_id' => $this->id]);
		$countries = $countries->limit(-1);
        $zones = $countries->orderBy(['name'=>SORT_ASC]);
        return $countries->all();       
    }
    public function inqRelatedLandscapes($inclInvisible = false) {
        $landscapes = Landscape::find();
		if(!$inclInvisible) {
		   $landscapes = $landscapes->where(['visible' => true]);	
		}
        $landscapes = $landscapes->join('INNER JOIN', 'landscape_adaption', 'landscape_adaption.landscape_id = landscape.id')->andWhere(['landscape_adaption.adaption_id' => $this->id]);
		$landscapes = $landscapes->limit(-1);
        $landscapes = $landscapes->orderBy(['name'=>SORT_ASC]);
        return $landscapes->all();       
    }

	public function inqAllAdaptions( $inclInvisible = false ) {
	    $adaptions = Adaption::find();
		if(!$inclInvisible) {
		   $adaptions = $adaptions->where(['visible' => true]);	
		}
		$adaptions = $adaptions->limit(-1);
        $adaptions = $adaptions->orderBy(['name'=>SORT_ASC]);
        return $adaptions->all();
	}

	public function inqAdaptionsByDanger( $dangerId, $sector=NULL, $inclInvisible = false ) {
	    $adaptions = Adaption::find();
        $adaptions = $adaptions->join('INNER JOIN', 'danger_adaption', 'danger_adaption.adaption_id = adaption.id')->andWhere(['danger_adaption.danger_id' => $dangerId]);
		if(!$inclInvisible) {
		   $adaptions = $adaptions->andWhere(['visible' => true]);	
		}
        if($sector) {
            $sectorModel = Sector::findBy($sector);
            if($sectorModel) {
                $sectorId = $sectorModel['id'];
                $adaptions = $adaptions->join('INNER JOIN', 'sector_adaption', 'sector_adaption.adaption_id = adaption.id')->andWhere(['sector_adaption.sector_id' => $sectorId]); 
            }
        }
		$adaptions = $adaptions->limit(-1);
        $adaptions = $adaptions->orderBy(['name'=>SORT_ASC]);
        return $adaptions->all();
	}    
	
	public function inqAdaptionsByDangerSectorLandscapeCountry( $dangerId=NULL, $sectorId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {
	    $adaptions = Adaption::find();
        if($dangerId) {
          $adaptions = $adaptions->join('INNER JOIN', 'danger_adaption', 'danger_adaption.adaption_id = adaption.id')->andWhere(['danger_adaption.danger_id' => $dangerId]);
        } 
		if(!$inclInvisible) {
		   $adaptions = $adaptions->andWhere(['visible' => true]);	
		}
        if($sectorId) {
           $adaptions = $adaptions->join('INNER JOIN', 'sector_adaption', 'sector_adaption.adaption_id = adaption.id')->andWhere(['sector_adaption.sector_id' => $sectorId]); 
        }
        if($landscapeId) {
            $adaptions = $adaptions->join('INNER JOIN', 'landscape_adaption', 'landscape_adaption.adaption_id = adaption.id')->andWhere(['landscape_adaption.landscape_id' => $landscapeId]); 
         }
         if($countryId) {
            $adaptions = $adaptions->join('INNER JOIN', 'country_adaption', 'country_adaption.adaption_id = adaption.id')->andWhere(['country_adaption.country_id' => $countryId]); 
         }         
		$adaptions = $adaptions->limit(-1);
        $adaptions = $adaptions->orderBy(['name'=>SORT_ASC]);
        return $adaptions->all();
	} 



	public static function findById($id)
    {
        $adaption = Adaption::find()
            ->where(['id' => $id])
            ->one();
        return $adaption;
    }
	
	public static function findByName($name)
    {
        $adapton = Adaption::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $adaption;
    }
	
	public static function findBy($idOrName)
	{
		$adapton = NULL;
		if(is_numeric($idOrName))
		{
		   $adapton = Adaption::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $adapton = Adaption::findByName($idOrName);
		}
		return $adapton;
	}	
  
public function search($params)
    {
        $query = Adaption::find();

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
