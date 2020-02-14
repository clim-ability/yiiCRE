<?php
use app\widgets\TmbCards;
use app\utils\FlyerItems;

$this->title = Yii::t('p:flyer', 'Flyer');
$items = FlyerItems::getItems();
echo TmbCards::widget(['items' => $items]);

?>
