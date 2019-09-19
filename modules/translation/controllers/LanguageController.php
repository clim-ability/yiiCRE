<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */
namespace app\modules\translation\controllers;

use app\controllers\ControllerBase;
//use app\modules\user\models\Profile;
use app\modules\translation\models\Language;
use app\modules\translation\models\LanguageSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\modules\translation\models\MessageTarget;
use yii\data\ActiveDataProvider;
use app\components\RssFormater;

/**
 * Class LanguageController
 * @package app\modules\translation\controllers
 * @since   2.0
 */
class LanguageController extends ControllerBase
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => $this->crudVerbs(),
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => array_merge(
                    [
                        [
                            'allow' => true,
                            'actions' => [
                                'rss',
                                'add-translation',
                                'get-translation',
                                'get-languages',
                                'statistics',
                                'translate',
								'whole-category',
								'add-missing-translation'
                            ],
                            'roles' => ['?', '@'],
                        ],
                    ], $this->crudRules()),
            ],
        ];
    }

    protected function newModel()
    {
        return new Language();
    }

    protected function newSearchModel()
    {
        return new LanguageSearch();
    }

    protected function findOneModel($id)
    {
        return Language::findOne($id);
    }

    protected function getColumns()
    {
        return array_keys($this->getAllColumns());
    }

    protected function getMessages()
    {
        return [
            'title' => Yii::t('p:language', 'Languages'),
            'item' => Yii::t('p:language', 'Language'),
            'create' => Yii::t('p:language', 'Create Language'),
        ];
    }

    public function actionGetLanguages()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Language::getAllVisibleLanguagesAsJson();
    }

    public function actionStatistics()
    {

        $properties = [
            ['identifier' => 'Language', 'label' => 'Language', 'type' => 1, 'choice_labels' => null],
            ['identifier' => 'Name', 'label' => 'Name', 'type' => 1, 'choice_labels' => null],
            ['identifier' => 'Requests', 'label' => 'Requests', 'type' => 4, 'choice_labels' => null],
            ['identifier' => 'Request Date', 'label' => 'Request Date', 'type' => 7, 'choice_labels' => null],
            ['identifier' => 'Visible', 'label' => 'Visible', 'type' => 3, 'choice_labels' => [0 => 'no', 1 => 'yes']],
            //['identifier' => 'Events', 'label' => 'Events', 'type' => 2, 'choice_labels' => null],

        ];

        $languages = Language::find()->all();
        $data = [];
        foreach ($languages as $language) {
            $data[] = [
                Yii::t("m:language-names", $language->language),
                $language->name,
                $language->requests,
                date('Y-m-d', strtotime($language->modified)),
                ($language->visible ? 1 : 0),
                // Translated messages
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return ['options' => [], 'properties' => $properties, 'data' => $data];
    }

    public function actionTranslate($category = "", $message = "")
    {

        $translation = Array();
        $currentLanguage = Yii::$app->language;
        $translation['current'] = $currentLanguage;
        $translation['visible'] = Language::getVisibleLanguages();
        $translation['all'] = Language::getAllLanguages();
        $translation['currentFull'] = $translation['all'][$currentLanguage];

        $translation['user'] = $currentLanguage;
        //$userLanguage = Profile::find()->where(["user_id" => Yii::$app->user->id])->one()->translate;
        //if (is_string($userLanguage)) {
        //    $translation['user'] = $userLanguage;
        //}
        $translation['categories'] = Language::getAllCategories($translation['user']);
        $translation['category'] = $category;
        $translation['messages'] = Language::getAllMessagesOfCategoryAndLanguage($category, $currentLanguage, $translation['user']);
        $translation['message'] = Language::getMessageIdByCategoryAndMessage($category, $message);
        $translated = Language::getTranslationById($translation['message'], $translation['user']);
        $translation['target'] = ' ';
        if (sizeof($translated) > 0) {
            $translation['target'] = $translated[0]['translation'];
        }
        $translation['suggestions'] = Language::getSuggestionsById($translation['message'], $translation['user']);

        return $this->renderPartial('/_languageTranslate', array(
            'translation' => $translation,
        ));
    }

    public function actionWholeCategory($category = '', $language='en')
	{
	   $result = Language::getAllTranslationsOfCategoryAndLanguage($category, $language);
	   \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');	   
       \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       return $result;
	}

    public function actionAddMissingTranslation()
	{
	   $request = Yii::$app->request;
       if($request->isPost)	{   
	     $message = $request->post('message');
		 $translation = $request->post('translation');
		 $language = $request->post('language');
		 $category = $request->post('category');
	     $result = Language::addTranslation($message, $language, $translation, $category);
	     \Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');  //remove	   
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         return true;
	   }
	   return false;
	}

    public function actionGetTranslation($lang = "", $id = "")
    {
        $translation = ' ';
        $result = Language::getTranslationById($id, $lang);
        if (sizeof($result) > 0) {
            $translation = $result[0]['translation'];
        }
        $suggestions = '';
        $result = Language::getSuggestionsById($id, $lang);
        if (sizeof($result) > 0) {
            foreach ($result as $suggestion) {
                $suggestions .= "<li>" . $suggestion['translation'] . "</li>";
            }
        }
        $data = array("feedback" => Language::t('p:translate', 'Text elements in {brackets} must not be translated.'),
            "translation" => $translation, "suggestions" => $suggestions);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $data;
    }

    public function actionAddTranslation($lang = "", $id = "", $translation = "")
    {
        $result = Language::t('p:translate', 'Nothing added');
        $category = '';
        $message = '';
        if (Yii::$app->request->post()) {
            $result = Language::addTranslationById($id, $lang, $translation);
            $existingSource = Language::getExistingSourceById($id);
            if (sizeof($existingSource) > 0) {
                $category = $existingSource[0]['category'];
                $message = $existingSource[0]['message'];
            }
        }
        $data = array("feedback" => $result, "category" => $category, "message" => $message, "translation" => $translation);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $data;
    }
    
    
    public function actionRss()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MessageTarget::find()
                        ->orderBy(['modified' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);
        $title = 'Translations';
        $channelDefinition = RssFormater::getRssChannelDefinition($title);
        $itemDefinition = MessageTarget::getRssItemDefinition();
        $feed = [
            'dataProvider' => $dataProvider,
            'channel' => $channelDefinition,
            'items' => $itemDefinition,
        ];
        Yii::$app->response->format = 'rss';                
        return $feed;
    }    

}
