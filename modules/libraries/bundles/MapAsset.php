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
 * Class StatisticsAsset
 * @package app\modules\libraries\bundles
 * @author Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class MapAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/map';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
        'css/map.css',
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/map.js',
    ];
    public $depends = [
        //'app\modules\libraries\bundles\OpenlayersAsset',
		'app\modules\translation\TranslationAsset',
	    'app\modules\libraries\bundles\LeafletAsset',
        'yii\web\JqueryAsset', //'app\modules\libraries\bundles\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\modules\libraries\bundles\TweenAsset', 
        'app\modules\libraries\bundles\VueAsset',
        //'app\modules\libraries\bundles\D3Asset',		
    ];
   
}
