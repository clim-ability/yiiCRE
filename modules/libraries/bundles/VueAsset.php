<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\libraries\bundles;

use yii\web\AssetBundle;

/**
 * Class JqueryAsset
 * @package app\modules\libraries\bundles
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class VueAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/vue';   
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
         'js/vue.min.js',
		 'js/axios.min.js',
    ];
    public $depends = [];
}
