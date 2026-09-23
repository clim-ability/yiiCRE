<?php
/**
 * @copyright 2019 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use app\models\Adaption;
use app\models\Danger;
use app\models\Sector;
use app\models\Country;
use app\models\Landscape;
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
class AdaptionController extends ControllerBase
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
        return Adaption::findOne($id);
    }

    protected function newModel()
    {
        return new Adaption();
    }

    protected function newSearchModel()
    {
        //return new HazardSearch(); 
		return new Adaption(); 
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
            'title' => 'Adaptions', // Yii::t('p:quote', 'Quotes'),
            'item' => 'Adaption', // Yii::t('p:quote', 'Quote'),
            'create' => 'Create Adaption', // Yii::t('p:quote', 'Create Quote'),
        ];
    }

    public function actionCsv()
    {
        $adaptions = Adaption::inqAllAdaptions();
        $data = [];
        foreach($adaptions as $adaption) {
            $item=[];
            $item['id'] = $adaption['id'];
            $item['visible'] = $adaption['visible'] ? 'visible' : 'hidden';
            $item['name'] = $adaption['name'];

            $item['dangers'] = $adaption['dangers'];
            $item['sectors'] = $adaption['sectors'];            
            $item['countries'] = $adaption['countries'];
            $item['landscapes'] = $adaption['landscapes'];
            
            $item['description'] = $adaption['description'];
            $item['details'] = $adaption['details'];
            $item['zones'] = $adaption['zones'];

            $data[] = $item;
        }
	    CsvExport::export($data,"adaptations.csv" );
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
                'dangers' => Danger::inqAllDangers(),
                'sectors' => Sector::inqAllSectors(),
                'countries' => Country::inqAllCountries(),
                'landscapes' => Landscape::inqAllLandscapes(),
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
                'landscapes' => Landscape::inqAllLandscapes(),
                'zones' => Zone::inqAllZones()
            ]);
        }
    }



}
