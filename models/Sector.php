<?php

namespace app\models;

use Yii;
use PDO;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Sector extends ActiveRecord 
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
        return 'sector';
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
	
	public function inqAllSectors( $inclInvisible = false ) {
	    $sectors = Sector::find();
		if(!$inclInvisible) {
		   $sectors = $sectors->where(['visible' => true]);	
		}
		$sectors = $sectors->limit(-1);
        $sectors = $sectors->orderBy(['name'=>SORT_ASC]);
        return $sectors->all();
	}

    public function inqAllSectorsWithCountOfRisks( $dangerId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {
	    $sql = "SELECT sector.*, ca.counting AS counting "
            . " FROM sector "
            . " LEFT JOIN "
            . " (SELECT sector_risk.sector_id AS sid, count(*) as counting "
            . "  FROM sector_risk, country_risk, landscape_risk, danger_risk "
            . " WHERE danger_risk.risk_id = sector_risk.risk_id AND danger_risk.danger_id = :dangerId "
            . "  AND landscape_risk.risk_id = sector_risk.risk_id AND landscape_risk.landscape_id = :landscapeId "
            . "  AND country_risk.risk_id = sector_risk.risk_id AND country_risk.country_id = :countryId "            
            . " GROUP BY sector_risk.sector_id ) as ca "
            . " ON ca.sid = sector.id ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":dangerId", (int)$dangerId, PDO::PARAM_INT);
        $command->bindValue(":landscapeId", (int)$landscapeId, PDO::PARAM_INT);
        $command->bindValue(":countryId", (int)$countryId, PDO::PARAM_INT);
        $sectors = $command->queryAll();
        return $sectors;
    }

    public function inqAllSectorsWithCountOfAdaptions( $dangerId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {
	    $sql = "SELECT sector.*, ca.counting AS counting "
            . " FROM sector "
            . " LEFT JOIN "
            . " (SELECT sector_adaption.sector_id AS sid, count(*) as counting "
            . "  FROM sector_adaption, country_adaption, landscape_adaption, danger_adaption "
            . " WHERE danger_adaption.adaption_id = sector_adaption.adaption_id AND danger_adaption.danger_id = :dangerId "
            . "  AND landscape_adaption.adaption_id = sector_adaption.adaption_id AND landscape_adaption.landscape_id = :landscapeId "
            . "  AND country_adaption.adaption_id = sector_adaption.adaption_id AND country_adaption.country_id = :countryId "            
            . " GROUP BY sector_adaption.sector_id ) as ca "
            . " ON ca.sid = sector.id ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":dangerId", (int)$dangerId, PDO::PARAM_INT);
        $command->bindValue(":landscapeId", (int)$landscapeId, PDO::PARAM_INT);
        $command->bindValue(":countryId", (int)$countryId, PDO::PARAM_INT);
        $sectors = $command->queryAll();
        return $sectors;
    }


	
	public static function findById($id)
    {
        $sector = Sector::find()
            ->where(['id' => $id])
            ->one();
        return $sector;
    }
	
	public static function findByName($name)
    {
        $sector = Sector::find()
            ->where(['name' => $name])
			->orderBy(['id'=>SORT_DESC])
            ->one();
        return $sector;
    }
	
	public static function findBy($idOrName)
	{
		$sector = NULL;
		if(is_numeric($idOrName))
		{
		   $sector = Sector::findById((int)$idOrName);	
		} 
		elseif(is_string($idOrName)) 
		{
		   $sector = Sector::findByName($idOrName);
		}
		return $sector;
	}	
  
public function search($params)
    {
        $query = Sector::find();

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
