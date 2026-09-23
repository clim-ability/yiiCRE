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
use app\models\User;
/**
 * Class Language
 * @package app\modules\translation\models
 * @since 2.0
 */
class MessageTarget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'table_message_target';
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
            [['voting'], 'integer'],
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
    
    public function getMessageSuggestions()
    {
        return $this->hasMany(NessageSuggestion::className(), ['id' => 'id', 'language'=>'language'])->orderBy('translation');
    }
    
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }    
    
    public static function getRssItemDefinition() {
        return [
                'title' => function ($model, $widget) {
                    return $model->id.'['.$model->language.']'.$model->messageSource->message;
                },
                'description' => function ($model, $widget) {
                    return '['.$model->language.'] '.$model->translation.' ('.$model->voting.')';
                },
                'link' => function ($model, $widget) {
                    return Url::toRoute(['/translation/language/show', 'id' => $model->id, 'language'=>$model->language], true);
                },
                'author' => function ($model, $widget) {
                    if (!is_null($model->created_by)) {
                      return $model->createdBy->fullName;
                    }
                },
                'guid' => function ($model, $widget) {
                    $str = $model->modified;
                    return Url::toRoute(['/translation/language/show', 'id' => $model->id, 'language'=>$model->language], true) . ' ' . $str;
                },
                'pubDate' => function ($model, $widget) {
                    $date = $date = strtotime($model->modified);
                    return date(DATE_ATOM, $date);
                }
                ];
    }    
    
    
}
