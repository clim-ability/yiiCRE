<?php
/**
 * @copyright 2017 University Library of Freiburg
 * @copyright 2017 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\libraries\bundles;

use yii\web\AssetBundle;

/**
 * Class LeafletAsset
 * @package app\modules\libraries\bundles
 * @author Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class HighlightAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/highlight';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
        //'css/default.min.css',
        'css/zenburn.min.css',
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/highlight.min.js',
        'js/http.min.js',
        'js/json.min.js',
     // 'js/xml.min.js',
     // 'js/cpp.min.js',
        'js/r.min.js',
        
    ];
    public $depends = [];
}
