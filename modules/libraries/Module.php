<?php
/**
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @copyright 2015 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\modules\libraries;

/**
 * Class LibrariesModule
 * MapModule creates an area for timeline handling
 * @package app\modules\libraries
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @since 2.0
 */
class Module extends \yii\base\Module
{

    private $_assetsUrl;

    public function init()
    {
        parent::init();
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        /*$this->setImport(array(
            'explore.models.*',
            'explore.components.*',
        ));*/
    }
}
