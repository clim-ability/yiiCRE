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
 * Class IntroAsset
 * @package app\modules\libraries\bundles
 * @author Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class TweenAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/tween';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/tweenjs.min.js',
        'js/tween.js',
    ];
    public $depends = [];
}
