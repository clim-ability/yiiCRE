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
 * Class ContextMenuAsset
 * @package app\modules\libraries\bundles
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class ContextMenuAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/libraries/assets/jquery-ui';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    // List of js-files can also be defined in config/assets.php!
    public $js = [
        'js/contextmenu/jquery.ui-contextmenu.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset', //'app\modules\libraries\bundles\JqueryAsset',
        'app\modules\libraries\bundles\JqueryUiAsset'
    ];
}
