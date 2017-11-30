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
class OpenlayersAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/ol3';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
        'css/ol3.min.css',
        'css/ol3-layerswitcher.css',
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/ol3.min.js',
        'js/ol3-layerswitcher.js',
    ];
    public $depends = [];
}
