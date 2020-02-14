<?php
/**
 * Widget to render cards-like link-list
 *
 * @author    Michael Kahle <michael.kahle@ub.uni-freiburg.de>
 * @copyright 2015 University Library of Freiburg
 * @copyright 2015 Leibniz Institute for Regional Geography
 * @license   GNU General Public License - http://www.gnu.org/copyleft/gpl.html 
 */

/*
  Usage:

  $items = [
   ['title' => 'Google', 'url' => 'http://google.com',
    'description' => Yii::t('p:cards', 'Such doch selber'), 'glyphicon' => 'search'],
   ['title' => 'Tambora', 'url' => 'http://www.tambora.org',
    'description' => 'Dieser Vulkan ist heiss!',
    'img' => 'https://tambora-test.ub.uni-freiburg.de/tambora-dev/images/logos/tambora-logo.png' ],
	'target' => '_flyer' 
   ];
  echo TmbCards::widget(['items' => $items]);
  // best image-size 250*175px 
 */
namespace app\widgets;

use yii\base\Widget;

class TmbCards extends Widget
{

    public $items;

    public function run()
    {
        return $this->render('tmbCards', [
                'items' => $this->items
                ]
        );
    }
}

?>