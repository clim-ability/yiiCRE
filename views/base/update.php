<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('p:base', 'Update {modelClass}: ', [
    'modelClass' => $messages['item'],
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $messages['title'], 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('p:base', 'Update');

?>
<style>
 body {
   background-image: unset !important; 
 }
</style>

<div class="item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'columns' => $columns,
        'messages' => $messages,
    ]) ?>

</div>
