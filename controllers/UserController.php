<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use app\models\User;
//use app\models\UserSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\NotFoundHttpException;
//use yii\data\ActiveDataProvider;
//use app\components\RssFormater;

/**
 * Class ResultController
 * @package app\controllers
 * @author Michael Kahle <michael,kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class UserController extends ControllerBase
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
                            'actions' => ['list', 'show', 'rss', 'present'],
                            'roles' => ['?', '@' ,'sysadmin', 'admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['new', 'modify', 'remove', 'history'],
                            'roles' => ['@','sysadmin', 'admin'],
                        ],
                    ], $this->crudRules()),
            ],
        ];
    }

    protected function newModel()
    {
        return new Result();
    }

    protected function newSearchModel()
    {
        return null;
        //return new UserSearch();
    }

    protected function findOneModel($id)
    {
        return User::findOne($id);
    }

    protected function getColumns()
    {
        return array_keys($this->getAllColumns());
    }

    protected function getMessages()
    {
        return [
            'title' => Yii::t('p:result', 'Users'),
            'item' => Yii::t('p:result', 'User'),
            'create' => Yii::t('p:result', 'Create User'),
        ];
    }

    
   

}
