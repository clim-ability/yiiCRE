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
class MessageSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_message_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['message', 'category'], 'required'],
            [['message', 'category'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('m:common', 'ID'),
            'message' => Yii::t('m:language', 'Message'),
            'category' => Yii::t('m:language', 'Category'),
        ];
    }

   
    
}
