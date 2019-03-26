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
    public $sourcePath = '@app/modules/libraries/assets/eonet';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
        'css/spaceapp.css',
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/globe.js',
        'js/maps.js',
        'js/charts.js',
        'js/tambora.js',
        'js/eonet.js',
        'js/spaceapp.js',
    ];
    public $depends = [
        'app\modules\libraries\bundles\OpenlayersAsset',
        'yii\web\JqueryAsset', //'app\modules\libraries\bundles\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\modules\libraries\bundles\LockrAsset',        
        'app\modules\libraries\bundles\D3Asset',
        'app\modules\libraries\bundles\DynaTableAsset',        
        'app\modules\libraries\bundles\ThreeAsset',     
        'app\modules\libraries\bundles\TweenAsset',  
    ];
   
}
