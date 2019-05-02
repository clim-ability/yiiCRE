<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
 function addSearchFields($model, $form, $columns) {
    foreach ($columns as $attribute=>$column) {
        if ($column->phpType === 'boolean') {
            echo $form->field($model, $attribute)->checkbox();
        } else {
            echo $form->field($model, $attribute);
        }
    }
}
?>

<div class="item-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?php addSearchFields($model, $form, $columns); ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('p:base', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('p:base', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
