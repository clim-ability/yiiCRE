<?php

namespace app\models;

use Yii;
use PDO;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class Danger extends ActiveRecord 
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
        return 'danger';
    }

    public function fields()
    {
        $fields = parent::fields();
		$fields[] = 'label';
        $fields[] = 'description';
		return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->label = $this->name;
        $this->description = $this->name;
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
	
	public function inqAllDangers( $inclInvisible = false ) {
	    $dangers = Danger::find();
		if(!$inclInvisible) {
		   $dangers = $dangers->where(['visible' => true]);	
		}
		$dangers = $dangers->limit(-1);
        $dangers = $dangers->orderBy(['name'=>SORT_ASC]);
        return $dangers->all();
	}
	
    public function inqAllDangersWithCount( $sectorId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {


    }

    public function inqAllDangersWithCountOfRisks( $sectorId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {
	    $sql = "SELECT danger.*, ca.counting AS counting "
            . " FROM danger "
            . " LEFT JOIN "
            . " (SELECT danger_risk.danger_id AS did, count(*) as counting "
            . "  FROM danger_risk, country_risk, landscape_risk, sector_risk "
            . " WHERE sector_risk.risk_id = danger_risk.risk_id AND sector_risk.sector_id = :sectorId "
            . "  AND landscape_risk.risk_id = danger_risk.risk_id AND landscape_risk.landscape_id = :landscapeId "
            . "  AND country_risk.risk_id = danger_risk.risk_id AND country_risk.country_id = :countryId "            
            . " GROUP BY danger_risk.danger_id ) as ca "
            . " ON ca.did = danger.id ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":sectorId", (int)$sectorId, PDO::PARAM_INT);
        $command->bindValue(":landscapeId", (int)$landscapeId, PDO::PARAM_INT);
        $command->bindValue(":countryId", (int)$countryId, PDO::PARAM_INT);
        $sectors = $command->queryAll();
        return $sectors;
    }

    public function inqAllDangersWithCountOfAdaptions( $sectorId=NULL, $landscapeId=NULL, $countryId=NULL, $inclInvisible = false ) {
	    $sql = "SELECT danger.*, ca.counting AS counting "
            . " FROM danger "
            . " LEFT JOIN "
            . " (SELECT danger_adaption.danger_id AS did, count(*) as counting "
            . "  FROM danger_adaption, country_adaption, landscape_adaption, sector_adaption "
            . " WHERE sector_adaption.adaption_id = danger_adaption.adaption_id AND sector_adaption.sector_id = :sectorId "
            . "  AND landscape_adaption.adaption_id = danger_adaption.adaption_id AND landscape_adaption.landscape_id = :landscapeId "
            . "  AND country_adaption.adaption_id = danger_adaption.adaption_id AND country_adaption.country_id = :countryId "            
            . " GROUP BY danger_adaption.danger_id ) as ca "
            . " ON ca.did = danger.id ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":sectorId", (int)$sectorId, PDO::PARAM_INT);
        $command->bindValue(":landscapeId", (int)$landscapeId, PDO::PARAM_INT);
        $command->bindValue(":countryId", (int)$countryId, PDO::PARAM_INT);
        $sectors = $command->queryAll();
        return $sectors;
    }




	public static function findById($id)
    {
        $danger = Danger::find()
            ->where(['id' => $id])
            ->one();
        return $danger;
    }
	
	public static function findByName($name)
    {
        //var_dump($name);
        //var_dump(html_entity_decode($name));
        //var_dump(htmlentities($name));
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
  
public function search($params)
    {
        $query = Danger::find();

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
