<?php
/**
 * @copyright 2019 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use app\models\Risk;
use app\controllers\ControllerBase;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class HazardController
 * @package app\modules\grouping\controllers
 * @since 2.0
 */
class RiskController extends ControllerBase
{


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => array_merge(
                    [
                        'remove' => ['post', 'delete'],
                    ], $this->crudVerbs()
                ),
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => array_merge(
                    [
                        [
                            'allow' => true,
                            'actions' => ['list', 'show', 'new' ,'modify', 'remove', 'translate'],
                            'roles' => ['@','sysadmin','admin'],
                        ],
                    ], $this->crudRules()),
            ],
        ];
    }

    protected function findOneModel($id)
    {
        return Risk::findOne($id);
    }

    protected function newModel()
    {
        return new Risk();
    }

    protected function newSearchModel()
    {
        //return new HazardSearch(); 
		return new Risk(); 
    }

    protected function getColumns()
    {
        return array_keys($this->getAllColumns());
    }

    protected function getMessages()
    {
        return [
            'title' => 'Risks', // Yii::t('p:quote', 'Quotes'),
            'item' => 'Risk', // Yii::t('p:quote', 'Quote'),
            'create' => 'Create Risk', // Yii::t('p:quote', 'Create Quote'),
        ];
    }

}