<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;

use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php echo $this->render('////layouts/menu') ?>
    <div class="container-fluid container-main">
	   <br/>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

    <?php $form = ActiveForm::begin();ActiveForm::end(); // Just to include ActiveForm.js in the code, needed for modal dialogs?>
    <?php
      $help = Html::a('<span class="modal-help glyphicon glyphicon-question-sign"></span>', '#', [
            'target' => '_tmbHelp',
            'class' => 'disabled modal-help no-wait']);
      Modal::begin([
        'id' => 'common-modal',
        'options' => [ 'base-url' => Url::to('@web/index.php'), 'data-backdrop'=>'static'],
        'header' => '<h4> <span class="modal-title">Dialog</span> '.$help.'</h4>',
         ]); 
    ?>
    <div class="well"></div>
    <?php Modal::end(); ?>        
    <div id='ajaxActive' style='display: none;'></div>   

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; University Freiburg<?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
