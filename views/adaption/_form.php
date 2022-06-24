<?php

use yii\helpers\Html;
use yii\helpers\BaseArrayHelper;
use yii\helpers\BaseHtml;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Day */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
 function addInputFields($model, $form, $columns) {

    foreach ($columns as $attribute=>$column) {
        if ($column->phpType === 'boolean') {
            echo $form->field($model, $attribute)->checkbox();
        } elseif ($column->type === 'text') {
            echo $form->field($model, $attribute)->textarea(['rows' => 6]);
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if ($column->phpType !== 'string' || $column->size === null) {

                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    echo $form->field($model, $attribute)->passwordInput();
                } else {
                    echo $form->field($model, $attribute)->textInput();
                }
            } else {

                if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                    echo $form->field($model, $attribute)->passwordInput(['maxlength' => $column->size]);
                } else {
                    echo $form->field($model, $attribute)->textInput(['maxlength' => $column->size]);
                }
            }
        }
    }
}
?>


<div class="item-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php addInputFields($model, $form, $columns); ?>
    <?php echo "<br/>" ?>
    <?php $dangerArray = BaseArrayHelper::map($dangers, 'id', 'name'); ?>
    <?php echo $form->field($model, 'danger_ids')->checkBoxList($dangerArray, ['multiple'=>true]); ?>
    <?php $sectorArray = BaseArrayHelper::map($sectors, 'id', 'name'); ?>
    <?php echo $form->field($model, 'sector_ids')->checkBoxList($sectorArray, ['multiple'=>true]); ?>
    <?php echo "<br/>" ?>
    <?php $countryArray = BaseArrayHelper::map($counties, 'id', 'name'); ?>
    <?php echo $form->field($model, 'country_ids')->checkBoxList($countryArray, ['multiple'=>true]); ?>
    <?php $landscapeArray = BaseArrayHelper::map($landscapes, 'id', 'name'); ?>
    <?php echo $form->field($model, 'landscape_ids')->checkBoxList($landscapeArray, ['multiple'=>true]); ?>
    <?php $zoneArray = BaseArrayHelper::map($zones, 'id', 'name'); ?>
    <?php echo $form->field($model, 'zone_ids')->checkBoxList($zoneArray, ['multiple'=>true]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('p:base', 'Create') : Yii::t('p:base', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('p:base', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
