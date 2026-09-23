<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\libraries\bundles;

use yii\web\AssetBundle;

//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css
//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js
//fonts.googleapis.com/css?family=Open+Sans
//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css

/**
 * Class BootstrapAsset
 * @package app\modules\libraries\bundles
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/bootstrap';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of css-files can also be defined in config/assets.php!
    public $css = [
        'css/bootstrap.min.css',
    ];
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/bootstrap.min.js',
    ];
    public $depends = [];
}
