<?php
/**
 * @copyright 2016 University Library of Freiburg
 * @copyright 2016 Leibniz Institute for Regional Geography
 * @copyright 2016 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\libraries\bundles;

use yii\web\AssetBundle;

/**
 * Class LockrAsset
 * @package app\modules\libraries\bundles
 * @author Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class LockrAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/lockr';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/lockr.js',
    ];
    public $depends = [];
}
