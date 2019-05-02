<div id="translate_div">
<?php
use yii\helpers\Html;
use app\modules\translation\models\Language;

    echo Html::beginForm();
    // Translation from category
    if (Yii::$app->user->can('sysadmin')) {
        echo "<div class='box box-shadow'>";  // for sysadmins
    } else {
        echo "<div class='box box-shadow hidden'>"; // for members, ...
    }
    $category = yii::t('p:translate', 'Category');
    echo "<h2 class='box-header'> $category : </h2>";
    echo Html::dropDownList('_category', $translation['category'], $translation['categories']
                            , array( 'id' => '_category')
                            );
    echo "</div>";

    // Existing translation in...
    echo "<div class='box box-shadow'>";
    $from = yii::t('p:translate', 'From');
    echo "<h2 class='box-header'> $from " . $translation['currentFull'] . " : </h2>";
    echo Html::dropDownList('_message', $translation['message'], $translation['messages'],
        array('style' => 'max-width: 640px;' , 'id' => '_message'));
    echo "</div>";

    // New propositions for translations
    echo "<div class='box box-shadow'>";
    $to = yii::t('p:translate', 'To');
    echo "<h2 class='box-header'> $to ";
    echo Html::dropDownList('_lang2', $translation['user'], $translation['all'] , array( 'id' => '_lang2'));
    echo " : </h2>";

    echo "<div class='row'>";

    if (Yii::$app->user->can('sysadmin')) {
        echo "<ul id='_suggestions'>";  // for sysadmins
    } else {
        echo "<ul id='_suggestions' class='hidden'>"; // for members, ...
    }
    if (sizeof($translation['suggestions']) > 0) {
        foreach ($translation['suggestions'] as $suggestion) {
            echo "<li>" . $suggestion['translation'] . "</li>";
        }
    }
    echo "</ul>";

    echo Html::textArea('_translation', $translation['target'], array('rows' => 4, 'cols' => 68, 'id' => '_translation'));
    echo "</div>";

    echo "<div class='hint'>";
    Language::t('p:translate', 'Text elements in {brackets} must not be translated.',
        array('{brackets}' => '{' . yii::t('p:translate', 'brackets') . '}'));
    echo "</div>";
    echo "</div>";

    echo "<div class='row buttons'>";
    echo Html::button(yii::t('p:translate', 'Save'), array('class' => 'submit'));
    echo "</div>";

    echo Html::endForm();


    ?>

</div>

