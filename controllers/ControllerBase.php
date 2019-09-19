<?php
/**
 * @copyright 2018 University Library of Freiburg
 * @copyright 2018 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use app\modules\user\models\Profile;
use app\modules\translation\models\Language;

/**
 * Class ControllerBase
 * @package app\controllers
 * @author Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class ControllerBase extends Controller
{
    public $sidemenu = array();

    
    protected function newSearchModel()
    {
        return null;
    }

    protected function newModel()
    {
        return null;
    }

    protected function findOneModel($id)
    {
        return null;
    }

    protected function getAllColumns()
    {
        if ($model = $this->newModel()) {
            if ($schema = $model->getTableSchema()) {
                return $schema->columns;
            }
        }
    }

    protected function getColumns()
    {
        return null;
    }

    protected function getMessages()
    {
        return [
            'title' => 'Items',
            'item' => 'Item',
            'create' => 'Create Item',
        ];
    }

    protected function crudRules()
    {
        $rules = [
            [
                'allow' => true,
                'actions' => ['index', 'view'],
                'roles' => ['@', '?']
            ],
            [
                'allow' => true,
                'actions' => ['create', 'update'],
                'roles' => ['@']  // 'sysadmin'
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => ['sysadmin']  // 'sysadmin'
            ],
        ];

        return $rules;
    }

    protected function crudVerbs()
    {
        $verbs = [
            'index' => ['get'],
            'view' => ['get'],
            'create' => ['get', 'post'],
            'update' => ['get', 'put', 'post'],
            'delete' => ['post', 'delete'],
        ];

        return $verbs;
    }

    function init() {
        parent::init();
        $this->handleLanguage();
        $this->handleLoginRedirects();
    }
    
    function handleLanguage() {
        $app = Yii::$app;
        $httpRequest = $app->getRequest();
        $app->language = 'en';
        $allowedLanguages = Language::getVisibleLanguages();
        $preferedLanguages = $httpRequest->getAcceptableLanguages();        
        $language = $httpRequest->getPreferredLanguage();
        if ($language) {
            $language = substr($language,0,2);
            if (array_key_exists($language, $allowedLanguages)) {
                $app->language = $language;
            }
        }
        $firstVisit = TRUE;
        /*
        if (is_int($app->user->id)) {
            $language = Profile::find()->where(["user_id"=> $app->user->id])->one()->language; //todo
            $firstVisit = FALSE;
            if (array_key_exists($language, $allowedLanguages)) {
                $app->language = $language;
            }
        }
       */       
        if (isset($app->session['_lang'])) {
            $language = $app->session['_lang'];
            $firstVisit = FALSE;
            if (array_key_exists($language, $allowedLanguages)) {
                $app->language = $language;
            }
        } 
        /*
        if ($firstVisit) {
            // language was never set before
            $languageWeight = 1.0;
            $ipAddress = $httpRequest->getUserIP();
            if (!in_array($ipAddress, array("74.86.158.106", "74.86.158.110"))) {
                foreach ($preferedLanguages as $preferedLanguage) {                    
                    $languageId = strtolower( substr($preferedLanguage, 0,2));
                    if (preg_match("/^[a-z]{2,3}$/", $languageId) and ! in_array($languageId, array("by", "kz", "ua"))) {
                        Language::voteLanguage($languageId, $languageId, $languageWeight);
                    }
                    $languageWeight *= 0.5;
                }
            }
        }
        */
        $language = $httpRequest->post('_lang', $app->language);
        
        if (array_key_exists($language, $allowedLanguages)) {
            $app->language = $language;
        }

        $app->session['_lang'] = $app->language;  
        // URL query - don't add to session!
        $language = $httpRequest->get('_lang', $app->language);
        if (array_key_exists($language, $allowedLanguages)) {
            $app->language = $language;
        }
    }

    function handleLoginRedirects() {
        $this->on( 'beforeAction', function ($event) {
            //if (Yii::$app->getUser()->isGuest) {
                $request = Yii::$app->getRequest();
                // dont remember login page or ajax-request
                if ( !($request->getIsAjax()
                       || strpos($request->getUrl(), 'login') !== false
                       || strpos($request->getUrl(), 'captcha') !== false)) {
                   Yii::$app->getUser()->setReturnUrl($request->getUrl());
                }
            //}
        });
    }

    /**
     * List all models
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = $this->newSearchModel();
        $columns = $this->getColumns();
        if ($searchModel !== null) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render(is_null($columns) ? 'index' : '@app/views/base/index', [
                'columns' => $columns,
                'messages' => $this->getMessages(),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * List all models
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTranslate()
    {
        $searchModel = $this->newSearchModel();
        $columns = $this->getColumns();
        if ($searchModel !== null) {
            $dataProvider = $searchModel->search([]);
            return $this->render('@app/views/base/translate', [
                'columns' => $columns,
                'messages' => $this->getMessages(),
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Displays a single model.
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $columns = $this->getAllColumns();

        return $this->render(is_null($columns) ? 'view' : '@app/views/base/view', [
            'model' => $this->findModel($id),
            'columns' => $columns,
            'messages' => $this->getMessages(),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = $this->newModel();
        $columns = $this->getAllColumns();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(is_null($columns) ? 'create' : '@app/views/base/create', [
                'model' => $model,
                'columns' => $columns,
                'messages' => $this->getMessages(),
            ]);
        }
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $columns = $this->getAllColumns();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(is_null($columns) ? 'update' : '@app/views/base/update', [
                'model' => $model,
                'columns' => $columns,
                'messages' => $this->getMessages(),
            ]);
        }
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.*
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return model
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = $this->findOneModel($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
