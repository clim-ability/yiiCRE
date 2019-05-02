<?php

use yii\helpers\HtmlPurifier;

$translation = \Yii::t($category, $message, $params, $language);
echo "<span class='languageTranslate' data-category='$category' data-message='$message'>";
echo HtmlPurifier::process($translation);
echo "</span>";
?>
