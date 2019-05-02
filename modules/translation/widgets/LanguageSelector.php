<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

/**
 * Class LanguageSelector
 * LanguageSelector creates a language selector field
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>>
 * @since 2.0
 * @see http://www.yiiframework.com/wiki/293/
 * @see manage-target-language-in-multilingual-applications-a-language-selector-widget-i18n/
 * @see http://www.yiiframework.com/wiki/208/
 * @see how-to-use-an-application-behavior-to-maintain-runtime-configuration/
 */

namespace app\modules\translation\widgets;

use Yii;
use yii\base\Widget;
use app\modules\translation\models\Language;


class LanguageSelector extends Widget
{
    public function run()
    {
        $currentLang = Yii::$app->language;
        $languages = Language::getVisibleLanguages();
        
        return $this->render('languageSelector'
            , array('currentLang' => $currentLang, 'languages' => $languages));
    }
}

?>