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
use PDO;
use app\modules\translation\widgets\LanguageTranslate;
use app\modules\user\models\Profile;
use app\components\utils\CurlHandler;
use app\models\User;


/**
 * Class Language
 * @package app\modules\translation\models
 * @since 2.0
 */
class Language extends \yii\db\ActiveRecord
{
	/*
	public static function getDb() 
	{
        return Yii::$app->pgsql_cre;
    }
	*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            [['name'], 'string'],
            [['visible'], 'boolean'],
            [['requests'], 'number'],
            [['modified'], 'safe'],
            [['language', 'iso'], 'string', 'max' => 16]
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
            'name' => Yii::t('m:language', 'Name'),
            'visible' => Yii::t('m:language', 'Visible'),
            'requests' => Yii::t('m:language', 'Requests'),
            'modified' => Yii::t('m:language', 'Modified'),
            'iso' => Yii::t('m:language', 'Iso'),
        ];
    }

    public static function findCurrentLanguage()
    {
        $key = 'Tag::findCurrentLanguage(' . Yii::$app->language . ')';
        $tag = Yii::$app->cache->get($key);
        if ($tag === false) {
            $tag = Language::find()
                ->where(['language' => substr(Yii::$app->language, 0, 2)])
                ->orderBy('id')
                ->one();
            Yii::$app->cache->set($key, $tag, mt_rand(3600, 7200));
        }
        return $tag;
    }

    public static function t($c, $m, $p = [], $l = NULL) {
        return LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p, 'language' => $l]);
    }

    public static function getAllLanguages($minRequests = 1.0)
    {
        // Return all Languages, that are known
        $sql = 'SELECT language, name
                FROM language WHERE requests > :minimum
                ORDER BY name';
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':minimum', (float)$minRequests);
        $languages = $command->queryAll();
		$result = [];
        foreach ($languages as $language) {
            $result[$language['language']] = $language['name'];
        }

        return $result;
    }

    public static function getVisibleLanguages()
    {
        // Return only Languages, that are visible to UI
        $sql = 'SELECT language, 
                       name
                FROM language 
                WHERE visible
                ORDER BY name';
        
        $command = Yii::$app->db->createCommand($sql);
        $languages = $command->queryAll();
        foreach ($languages as $language) {
            $result[$language['language']] = $language['name'];
        }

        return $result;
    }

    public static function getAllLanguagesAndNone($minRequests = 1.0)
    {
        $result = Language::getAllLanguages($minRequests);
        $result['None'] = 'None';

        return $result;
    }
    
    /**
     * Returns all languages as multidimensional Array:
     * [["code"=>"cs","name" => "Czech"],["code" => "da","name" => "Danish"]]
     *
     * @param float $minRequests
     *
     * @return array
     */
    public static function getAllLanguagesAsJson($minRequests = 1.0)
    {

        $sql = 'SELECT language, name
                FROM language WHERE requests > :minimum
                ORDER BY name';

        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':minimum', (float)$minRequests);
        $languages = $command->queryAll();

        Yii::trace("sql = {$sql}", __METHOD__);
        $result = [];

        foreach ($languages as $language) {

            $result = array_merge($result, array(array("code" => $language['language'], "name" => $language['name'])));
        }

        return $result;
    }
  
    public static function getAllVisibleLanguagesAsJson($minRequests = 1.0)
    {
        $sql = 'SELECT language, 
                       name
                FROM language 
                WHERE visible
                ORDER BY name';
        
        $command = Yii::$app->db->createCommand($sql);
        $languages = $command->queryAll();
        $result = [];
        foreach ($languages as $language) {
            $result = array_merge($result, array(array("code" => $language['language'], "name" => $language['name'])));
        }
        return $result;
    }    
    

    /**
     *
     * @param type $category
     * @param type $message
     * @param type $translation
     * @param type $language
     */
    public static function updateTranslation($category, $message, $translation, $language)
    {
        $sql = "UPDATE table_message_target "
            . " SET translation=:translation, modified=now(), created_by=:userId "
            . " WHERE language = :language AND id IN "
            . " (SELECT id FROM table_message_source WHERE category = :category AND message = :message)";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":userId", Yii::$app->user->id, PDO::PARAM_INT);
        $command->bindValue(":category", $category, PDO::PARAM_STR);
        $command->bindValue(":message", $message, PDO::PARAM_STR);
        $command->bindValue(":translation", $translation, PDO::PARAM_STR);
        $command->bindValue(":language", $language, PDO::PARAM_STR);
        $result = $command->execute();
        // if update was not possible, add new translation
        $sql = "INSERT INTO table_message_target "
            . " (id, language, translation, modified, created_by ) "
            . " SELECT id, :language1, :translation, now(), :userId FROM table_message_source WHERE category = :category1 AND message = :message1 "
            . " AND NOT EXISTS "
            . " (SELECT 1 FROM table_message_target, table_message_source "
            . " WHERE category = :category2 AND message = :message2 "
            . " AND language = :language2 AND table_message_target.id = table_message_source.id ) ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":userId", Yii::$app->user->id, PDO::PARAM_INT);
        $command->bindValue(":category1", $category, PDO::PARAM_STR);
        $command->bindValue(":message1", $message, PDO::PARAM_STR);
        $command->bindValue(":translation", $translation, PDO::PARAM_STR);
        $command->bindValue(":language1", $language, PDO::PARAM_STR);

        $command->bindValue(":language2", $language, PDO::PARAM_STR);
        $command->bindValue(":category2", $category, PDO::PARAM_STR);
        $command->bindValue(":message2", $message, PDO::PARAM_STR);
        $result = $command->execute();
    }


    /**
     *
     * @param type $category
     * @param type $oldMessage
     * @param type $newMessage
     */
    public static function updateMessage($category, $oldMessage, $newMessage)
    {
        $sql = "UPDATE table_message_source "
            . " SET message=:new "
            . " WHERE category = :category AND message = :old ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":category", $category, PDO::PARAM_STR);
        $command->bindValue(":new", $newMessage, PDO::PARAM_STR);
        $command->bindValue(":old", $oldMessage, PDO::PARAM_STR);
        $result = $command->execute();

        // if update was not possible, create new messages
        $sql = "INSERT INTO table_message_source "
            . " (category, message) "
            . " SELECT :category1, :new1 "
            . " WHERE NOT EXISTS "
            . " (SELECT 1 FROM table_message_source WHERE category = :category2 AND message = :new2) ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(":category1", $category, PDO::PARAM_STR);
        $command->bindValue(":new1", $newMessage, PDO::PARAM_STR);
        $command->bindValue(":category2", $category, PDO::PARAM_STR);
        $command->bindValue(":new2", $newMessage, PDO::PARAM_STR);
        $result = $command->execute();
    }
     
    public static function getAllCategories($language = 'en') {
        $sql = 'SELECT category, count (*) AS counting FROM table_message_source GROUP BY category ORDER BY counting DESC';
        /*
		$sql = "SELECT category, count (*) AS counting, "
               ."  sum(case when (language = 'en') THEN 1 ELSE 0 END) - "
               ."  sum(case when (language = :language) THEN 1 ELSE 0 END) as missing "
               ." FROM table_message_source src, table_message_target trg where src.id = trg.id "
               ." GROUP BY category ORDER BY missing DESC, counting ASC; ";
        */
        $command = Yii::$app->db->createCommand($sql);
        // $command->bindValue(':language', $language, PDO::PARAM_STR);        
        $categories = $command->queryAll();
		$result = [];
        foreach ($categories as $category) {
           $result[$category['category']] = Language::beautifyCategory($category['category']);
        }
        return $result;
    }
    
    public static function beautifyCategory($category) {
        $category = str_replace('m:', yii::t('p:translate', 'Model').': ', $category);
        $category = str_replace('p:', yii::t('p:translate', 'Page').': ', $category);
        $category = str_replace('t:', yii::t('p:translate', 'Table').': ', $category);
        $category = str_replace('c:', yii::t('p:translate', 'Column').': ', $category); 
        $category = str_replace('sys:', yii::t('p:translate', 'Sysadmin').': ', $category);  
        $category = str_replace('sug:', yii::t('p:translate', 'Suggest').': ', $category);  
        $category = str_replace('/', '/ ', $category);          
        return $category;
    }

    public static function getAllMessagesOfCategory($category) {
        // Return only Languages, that are visible to UI
        $sql = "SELECT MIN(id) as id, message, count (*) AS counting FROM table_message_source WHERE category = :category GROUP BY message ORDER BY counting DESC";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':category', $category, PDO::PARAM_STR);        
        $messages = $command->queryAll();
        $result = array();
        foreach ($messages as $message) {
           $result[$message['id']] = $message['message'];  
        }
        return $result;
    }
   public static function getMessageIdByCategoryAndMessage($category, $message) {
        $sql = "SELECT MIN(id) as id "
                . " FROM table_message_source "
                . " WHERE category = :category AND message = :message "
                . " GROUP BY message, category ORDER BY message;";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':category', $category, PDO::PARAM_STR);    
        $command->bindValue(':message', $message, PDO::PARAM_STR);    
        return $command->queryScalar();
    }
    
    public static function getAllMessagesOfCategoryAndLanguage($category, $language, $language2) {
        //
        $sql = "SELECT DISTINCT ON(src.id) src.id, translation, language, (language = :language ) AS lang, (language = 'en') AS english".
               " FROM table_message_target trg, table_message_source src".
               " WHERE src.category = :category AND trg.id = src.id".
               " ORDER BY src.id, lang DESC, english DESC";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':category', $category, PDO::PARAM_STR);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $messages = $command->queryAll();
        $result = array();
        foreach ($messages as $message) {
            $result[$message['id']] = $message['translation'];
        }
        return $result;
    }    
    
    public static function getAllTranslationsOfCategoryAndLanguage($category, $language) {
        //
        $sql = "SELECT DISTINCT ON(src.id) src.id, category, message, translation, language".
                " FROM table_message_target trg, table_message_source src".
                " WHERE src.category = :category AND trg.id = src.id".
                " AND language = :language".
                " ORDER BY src.id";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':category', $category, PDO::PARAM_STR);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $result = $command->queryAll();
        return $result;
    } 	
	
    public static function getTranslationByCategory($category, $message, $language)
    {
        // 
        $sql = "SELECT translation FROM table_message_source, table_message_target 
                  WHERE table_message_source.category = :category  
                  AND table_message_source.message = :message  
                  AND table_message_source.id = table_message_target.id 
                  AND table_message_target.language = :language;";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':category', $category, PDO::PARAM_STR);
        $command->bindValue(':message', $message, PDO::PARAM_STR);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $result = $command->queryAll();

        return $result;
    }

    public static function getTranslationById($id, $language) {
        // 
        $sql = "SELECT DISTINCT ON(src.id) src.id, translation, language, voting, (language = :language ) AS lang, (language = 'en') AS english " .
                " FROM table_message_target trg, table_message_source src" .
                " WHERE src.id = :id AND trg.id = src.id" .
                " ORDER BY src.id, lang DESC, english DESC";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':id', $id, PDO::PARAM_INT);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $result = $command->queryAll();
        return $result;
    }

    public static function getSuggestionsById($id, $language) {
        // 
        $sql = "SELECT translation, count(*) AS counting, MAX(modified) as modified, MAX(created_by) created_by" .
                " FROM table_message_suggestion " .
                " WHERE id = :id AND language = :language" .
                " GROUP BY translation " .                
                " ORDER BY counting DESC, modified DESC";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':id', $id, PDO::PARAM_INT);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $result = $command->queryAll();
        return $result;
    }
    
    // get voting and message of existing translation
    public static function getExistingTranslationsByIdAndLanguage($id, $language) {
        $sql = "SELECT voting, translation, modified, created_by FROM table_message_target WHERE id = :id AND language = :language";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':id', $id, PDO::PARAM_INT);    
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $existingTranslation = $command->queryAll();
        return $existingTranslation;
    }
    
    public static function getExistingSourceById($id) {
        $sql = "SELECT message, category FROM table_message_source WHERE id = :id ;";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':id', $id, PDO::PARAM_INT);    
        $existingSource = $command->queryAll();
        return $existingSource;
    }
    
    public static function addSuggestion($id,$language,$message,$userId = NULL) {
        if (is_null($userId)) {
            $sqlSys = "SELECT MIN(id) FROM \"user\" WHERE username = 'system' GROUP BY username"; 
             
            $command = Yii::$app->db->createCommand($sqlSys);
            $userId = $command->queryScalar();
        }
        $sqlMsg = "INSERT INTO table_message_suggestion
                  (id, language,translation,created_by,modified)
                  SELECT :id1, :language1, :message1, :user1, NOW()
                  WHERE NOT EXISTS
                    (SELECT 1 FROM table_message_suggestion WHERE id = :id2 AND language = :language2 AND translation = :message2 AND created_by = :user2);";
        
        $command = Yii::$app->db->createCommand($sqlMsg);
        $command->bindValue(':id1', $id, PDO::PARAM_INT);    
        $command->bindValue(':id2', $id, PDO::PARAM_INT);          
        $command->bindValue(':user1', $userId, PDO::PARAM_INT);  
        $command->bindValue(':user2', $userId, PDO::PARAM_INT);         
        $command->bindValue(':language1', $language, PDO::PARAM_STR);
        $command->bindValue(':language2', $language, PDO::PARAM_STR);        
        $command->bindValue(':message1', $message, PDO::PARAM_STR);
        $command->bindValue(':message2', $message, PDO::PARAM_STR);        
        $command->execute();
    }
    
    public static function getNumberOfSameSuggestions($id, $language, $message) {
         $sql = "SELECT COUNT(*) FROM table_message_suggestion WHERE id = :id AND language = :language AND translation = :message";
         
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':id', $id, PDO::PARAM_INT);    
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $command->bindValue(':message', $message, PDO::PARAM_STR);
        $numberSuggestions = $command->queryScalar();
        return $numberSuggestions;
    }
    
    private static function moveExistingTranslationToSuggestion($id, $language) {
        $sqlMsg = "INSERT INTO table_message_suggestion
                  (id, language,translation,created_by,modified)
                  SELECT trl.id, trl.language, trl.translation, trl.created_by, trl.modified  
                  FROM table_message_target trl 
                  WHERE trl.id = :id AND trl.language = :language
                  AND NOT EXISTS
                    (SELECT 1 FROM table_message_suggestion WHERE id = trl.id AND language = trl.language AND translation = trl.translation AND created_by = trl.created_by);";
        
        $command = Yii::$app->db->createCommand($sqlMsg);
        $command->bindValue(':id', $id, PDO::PARAM_INT);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $command->execute();
    }
    
    private static function deleteExistingTranslation($id, $language) {
            $sqlMsg = "DELETE FROM table_message_target
                   WHERE id = :id AND language = :language;";
            
            $command = Yii::$app->db->createCommand($sqlMsg);
            $command->bindValue(':id', $id, PDO::PARAM_INT);    
            $command->bindValue(':language', $language, PDO::PARAM_STR);            
            $command->execute();
    }
    
    private static function addTranslationToTarget($id, $language, $message, $userId, $numberSuggestions) {
            $sqlMsg = "INSERT INTO table_message_target
                  (id, language, translation, created_by, voting, modified) 
                  SELECT :id1, :language1, :message1, :user1, :voting ,NOW()
                  WHERE NOT EXISTS
                    (SELECT 1 FROM table_message_target 
                       WHERE id = :id2 AND language = :language2 AND translation = :message2 AND created_by = :user2);";
            
            $command = Yii::$app->db->createCommand($sqlMsg);
            $command->bindValue(':id1', $id, PDO::PARAM_INT);
            $command->bindValue(':id2', $id, PDO::PARAM_INT);
            $command->bindValue(':user1', $userId, PDO::PARAM_INT);
            $command->bindValue(':user2', $userId, PDO::PARAM_INT);
            $command->bindValue(':language1', $language, PDO::PARAM_STR);
            $command->bindValue(':language2', $language, PDO::PARAM_STR);
            $command->bindValue(':message1', $message, PDO::PARAM_STR);
            $command->bindValue(':message2', $message, PDO::PARAM_STR);
            $command->bindValue(':voting', $numberSuggestions, PDO::PARAM_INT);
            $command->execute();
    }

    private static function getTranslationsFromUser($userId) {
        $sql = "SELECT * FROM table_message_target WHERE created_by = :user";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':user', $userId, PDO::PARAM_INT);    
        $existingTranslation = $command->queryAll();
        return $existingTranslation;        
    } 
    
    private static function removeSuggestionsFromUser($userId) {
            $sqlMsg = "DELETE FROM table_message_suggestion
                         WHERE created_by = :user;";
            
            $command = Yii::$app->db->createCommand($sqlMsg);
            $command->bindValue(':user', $userId, PDO::PARAM_INT);
            $command->execute();        
    }

    private static function removeTranslationsFromUser($userId) {
            $sqlMsg = "DELETE FROM table_message_target
                         WHERE created_by = :user;";
            
            $command = Yii::$app->db->createCommand($sqlMsg);
            $command->bindValue(':user', $userId, PDO::PARAM_INT);
            $command->execute();        
    }    
    
    public static function deleteTranslationsFromUser($user) {
      $userId = $user->id;
      Language::deleteTranslationsFromUserById($userId);
     }
    
    public static function deleteTranslationsFromUserById($userId) {
        Language::removeSuggestionsFromUser($userId);
        $translations = Language::getTranslationsFromUser($userId);
        Language::removeTranslationsFromUser($userId);
        foreach($translations as $translation) {
          $id = $translation['id'];
          $language = $translation['language'];
          $oldMessage = $translation['translation'];
          $suggestions = Language::getSuggestionsById($id, $language);
            if (sizeof($suggestions) > 0) {
               $message = $suggestions[0]['translation'];
               $voting = $suggestions[0]['counting'];  
               $creator = $suggestions[0]['created_by']; 
               Language::addTranslationToTarget($id, $language, $message, $creator, $voting);
               Language::removeSuggestion($id, $language, $message);
            }
            Language::addSuggestion($id,$language,$oldMessage);
        }
        return TRUE;
    }
    
    private static function removeSuggestion($id, $language, $message) {
            $sqlMsg = "DELETE FROM table_message_suggestion
                         WHERE id = :id AND language = :language AND translation = :message;";
            
            $command = Yii::$app->db->createCommand($sqlMsg);
            $command->bindValue(':id', $id, PDO::PARAM_INT);
            $command->bindValue(':language', $language, PDO::PARAM_STR);
            $command->bindValue(':message', $message, PDO::PARAM_STR);
            $command->execute();
    }
    
    public static function getPercantageOfTranslationByLanguage($language) {
        $sql = "SELECT (100.0 * (SELECT COUNT(*) from table_message_target WHERE language = :language) "
                          . " / (SELECT COUNT(*) from table_message_source)) AS percentage";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $percentageTranslated = $command->queryScalar(); 
        return $percentageTranslated;
    }
    
    private static function activateVisibilityOfLanguage($language) {
            $upd = "UPDATE language SET visible = TRUE WHERE language = :language ;";
            
            $command = Yii::$app->db->createCommand($upd);
            $command->bindValue(':language', $language, PDO::PARAM_STR);
            $command->execute();  
    }

    public static function addTranslationById($id, $language, $message) {
        
        $result = yii::t('p:translate', 'Nothing added');
        // get voting and message of existing translation
        $existingTranslation = Language::getExistingTranslationsByIdAndLanguage($id, $language);
        
        $votingTranslation = 0;
        if (sizeof($existingTranslation) > 0) {
            $votingTranslation = $existingTranslation[0]['voting'];
            // check if suggestion != current translation   
            if ($message == $existingTranslation[0]['translation']) {
                $result = yii::t('p:translate', 'Translation already exists');
                
                return $result;
            } 
        }
        $existingSource = Language::getExistingSourceById($id);
        $source = '';
        $category = '';
        if (sizeof($existingSource) > 0) {
            $source   =  $existingSource[0]['message'];  
             // check if category starts with 'sys:*' 
            $category =  strtolower($existingSource[0]['category']); 
            if ((substr( $category, 0, 4 ) == 'sys:') and !(User::hasRole('sysadmin'))) { 
                $result = yii::t('p:translate', 'Only allowed to be translated by sysadmin.'); 
                 
                 return $result;
            }
       }
        // check for {bracket}-strings!
        preg_match_all("/\{(.*?)\}/", $message, $messageBrackets);
        preg_match_all("/\{(.*?)\}/", $source, $sourceBrackets);
        $additional = array_diff($messageBrackets[1], $sourceBrackets[1]);
        $missing = array_diff($sourceBrackets[1], $messageBrackets[1]);
        if (count($missing) > 0) {
                $result = yii::t('p:translate', 'Some values in brackets {missing} are missing.'
                                              , array('{missing}'  => '{'.implode(',',$missing).'}')); 
                 
                 return $result;            
        }   
        if (count($additional) > 0) {
                $result = yii::t('p:translate', 'Some values in brackets {additional} are superfluous.' 
                                              , array('{additional}'  => '{'.implode(',',$additional).'}')); 
                  
                  return $result;            
        }
        // suggestion: same id,language, tranlation, creator -> already entered
        $userId = Yii::$app->user->id;
        // $Profile = Profile::find()->where(["user_id" => $userId])->one() ;
        // add suggestion to database
        Language::addSuggestion($id,$language,$message,$userId);
        $result = yii::t('p:translate', 'Suggestion added');
        
        // count suggestions that are the same...
        $numberSuggestions = Language::getNumberOfSameSuggestions($id, $language, $message);
        // sysadmin have higher priority
        if (User::hasRole('sysadmin')) {
            $numberSuggestions += 2;
        }
        if (User::hasRole('sysadmin') and 
           ((substr($category, 0, 4) == 'sys:') or 
            (substr($category, 0, 4) == 'sug:')) ) {
            $numberSuggestions = $votingTranslation + 1;
        }
        //if ($Profile->translate == $language) {
        //    $numberSuggestions += 1;
        //}
        // add more privileges: log(number of translations, logins, ... of user.)
        // $participation = Language::getParticipation($userId, $language);
		$participation = 2;
		
        //if (sizeof($participation) > 0) {
        //    $numberSuggestions += (int)floor(log10(1.0 + $participation[0]['translations'] + $participation[0]['logins']));
        //}
		
        // for some categories only suggestions are added
        if ((substr( $category, 0, 4 ) == 'sug:') and !(User::hasRole('sysadmin'))) { 
            $numberSuggestions = 0;
        }
        
        if (($numberSuggestions > $votingTranslation) or (User::hasRole('sysadmin'))) {
            $result = yii::t('p:translate', 'Translation added');
            
            // add new suggestion as official translation
            if (sizeof($existingTranslation) > 0) {
                $result = yii::t('p:translate', 'Translation modified');
                // move existing translation to suggestion
                Language::moveExistingTranslationToSuggestion($id, $language);
            }
            // delete existing translation
            Language::deleteExistingTranslation($id, $language);
            // add latest suggestion to translation
            Language::addTranslationToTarget($id, $language, $message, $userId, $numberSuggestions);
            // remove suggestions
            Language::removeSuggestion($id, $language, $message);
        }
        // make language visible if translations > 0.5% 
        $percentageTranslated = Language::getPercantageOfTranslationByLanguage($language);
        if ($percentageTranslated > 0.5) {   
            Language::activateVisibilityOfLanguage($language);      
        }
        return $result;
    }

    public static function limitCallsPerTimeFrame($key, $limit = 1000, $days = 1.0) {
        $allow = false;
        $cacheKey = 'expo:'.$key;
        $expCounter = Yii::$app->cache->get($cacheKey);
        if (!$expCounter) {
            $expCounter = array('time'=>time(), 'value'=>0.0);
        } 
        $value = $expCounter['value']*exp( ($expCounter['time'] - time()) / ($days * 86400.0));
        if (mt_rand(0, $limit) > $value * exp(1.0)) {
           $allow = true;
           $expCounter['value'] = $value + 1.0;
           $expCounter['time'] = time();
           Yii::$app->cache->set($cacheKey, $expCounter, (int)($days * 86400 * 2)); 
        }
        return $allow;
    }
    
    // http://mymemory.translated.net/doc/usagelimits.php
    public static function internetTranslation($message, $language, $minQuality = 0.75, $sourceLanguage = 'en') {
        $translation = NULL;
        $cacheKey = 'api.mymemory.translated.net:['.$sourceLanguage.'->'.$language.']:'.$message;
        $translationString = Yii::$app->cache->get($cacheKey);
        if (!$translationString) {
            // limited to 10000 words a day!
            if (Language::limitCallsPerTimeFrame("test2", 10000)) {
                $eMail = TAMBORA_ADMIN_EMAIL; // 'devel@tambora.org'
                $myMemoryKey = '67511ecdf7c5f1af4a42'; // http://mymemory.translated.net/doc/keygen.php
                $url = 'http://api.mymemory.translated.net/get?q=' . urlencode($message) . '&langpair='.$sourceLanguage.'|' . $language . '&de=' . $eMail . '&key=' . $myMemoryKey;
                //Yii::log("tr-internet-url: { $url }", CLogger::LEVEL_INFO, 'tambora.' . __METHOD__);
                $translationString=CurlHandler::getUrlAsText($url);
                Yii::$app->cache->set($cacheKey, $translationString, mt_rand(604800, 1209600)); //7-14days
            }
        }
        //Yii::log("tr-internet-json: { $translationString }", CLogger::LEVEL_INFO, 'tambora.' . __METHOD__);
        $translationData = json_decode($translationString, true);
        if (is_array($translationData) and (sizeof($translationData) > 0) and array_key_exists('responseData', $translationData)) {
            $maxQuality = 0.0;
            if (array_key_exists('matches', $translationData)) {
                $matches = $translationData['matches'];
                if (sizeof($matches) > 0) {
                    foreach ($matches as $item) {
                        $matchValue = 0.0;
                        $qualityValue = 0.0;    
                        if (array_key_exists('match', $item)) {
                            if (is_double($item['match'])) {
                                $matchValue = $item['match'];
                            }
                        }
                        if (array_key_exists('quality', $item)) {
                            if (is_int((int)$item['quality'])) {
                                $qualityValue = (int)$item['quality'];
                            }
                        }
                        $quality = $matchValue * $qualityValue / 100.0;
                        if ($quality > $maxQuality) {
                            $maxQuality = $quality;
                        }
                    }
                }
            }
            //Yii::log("tr-internet-quality: { $language : $maxQuality }", CLogger::LEVEL_INFO, 'tambora.' . __METHOD__);
            if ($maxQuality > $minQuality) {
                $response = $translationData['responseData'];
                if ((sizeof($response) > 0) and array_key_exists('translatedText', $response)) {
                    $translation = $response['translatedText'];
                    //Yii::log("tr-internet-worked", CLogger::LEVEL_INFO, 'tambora.' . __METHOD__);
                }
            }
        }
        return $translation;
    }

    // function is called, when YII finds a translation not yet covered in DB
    // first english translation is added automatically
    public static function missingTranslation($event) {
		//var_dump($event);
        $language = $event->language;
        $category = $event->category;
        $message = $event->message;
        if ('en' == $event->language) {
            Language::addTranslation($message, 'en', $message, $category);
        } else {
            $findMessage = Language::findAnyTranslation($language, $message);

            if (is_string($findMessage)) {
                Language::addTranslation($message, $language, $findMessage, $category);
                $event->translatedMessage = $findMessage;
            } else {
                $currentLang = Yii::$app->language;
                 Yii::$app->language = 'en';
                 $englishMessage = yii::t($category, $message, array());
                Yii::$app->language = $currentLang;
                $event->translatedMessage = $englishMessage;
                // on real server try to fetch translations from internet
                $serverName = "www.tambora.org"; 
                //$serverName = "tambora-test.ub.uni-freiburg.de";
                if (strpos(Url::base(true), $serverName) !== false) {
                    // Yii::log("tr-internet-server: { $serverName }", CLogger::LEVEL_INFO, 'tambora.' . __METHOD__);
                    // only no :sys and :suggest
                    if ((substr($category, 0, 4) != 'sys:') and ( substr($category, 0, 4) != 'sug:')) {
                        // only messages without parameter should be translated automatically
                        if ((FALSE === strpos($englishMessage, '{')) and ( FALSE === strpos($englishMessage, '}'))) {
                            // only few messages should be translated automatically
                            if (mt_rand(0, 1000) > 980) {
                                // First prefer medium sized strings, later also shorter and longer ones
                                $percentageTranslated = Language::getPercantageOfTranslationByLanguage($language);
                                $minLen = 7.7 + 0.02 * $percentageTranslated - 0.00096 * $percentageTranslated * $percentageTranslated;
                                $maxLen = 20.0 + 0.0006 * $percentageTranslated * $percentageTranslated * $percentageTranslated;
                                // First only accept excellent translations, later be more tolerant
                                $quality = 0.75 - 0.0015 * $percentageTranslated;
                                // only short messages should be translated automatically
                                if ((strlen($englishMessage) < $maxLen) and ( strlen($englishMessage) > $minLen)) {
                                    $newMessage = Language::internetTranslation($englishMessage, $language, $quality);
                                    if (is_string($newMessage) and strlen($newMessage) < 4 * strlen($englishMessage)) {
                                        //Yii::log("tr-internet-new: { $newMessage }", CLogger::LEVEL_INFO, 'tambora.' . __METHOD__);
                                        Language::addTranslation($message, $language, $newMessage, $category);
                                        $event->translatedMessage = $newMessage;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function missingTranslation2($event) {
        $language = $event->language;
        $category = $event->category;
        $message = $event->message;  
        $event->translatedMessage = '*-'.$message.'-*';
    }
    
    public static function addTranslation($message, $language, $translation, $category) {
        // add Source
        $sqlMsg = "INSERT INTO table_message_source
                   (category, message)
                   SELECT :category1, :message1
                   WHERE NOT EXISTS
                    (SELECT 1 FROM table_message_source WHERE message = :message2 AND category = :category2);";
        
        $command = Yii::$app->db->createCommand($sqlMsg);
        $command->bindValue(':category1', $category, PDO::PARAM_STR);
        $command->bindValue(':category2', $category, PDO::PARAM_STR);        
        $command->bindValue(':message1', $message, PDO::PARAM_STR);
        $command->bindValue(':message2', $message, PDO::PARAM_STR);       
        $command->execute();
   
        // add target translation for language
        $sqlTrl = "INSERT INTO table_message_target
                  (id, language, translation, created_by, voting, modified)
                  SELECT table_message_source.id, :language1, :translation1, \"user\".id , 1, NOW()
                      FROM table_message_source, \"user\" 
                      WHERE \"user\".username = 'system' AND
                      message = :message1 AND category = :category1 AND
                      NOT EXISTS
                         (SELECT 1 FROM table_message_target WHERE language = :language2 AND table_message_target.id = table_message_source.id)
                ;";
        
        $command = Yii::$app->db->createCommand($sqlTrl);
        $command->bindValue(':category1', $category, PDO::PARAM_STR);
        $command->bindValue(':message1', $message, PDO::PARAM_STR);
        $command->bindValue(':translation1', $translation, PDO::PARAM_STR);        
        $command->bindValue(':language1', $language, PDO::PARAM_STR); 
        $command->bindValue(':language2', $language, PDO::PARAM_STR);         
        $command->execute();
   
        // add target translation for english
        $sqlEng = "INSERT INTO table_message_target
                  (id, language, translation, created_by, voting, modified)
                  SELECT table_message_source.id, 'en', :translation1, \"user\".id , 1, NOW()
                      FROM table_message_source, \"user\" 
                      WHERE \"user\".username = 'system' AND
                      message = :message1 AND category = :category1 AND
                      NOT EXISTS
                         (SELECT 1 FROM table_message_target WHERE language = 'en' AND table_message_target.id = table_message_source.id)
                ;";
        
        $command = Yii::$app->db->createCommand($sqlEng);
        $command->bindValue(':category1', $category, PDO::PARAM_STR);
        $command->bindValue(':message1', $message, PDO::PARAM_STR);
        $command->bindValue(':translation1', $message, PDO::PARAM_STR);        
        $command->execute(); 
        // maybe also add language?
    }
    
    
    public static function findAnyTranslation($language, $message) {
        $sql = "SELECT translation, SUM(voting) AS voting
                 FROM table_message_source src, table_message_target trg 
                 WHERE src.id = trg.id AND trg.language = :language AND src.message = :message
                 GROUP BY translation ORDER BY voting DESC;";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':message', $message, PDO::PARAM_STR);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $result = $command->queryAll();
        $translation = NULL;
        if (sizeof($result) > 0) {
            $translation = $result[0]['translation'];
        }
        return $translation;
    }

    public static function voteLanguage($language, $name, $vote = 1.0) {
        if (!is_float((float)$vote)) { $vote = 0.0; }
        $sqlAdd = "INSERT INTO language 
                  (language, name, visible, requests, modified)
                  SELECT :language1, :name1, FALSE, 0.0, NOW()
                  WHERE NOT EXISTS 
                    (SELECT 1 FROM language WHERE language = :language2);";
          
        $command = Yii::$app->db->createCommand($sqlAdd);
            $command->bindValue(':language1', $language, PDO::PARAM_STR);
            $command->bindValue(':language2', $language, PDO::PARAM_STR);  
            if  (is_string($name)) {
            $command->bindValue(':name1', $name, PDO::PARAM_STR);  
            } else {
            $command->bindValue(':name1', $language, PDO::PARAM_STR);      
            }
        $command->execute();
        
        // 15552000 = 60*60*24*30*6 seconds = 1/2 year
        $upd = "UPDATE language
                  SET requests = :vote + requests * exp(EXTRACT( epoch FROM(modified - NOW()))/15552000.0),  
                      modified = NOW()
                WHERE language = :language
                ;";

        $command = Yii::$app->db->createCommand($upd);
        $command->bindValue(':vote', (float)$vote);
        $command->bindValue(':language', $language, PDO::PARAM_STR);
        $command->execute();

        if (is_string($name)) {
            $upd = "UPDATE language SET name = :name WHERE language = :language ;";
            $command = Yii::$app->db->createCommand($upd);
            $command->bindValue(':name', $name, PDO::PARAM_STR);
            $command->bindValue(':language', $language, PDO::PARAM_STR);
            $command->execute();
        }
    }
    
    public static function getLanguageStatistic($onlyNew = false) {
        $selectNew = '';
        if ($onlyNew) {
            $selectNew = ' WHERE NOT visible ';
        }
        $sql = 'SELECT language.language AS language, 
                       name, 
                      (100.0 * requests / (SELECT SUM(requests) FROM language)) AS percent,
                      requests,
                      (100.0 * translated / (SELECT COUNT(*) from table_message_source)) AS translated
                FROM language LEFT JOIN
                     (SELECT language, COUNT(DISTINCT id) AS translated
                      FROM (SELECT id, language FROM table_message_target 
                            UNION
                            SELECT id, language FROM table_message_suggestion) AS together
                      GROUP BY language) AS together2
                ON language.language = together2.language'
                . $selectNew .
                ' ORDER BY percent DESC LIMIT 20';
        $command = Yii::$app->db->createCommand($sql);
        $languages = $command->queryAll();
        return $languages;
    }
    
    public static function getLatestTranslations($limit = 20, $suggestions = true, $translations = true) {
        $sqlSugg = '(SELECT result.id, result.message, result.modified, result.voting, '
                . ' result.language, result.translation, result.category, \'Suggestion\' as type, ' 
                . ' table_message_suggestion.created_by as created_by, username'
                . ' FROM (SELECT table_message_source.id as id, message, MAX(modified) as modified, COUNT(*) as voting, '
                . '  table_message_suggestion.language as language, translation, category'
                . '  FROM table_message_suggestion '
                . '  LEFT JOIN table_message_source ON table_message_suggestion.id = table_message_source.id '
                . '  GROUP BY table_message_source.id, language, translation ) AS result '
                . ' LEFT JOIN table_message_suggestion ON table_message_suggestion.id = result.id '
                . '  AND table_message_suggestion.language = result.language '
                . '  AND table_message_suggestion.modified = result.modified '
                . '  AND table_message_suggestion.translation = result.translation '
                . ' LEFT JOIN "user" ON "user".id = table_message_suggestion.created_by)';
        $sqlTrans = '(SELECT table_message_source.id as id, message, modified, voting,'
                . ' table_message_target.language as language, translation, category, \'Translation\' as type, '
                . ' table_message_target.created_by as created_by, username'
                . ' FROM table_message_target'
                . ' LEFT JOIN table_message_source ON table_message_target.id = table_message_source.id'
                . ' LEFT JOIN "user" ON "user".id = table_message_target.created_by)';
        if ($suggestions and $translations) {
        $sql = $sqlSugg . ' UNION ' . $sqlTrans . ' ORDER BY modified DESC LIMIT :limit';
        } elseif ($suggestions) {
             $sql = $sqlSugg . ' ORDER BY modified DESC LIMIT :limit';
        } elseif ($translations) {
             $sql = $sqlTrans . ' ORDER BY modified DESC LIMIT :limit';
        } else {
             $sql = 'SELECT * FROM generate_series(0,0,1) as x WHERE x > 0 LIMIT :limit';
        }
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $translations = $command->queryAll();
        return $translations;
    }    

    
    public static function getParticipation($userId, $language, $years = 1.0) {
        if (!is_float((float)$years)) { $years = 1.0; }
        $sql = "SELECT logins, translations, translations2 FROM"
              ." (SELECT SUM(CASE WHEN (language = :language1) AND (created_by = :user1) THEN voting ELSE 0 END) AS translations "
              ."  FROM table_message_target "
              ."  WHERE (EXTRACT( epoch FROM(now() - modified )) / (60*60*24*365) < :years1) "
              ." ) AS translate, "
              ." (SELECT SUM(CASE WHEN (language = :language1) AND (created_by = :user1) THEN 1 ELSE 0 END) AS translations2 "
              ."  FROM table_message_target "
              ."  WHERE (EXTRACT( epoch FROM(now() - modified )) / (60*60*24*365) < :years1) "
              ." ) AS translate2, "   
              ." (SELECT SUM(CASE WHEN (user_id = :user2) THEN 1 ELSE 0 END) AS logins "
              ."  FROM zzz_login_audit_old "
              ."  WHERE (EXTRACT( epoch FROM(now() - last_login_time )) / (60*60*24*365) < :years2) "
              ." ) AS login";
        
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':user1', (int)$userId, PDO::PARAM_INT);  
        $command->bindValue(':language1', $language, PDO::PARAM_STR);          
        $command->bindValue(':years1', (float)$years);
        $command->bindValue(':user2', (int)$userId, PDO::PARAM_INT);        
        $command->bindValue(':years2', (float)$years);        
        $result = $command->queryAll();
        return $result;
    }
    
}
