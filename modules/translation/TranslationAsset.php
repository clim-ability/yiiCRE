<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\translation;

use yii\web\AssetBundle;

/**
 * Class GroupingAsset
 * @package app\modules\grouping
 * @author Wael Sidawi <wael.sidawi@ub.uni-freiburg.de>
 * @since 2.0
 */
class TranslationAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/translation/assets';

    public $css = ['css/translation.css', ];

    public $js = ['js/translation.js',];

    public $depends = [  
            
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
    ];

}
