<?php

use yii\helpers\Html;


/* @var $this yii\web\View */

$this->title = $messages['create'];
$this->params['breadcrumbs'][] = ['label' => $messages['title'], 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
 body {
   background-image: unset !important; 
 }
</style>

<div class="item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'columns' => $columns,
        'messages' => $messages,
    ])
    ?>

</div>
