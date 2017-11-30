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
 * Class D3Asset
 * @package app\modules\libraries\bundles
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class D3Asset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/d3';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
        'css/c3.css',
        'css/dc.min.css',
        'css/colorbrewer.css',
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/d3.min.js',
        'js/d3-legend.min.js',
        'js/c3.min.js',
        'js/colorbrewer.js',
        'js/crossfilter.min.js',
        'js/dc.min.js',
        'js/cloud.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset', //'app\modules\libraries\bundles\JqueryAsset',
    ];
}
