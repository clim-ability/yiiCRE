<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\modules\translation\widgets\LanguageTranslate;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;

function tr($c, $m, $p = []) {
    echo LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p]);
}
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>
	<p><?php tr('sugg:about', 'Hello'); ?> 

    <code><?= __FILE__ ?></code>
</div>
