<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\modules\translation\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use PDO;
use app\modules\translation\widgets\LanguageTranslate;
use app\modules\user\models\Profile;
/**
 * Class Language
 * @package app\modules\translation\models
 * @since 2.0
 */
class MessageSuggestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_message_suggestion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by'], 'integer'],
            [['language', 'translation'], 'required'],
            [['translation'], 'string'],
            [['modified'], 'safe'],
            [['language'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('m:common', 'ID'),
            'language' => Yii::t('m:language', 'Language'),
            'translation' => Yii::t('m:language', 'Translation'),
            'voting' => Yii::t('m:language', 'Voting'),
            'modified' => Yii::t('m:language', 'Modified'),
        ];
    }


    public function getMessageSource()
    {
        return $this->hasOne(MessageSource::className(), ['id' => 'id']);
    }   
    
}
