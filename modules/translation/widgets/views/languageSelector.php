<?php

use yii\helpers\Url;
use app\models\User;
use app\modules\translation\TranslationAsset;
$assets = TranslationAsset::register($this);

?>

 
<span id="languageSelector">
<?php 
if (User::hasRole('sysadmin')
) {

    $tooltip = yii::t('p:translate', 'Switch to translation mode');
    echo "<a id='translate' url='#' class='no-wait' title='".$tooltip."'></a>";
}
?>
    <script type="text/javascript">
        var currentLanguage = '<?php echo $currentLang; ?>';
        var baseUrl = '<?php echo Url::base(''); ?>';        
    </script>
</span>    
