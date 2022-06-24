<?php

namespace app\models;

use Yii;
use PDO;
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
        return 'adaption';
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
            [['name'], 'string'],
            [['description'], 'string'],
            [['details'], 'string'],
            //[['label'], 'safe']
			// [['label', 'description'], 'safe']
            [['danger_ids', 'sector_ids', 'landscape_ids', 'country_ids', 'zone_ids'], 'safe'],
            [['danger_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Danger::className(), 'targetAttribute'=>'id']],
			[['sector_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Sector::className(), 'targetAttribute'=>'id']],
            [['landscape_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Landscape::className(), 'targetAttribute'=>'id']],
            [['country_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Country::className(), 'targetAttribute'=>'id']],
			[['zone_ids'], 'each', 'rule'=>['exist', 'targetClass'=>Zone::className(), 'targetAttribute'=>'id']]            
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
        $dangers = $dangers->join('INNER JOIN', 'danger_adaption', 'danger_adaption.danger_id = danger.id')->andWhere(['danger_adaption.adaption_id' => $this->id]);
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
        $sql = "DELETE FROM danger_adaption WHERE adaption_id = :adaptionId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->danger_ids)) {
            foreach($this->danger_ids as $danger_id) {
                $sql = "INSERT INTO danger_adaption (danger_id, adaption_id) VALUES(:dangerId, :adaptionId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':dangerId', (int) $danger_id, PDO::PARAM_INT);
                $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT); 
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
        $sectors = $sectors->join('INNER JOIN', 'sector_adaption', 'sector_adaption.sector_id = sector.id')->andWhere(['sector_adaption.adaption_id' => $this->id]);
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
        $sql = "DELETE FROM sector_adaption WHERE adaption_id = :adaptionId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->sector_ids)) {
            foreach($this->sector_ids as $sector_id) {
                $sql = "INSERT INTO sector_adaption (sector_id, adaption_id) VALUES(:sectorId, :adaptionId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':sectorId', (int) $sector_id, PDO::PARAM_INT);
                $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT); 
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
        $zones = $zones->join('INNER JOIN', 'zone_adaption', 'zone_adaption.zone_id = zone.id')->andWhere(['zone_adaption.adaption_id' => $this->id]);
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
        var_dump('SAVE');
        $connection = $this->getDb();
        $sql = "DELETE FROM zone_adaption WHERE adaption_id = :adaptionId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->zone_ids)) {
            foreach($this->zone_ids as $zone_id) {
                $sql = "INSERT INTO zone_adaption (zone_id, adaption_id) VALUES(:zoneId, :adaptionId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':zoneId', (int) $zone_id, PDO::PARAM_INT);
                $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getZones() {
	    //$this->loadZones();
        return $this->_zones;	
        return "Hello";
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
        $sql = "DELETE FROM country_adaption WHERE adaption_id = :adaptionId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->country_ids)) {
            foreach($this->country_ids as $country_id) {
                $sql = "INSERT INTO country_adaption (country_id, adaption_id) VALUES(:countryId, :adaptionId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':countryId', (int) $country_id, PDO::PARAM_INT);
                $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT); 
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
        $landscapes = $landscapes->join('INNER JOIN', 'landscape_adaption', 'landscape_adaption.landscape_id = landscape.id')->andWhere(['landscape_adaption.adaption_id' => $this->id]);
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
        $sql = "DELETE FROM landscape_adaption WHERE adaption_id = :adaptionId";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT);
        $command->execute();
        if(is_array($this->landscape_ids)) {
            foreach($this->landscape_ids as $landscape_id) {
                $sql = "INSERT INTO landscape_adaption (landscape_id, adaption_id) VALUES(:landscapeId, :adaptionId);";
                $command = Yii::$app->db->createCommand($sql);
                $command->bindValue(':landscapeId', (int) $landscape_id, PDO::PARAM_INT);
                $command->bindValue(':adaptionId', (int) $this->id, PDO::PARAM_INT); 
                $command->execute();
            }
        }
    }
    public function getLandscapes() {
        return $this->_landscapes;	
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
