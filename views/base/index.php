<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $messages['title']; 
?>
<div class="item-index">

    <p>
        <?= Html::a($messages['create'], ['create'], ['class' => 'btn btn-success']) ?>
    </p>

  <?php if(Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger" role="alert">
        <?= Yii::$app->session->getFlash('danger'); ?>
    </div>
  <?php endif; ?>
  <?php if(Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success" role="alert">
         <?= Yii::$app->session->getFlash('success'); ?>
    </div>
  <?php endif; ?>

    <?php     
     $allColumns = array_merge([['class' => 'yii\grid\SerialColumn']], $columns, [['class' => 'yii\grid\ActionColumn']])
    ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $allColumns,
    ]); ?>
</div>

