<?php
/**
 * @copyright 2019 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use app\models\Section;
use app\controllers\ControllerBase;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class HazardController
 * @package app\modules\grouping\controllers
 * @since 2.0
 */
class SectionController extends ControllerBase
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
        return Zone::findOne($id);
    }

    protected function newModel()
    {
        return new Zone();
    }

    protected function newSearchModel()
    {
        //return new HazardSearch(); 
		return new Zone(); 
    }

    protected function getColumns()
    {
        return array_keys($this->getAllColumns());
    }

    protected function getMessages()
    {
        return [
            'title' => 'Zones', // Yii::t('p:quote', 'Quotes'),
            'item' => 'Zone', // Yii::t('p:quote', 'Quote'),
            'create' => 'Create Zone', // Yii::t('p:quote', 'Create Quote'),
        ];
    }

}
