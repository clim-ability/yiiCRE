<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\modules\translation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\translation\models\Language;

/**
 * Class LanguageSearch
 * @package app\modules\translation\models
 * @since 2.0
 */
class LanguageSearch extends Language
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['language', 'name', 'modified', 'iso'], 'safe'],
            [['visible'], 'boolean'],
            [['requests'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Language::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'visible' => $this->visible,
            'requests' => $this->requests,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'iso', $this->iso]);

        return $dataProvider;
    }
}
