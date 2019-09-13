<?php
/**
 * @copyright 2016 University Library of Freiburg
 * @copyright 2016 Leibniz Institute for Regional Geography
 * @copyright 2016 Geographie University of Freiburg
 * @licence http://creativecommons.org/licenses/by/4.0/
 */

namespace app\widgets;

use yii\base\Widget;

class TmbMenu extends Widget {

    public $menu;


    public function run() {
        return $this->render('tmbMenu', 
        [
            'menu' => $this->menu
        ]
        );
    }

}

?>