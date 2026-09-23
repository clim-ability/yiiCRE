<?php
/**
 * @copyright 2019 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use app\models\Station;
use app\controllers\ControllerBase;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class HazardController
 * @package app\modules\grouping\controllers
 * @since 2.0
 */
class StationController extends ControllerBase
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
        return Station::findOne($id);
    }

    protected function newModel()
    {
        return new Station();
    }

    protected function newSearchModel()
    {
        //return new HazardSearch(); 
		return new Station(); 
    }

    protected function getColumns()
    {
        if ($model = $this->newModel()) {
            if ($schema = $model->getTableSchema()) {
                $columns = $schema->columns;
                unset($columns['location']);
                $columns['latitude'] = (object)['type'=>'double', 'phpType'=>'double', 'name'=>'latitude'];
                $columns['longitude'] = (object)['type'=>'double', 'phpType'=>'double', 'name'=>'longitude'];
                return(array_keys($columns));

            }
        }
    }

    protected function getAllColumns()
    {
        if ($model = $this->newModel()) {
            if ($schema = $model->getTableSchema()) {
                //var_dump($schema->columns);
                $columns = $schema->columns;
                unset($columns['location']);
                $columns['latitude'] = (object)['type'=>'double', 'phpType'=>'double', 'name'=>'latitude'];
                $columns['longitude'] = (object)['type'=>'double', 'phpType'=>'double', 'name'=>'longitude'];
                unset($columns['id']);
                unset($columns['created_at']);
                unset($columns['updated_at']);
                //var_dump($columns);
                return($columns);

            }
        }
    }    

    protected function getMessages()
    {
        return [
            'title' => 'Stations', // Yii::t('p:quote', 'Quotes'),
            'item' => 'Station', // Yii::t('p:quote', 'Quote'),
            'create' => 'Create Station', // Yii::t('p:quote', 'Create Quote'),
        ];
    }
	

}
