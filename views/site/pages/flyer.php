<?php
use app\widgets\TmbCards;
use app\utils\FlyerItems;

$this->title = Yii::t('p:flyer', 'Flyer');
$items = FlyerItems::getItems();
?>
<h2><?php tr('p:flyer', 'Zusätzliche Informationen'); ?></h2>
<?php
echo TmbCards::widget(['items' => $items]);
?>
