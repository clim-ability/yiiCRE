<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\translation\widgets;

use Yii;
use yii\base\Widget;

/**
 * Class LanguageTranslate
 *  * LanguageSelector creates a language selector field
 * @package app\modules\translation\widgets
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 * @see http://www.yiiframework.com/wiki/293/
 * @see manage-target-language-in-multilingual-applications-a-language-selector-widget-i18n/
 * @see http://www.yiiframework.com/wiki/208/
 * @see how-to-use-an-application-behavior-to-maintain-runtime-configuration/
 */
class LanguageTranslate extends Widget
{

    public $category;
    public $message;
    public $params;
    public $language;

    public function run()
    {
        return $this->render('languageTranslate',
            [
                'category' => $this->category,
                'message' => $this->message,
                'params' => $this->params,
                'language' => $this->language,
            ]
        );
    }

}

?>