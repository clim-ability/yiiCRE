<?php
/**
 * @copyright 2019 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use app\models\Risk;
use app\models\Zone;
use app\controllers\ControllerBase;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\utils\CsvExport;

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
                            'actions' => ['list', 'csv', 'show', 'new' ,'modify', 'remove', 'translate'],
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
        $columns = $this->getAllColumns();
        $columns['dangers'] = (object)['type'=>'string', 'phpType'=>'string','name'=>'dangers'];
        $columns['sectors'] = (object)['type'=>'string', 'phpType'=>'string','name'=>'sectors'];
        $columns['countries'] = (object)['type'=>'string', 'phpType'=>'string','name'=>'countries'];
        $columns['landscapes'] = (object)['type'=>'string', 'phpType'=>'string','name'=>'landscapes'];
        $columns['zones'] = (object)['type'=>'string', 'phpType'=>'string','name'=>'zones'];
        return array_keys($columns);
    }

    protected function getMessages()
    {
        return [
            'title' => 'Risks', // Yii::t('p:quote', 'Quotes'),
            'item' => 'Risk', // Yii::t('p:quote', 'Quote'),
            'create' => 'Create Risk', // Yii::t('p:quote', 'Create Quote'),
        ];
    }

    public function actionCsv()
    {
        $risks = Risk::inqAllRisks();
        //var_dump($risks);
        $data = [];
        foreach($risks as $risk) {
            $item=[];
            $item['id'] = $risk['id'];
            $item['visible'] = $risk['visible'] ? 'visible' : 'hidden';
            $item['name'] = $risk['name'];
            $item['negative'] = $risk['negative'] ? 'negativ' : 'positiv'; 

            $item['dangers'] = $risk['dangers'];
            $item['sectors'] = $risk['sectors'];            
            $item['countries'] = $risk['countries'];
            $item['landscapes'] = $risk['landscapes'];
            
            $item['description'] = $risk['description'];
            $item['details'] = $risk['details'];
            $item['zones'] = $risk['zones'];

            $data[] = $item;
        }
	    CsvExport::export($data,"impacts.csv" );
	}

    public function actionCreate()
    {
        $model = $this->newModel();
        $columns = $this->getAllColumns();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$model->saveZones();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'columns' => $columns,
                'messages' => $this->getMessages(),
                'messages' => $this->getMessages(),
                'dangers' => Danger::inqAllDangers(),
                'sectors' => Sector::inqAllSectors(),
                'countries' => Country::inqAllCountries(),
                'landscapes' => Zone::inqAllLandscapes(),
                'zones' => Zone::inqAllZones()
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $columns = $this->getAllColumns();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$model->saveZones();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //$model->loadZones();
            return $this->render('update', [
                'model' => $model,
                'columns' => $columns,
                'messages' => $this->getMessages(),
                'messages' => $this->getMessages(),
                'dangers' => Danger::inqAllDangers(),
                'sectors' => Sector::inqAllSectors(),
                'countries' => Country::inqAllCountries(),
                'landscapes' => Zone::inqAllLandscapes(),
                'zones' => Zone::inqAllZones()
            ]);
        }
    }



}
