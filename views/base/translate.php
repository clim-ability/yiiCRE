<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $messages['title']; 
?>
<div class="item-index">

<?php
foreach($dataProvider->getModels() as $data) {
	var_dump($data); 
}
foreach($columns as $column) {
	var_dump($column); 
}

var_dump($columns);
?>

</div>

