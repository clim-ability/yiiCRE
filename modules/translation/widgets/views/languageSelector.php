<?php

use yii\helpers\Url;
use app\modules\translation\TranslationAsset;
$assets = TranslationAsset::register($this);

?>

 
<span id="languageSelector">
<?php 
if (\Yii::$app->user->can('sysadmin') || 
    \Yii::$app->user->can('admin') ||
    \Yii::$app->user->can('member')
) {

    $tooltip = yii::t('p:translate', 'Switch to translation mode');
    echo "<a id='translate' url='#' class='no-wait' title='".$tooltip."'></a>";
}
?>
    <script type="text/javascript">
        var currentLanguage = '<?php echo $currentLang; ?>';
        var baseUrl = '<?php echo Url::base(true); ?>';        
    </script>
</span>    
