<?php
use app\widgets\TmbCards;
use app\utils\FlyerItems;
use app\modules\translation\widgets\LanguageTranslate;

function tr($c, $m, $p = []) {
    echo LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p]);
}

$this->title = Yii::t('p:flyer', 'Flyer');
$items = FlyerItems::getItems();
?>

<style>
 body {
   background-image: url('/images/wolke5.jpg') !important; 
 }
</style>

<h2><?php tr('p:flyer', 'Zusätzliche Informationen'); ?></h2>

<div class='gxxlass70'>
<?php
echo TmbCards::widget(['items' => $items]);
?>
</div>
