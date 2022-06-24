<?php

namespace app\models;

use Yii;
use PDO;
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

      public $zone_ids = [];
      private $_zones = "";
      public $country_ids = [];
      private $_countries = "";
      public $landscape_ids = [];
      private $_landscapes = "";
      public $danger_ids = [];
      private $_dangers = "";
      public $sector_ids = [];
      private $_sectors = "";

      
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
        $fields[] = 'dangers';
        $fields[] = 'sectors';
        $fields[] = 'landscapes';
        $fields[] = 'countries';
        $fields[] = 'zones';
		return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->label = $this->name;
        $this->loadDangers();
        $this->loadSectors();
        $this->loadLandscapes();
        $this->loadCountries();
        $this->loadZones();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveDangers();
        $this->saveSectors();
        $this->saveLandscapes();
        $this->saveCountries();
        $this->saveZones();
        return true;
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
            //[['zones'], 'string'],
            [['danger_ids', 'sector_ids', 'landscape_ids', 'country_ids', 'zone_ids'], 'safe'],
			[['danger_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Danger::className(), 'targetAttribute'=>'id']],
			[['sector_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Sector::className(), 'targetAttribute'=>'id']],
            [['landscape_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Landscape::className(), 'targetAttribute'=>'id']],
            [['country_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Country::className(), 'targetAttribute'=>'id']],
            [['zone_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Zone::className(), 'targetAttribute'=>'id']],


        ];
    }


    private $_label;
    public function getLabel() {
	    return $this->_label;	
	}	 
	public function setLabel($l) {
	    $this->_label = $l;	
	}

    
    public function inqRelatedDangers($inclInvisible = false) {
        $dangers = Danger::find();
		if(!$inclInvisible) {
		   $dangers = $dangers->where(['visible' => true]);	
		}
        $dangers = $dangers->join('INNER JOIN', 'danger_risk', 'danger_risk.danger_id = danger.id')->andWhere(['danger_risk.risk_id' => $this->id]);
		$dangers = $dangers->limit(-1);
        $dangers = $dangers->orderBy(['name'=>SORT_ASC]);
        return $dangers->all();       
    }
    public function loadDangers() {
        $this->danger_ids = [];
        $this->_dangers = "";
        $relDangers = $this->inqRelatedDangers(); 
        foreach($relDangers as $danger) {
            $this->danger_ids[] = $danger['id'];
            $this->_dangers .= $danger['name'].', ';
        }  
        if(strlen($this->_dangers)>2) {
            $this->_dangers = substr($this->_dangers,0,strlen($this->_dangers)-2); 
        } else {
            $this->_dangers = " - ";
        }
    }
    public function saveDangers() {
        $connection = $this->getDb();
        $sql = "DELETE FROM danger_risk WHERE risk_id = :riskId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->danger_ids)) {
            foreach($this->danger_ids as $danger_id) {
                $sql = "INSERT INTO danger_risk (danger_id, risk_id) VALUES(:dangerId, :riskId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':dangerId', (int) $danger_id, PDO::PARAM_INT);
                $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getDangers() {
        return $this->_dangers;	
	}	

        
    public function inqRelatedSectors($inclInvisible = false) {
        $sectors = Sector::find();
		if(!$inclInvisible) {
		   $sectors = $sectors->where(['visible' => true]);	
		}
        $sectors = $sectors->join('INNER JOIN', 'sector_risk', 'sector_risk.sector_id = sector.id')->andWhere(['sector_risk.risk_id' => $this->id]);
		$sectors = $sectors->limit(-1);
        $sectors = $sectors->orderBy(['name'=>SORT_ASC]);
        return $sectors->all();       
    }
    public function loadSectors() {
        $this->sector_ids = [];
        $this->_sectors = "";
        $relSectors = $this->inqRelatedSectors(); 
        foreach($relSectors as $sector) {
            $this->sector_ids[] = $sector['id'];
            $this->_sectors .= $sector['name'].', ';
        }  
        if(strlen($this->_sectors)>2) {
            $this->_sectors = substr($this->_sectors,0,strlen($this->_sectors)-2); 
        } else {
            $this->_sectors = " - ";
        }
    }
    public function saveSectors() {
        $connection = $this->getDb();
        $sql = "DELETE FROM sector_risk WHERE risk_id = :riskId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->sector_ids)) {
            foreach($this->sector_ids as $sector_id) {
                $sql = "INSERT INTO sector_risk (sector_id, risk_id) VALUES(:sectorId, :riskId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':sectorId', (int) $sector_id, PDO::PARAM_INT);
                $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getSectors() {
        return $this->_sectors;	
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
    public function loadZones() {
        $this->zone_ids = [];
        $this->_zones = "";
        $relZones = $this->inqRelatedZones(); 
        foreach($relZones as $zone) {
            $this->zone_ids[] = $zone['id'];
            $this->_zones .= $zone['name'].', ';
        }  
        if(strlen($this->_zones)>2) {
            $this->_zones = substr($this->_zones,0,strlen($this->_zones)-2); 
        } else {
            $this->_zones = " - ";
        }
    }
    public function saveZones() {
        $connection = $this->getDb();
        $sql = "DELETE FROM zone_risk WHERE risk_id = :riskId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->zone_ids)) {
            foreach($this->zone_ids as $zone_id) {
                $sql = "INSERT INTO zone_risk (zone_id, risk_id) VALUES(:zoneId, :riskId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':zoneId', (int) $zone_id, PDO::PARAM_INT);
                $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getZones() {
        return $this->_zones;	
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
    public function loadCountries() {
        $this->country_ids = [];
        $this->_countries = "";
        $relCountries = $this->inqRelatedCountries(); 
        foreach($relCountries as $country) {
            $this->country_ids[] = $country['id'];
            $this->_countries .= $country['name'].', ';
        }  
        if(strlen($this->_countries)>2) {
            $this->_countries = substr($this->_countries,0,strlen($this->_countries)-2); 
        } else {
            $this->_countries = " - ";
        }
    }
    public function saveCountries() {
        $connection = $this->getDb();
        $sql = "DELETE FROM country_risk WHERE risk_id = :riskId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->country_ids)) {
            foreach($this->country_ids as $country_id) {
                $sql = "INSERT INTO country_risk (country_id, risk_id) VALUES(:countryId, :riskId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':countryId', (int) $country_id, PDO::PARAM_INT);
                $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getCountries() {
        return $this->_countries;	
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
    public function loadLandscapes() {
        $this->landscape_ids = [];
        $this->_landscapes = "";
        $relLandscapes = $this->inqRelatedLandscapes(); 
        foreach($relLandscapes as $landscape) {
            $this->landscape_ids[] = $landscape['id'];
            $this->_landscapes .= $landscape['name'].', ';
        }  
        if(strlen($this->_landscapes)>2) {
            $this->_landscapes = substr($this->_landscapes,0,strlen($this->_landscapes)-2); 
        } else {
            $this->_landscapes = " - ";
        }
    }
    public function saveLandscapes() {
        $connection = $this->getDb();
        $sql = "DELETE FROM landscape_risk WHERE risk_id = :riskId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->landscape_ids)) {
            foreach($this->landscape_ids as $landscape_id) {
                $sql = "INSERT INTO landscape_risk (landscape_id, risk_id) VALUES(:landscapeId, :riskId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':landscapeId', (int) $landscape_id, PDO::PARAM_INT);
                $command->bindValue(':riskId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getLandscapes() {
        return $this->_landscapes;	
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
