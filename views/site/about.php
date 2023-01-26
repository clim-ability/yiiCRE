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
<style>
 body {
   background-image: url('/images/regenbogen1.jpg') !important; 
 }
</style>
<div class="site-about">
 <div class="container-fluid">
  <div class="row">
   <div class="col-md-1">
   </div>
   <div class="col-md-6">

    <div class="glass70">

    <h3><?php tr('about', 'WArum noch ein Tool zum Klimawandel?'); ?></h3>

    <p>
	<?php tr('about', 'DIe Erde erwärmt sich, das Klima wandelt sich.'); ?>
	<?php tr('about', 'JEdoch nicht überall im gleichen Maß.'); ?>
	<?php tr('about', 'DEr globale Klimawandel hat regional sehr unterschiedliche Ausprägungen und Folgen.'); ?>
    </p>

    <p>
	<?php tr('about', 'VIele Menschen sehen sich mittlerweile einer regelrechten Flut von Informationen über den Klimawandel ausgesetzt, wobei es nicht immer leicht ist, die für einen selbst relevanten Informationen herauszufiltern.'); ?>
	<?php tr('about', 'HIer will der Climate Inspector Abhilfe schaffen.'); ?>
	<?php tr('about', 'DAs einfach aufgebaute Tool soll es seinen Benutzern ermöglichen, sich in wenigen Klicks über die ganz konkreten Folgen des Klimawandels an ihrem Standort zu informieren.'); ?>
        <?php tr('about', 'DAs Tool kombiniert dabei Daten aus regionalen, hoch aufgelösten Klimaprojektionen mit branchenspezifischen Klimarisiken und Anpassungsmaßnahmen.'); ?>
	<!-- ?php tr('about', 'Es kann dabei folgende Fragen beantworten:'); ? -->	
    </p>
	<!--p><ul>
	 <li><?php tr('about', 'Wie wird sich das Klima in meiner Gemeinde konkret ändern?'); ?></li>
	 <li><?php tr('about', 'Wird es mehr warme Tage und schwüle Nächte geben?'); ?> <?php tr('about', 'Wenn ja, wie viele?'); ?></li>
	 <li><?php tr('about', 'Wird es weniger kalte Tage geben?'); ?> <?php tr('about', 'Wenn ja, wie viele?'); ?></li>
	 <li><?php tr('about', 'Wie wird sich der Niederschlag im Sommer und im Winter entwickeln?'); ?>
         <?php tr('about', 'Und wie sieht es mit Starkregen aus?'); ?></li>
	 <li><?php tr('about', 'Welche möglichen Auswirkungen haben die klimatischen Änderungen auf mein Unternehmen?'); ?></li>
	 <li><?php tr('about', 'Wie stark sind die Unterschiede zwischen den einzelnen Klimawandel-Szenarien?'); ?>
         <?php tr('about', 'Und lohnt es sich daher, in Klimaschutz und Klimaanpassung zu investieren?'); ?></li>	 
	</ul></p-->
    
    </div>
    <div class="glass70">

    <h3><?php tr('about', 'WIe funktioniert der Upper Rhine Climate Inspector?'); ?></h3>

	<p>
      <?php tr('about', 'MIt Hilfe des URCI kann man sich in wenigen Klicks einen Überblick über die Auswirkungen des Klimawandels in seiner Heimatgemeinde verschaffen.'); ?> 
      <?php tr('about', 'DIe Daten zur Entwicklung wichtiger klimatischer Kenngrößen (Sommertage, Tropennächte, Frosttage, Starkregen, Winterniederschlag, Sommerniederschlag) können interaktiv abgefragt und angezeigt werden.'); ?> 
      <?php tr('about', 'DAbei kann zwischen zwei Szenarien („Klimaschutz-Szenario“ / RCP4.5 und „Weiter-wie bisher-Szenario“ / RCP8.5)  und dwei Zeithorizonten (nahe Zukunft = 2021-2050, mittlere Zukunft = 2041-2070 und ferne Zukunft = 2071-2100) unterschieden werden.'); ?> 	
	</p>
	
	<p>
      <?php tr('about', 'DAs Tool besteht im Wesentlichen aus den folgenden Funktionen'); ?>: 
     </p>	
	
	<p><ul>
      <!--li>
	  <?php tr('about', 'Die {Eingangskarte} gibt an, welche Parameter sich wo besonders stark oder besonders schwach verändern.', 
	    ['Eingangskarte' => '<strong>'.yii::t('about', 'Eingangskarte').'</strong>']); ?> 
      <?php tr('about', 'Sie ist als eine Synthese der ausgewerteten klimatischen Daten zu verstehen.'); ?> 
	  <?php tr('about', 'Durch Klick in die Karte werden die Änderungssignale aller Parameter am ausgewählten Standort angezeigt.'); ?>
      </li-->
      <li>
	  <?php tr('about', 'DIe {Klimakarten} beziehen sich jeweils auf den ausgewählten Parameter und zeigen die prognostizierten Änderungssignale im Vergleich zur Periode 1971-2000.', 
	    ['Klimakarten' => '<strong>'.yii::t('about', 'KLimakarten').'</strong>']); ?> 
      <?php tr('about', 'DIe Referenzwerte helfen dabei, das Ausmaß der klimatischen Änderungen einzuschätzen.'); ?>
      <?php tr('about', 'DA das Klima stark von der Meereshöhe abhängt, wird neben der nächstgelegenen Klimastation auch ein Referenzwert einer Klimastation in ähnlicher Höhenlage angegeben.'); ?>	  
	  </li>
	  <li>
	  <?php tr('about', '{Referenzwerte} VOn mehreren Messstation in der Region ermöglichen zudem einen Vergleich mit Klima während des Zeitraums 1971-2000, das von den meisten Menschen als „normal“ eingeschätzt wird.', 
	    ['Referenzwerte' => '<strong>'.yii::t('about', 'REferenzwerte').'</strong>']); ?> 
          <?php tr('about', 'DIe Referenzwerte helfen dabei, das Ausmaß der klimatischen Änderungen einzuschätzen.'); ?>
          <?php tr('about', 'DA das Klima stark von der Meereshöhe abhängt, wird neben der nächstgelegenen Klimastation auch ein Referenzwert einer Klimastation in ähnlicher Höhenlage angegeben.'); ?>	
	  </li>
	  <li>
	  <?php tr('about', 'SPeziell für Unternehmen werden branchenspezifische {Risiken} des Klimawandels und ihre Auswirkungen auf verschiedene Unternehmensbereiche angezeigt.', 
	    ['Risiken' => '<strong>'.yii::t('about', 'RIsiken').'</strong>']); ?> 
	  <?php tr('about', 'DIese Informationen ermöglichen eine Einschätzung der konkreten Folgen des Klimawandels an einem individuellen Standort und für verschiedene Branchen.'); ?>
	  <?php tr('about', 'DIe Risiken werden dabei verschiedenen klimatischen Stressoren wie z. B. Hitze oder Starkregen zugeordnet.'); ?>	  
	  </li>	  
          <li>
	  <?php tr('about', ' ALs Gegengewicht zu den Klimarisiken können branchenspezifische {Anpassungsmaßnahmen} angezeigt werden', 
	    ['Anpassungsmaßnahmen' => '<strong>'.yii::t('about', 'ANpassungsmaßnahmen').'</strong>']); ?> 
          <?php tr('about', 'AUch diese sind einzelnen klimatischen Stressoren zugeordnet.'); ?> 
          <?php tr('about', 'SIe dienen dazu, Klimarisiken zu minimieren und Anpassungsmaßnahmen frühzeitig zu erkennen und umzusetzen.'); ?> 
          </li>

	</ul></p>  
    </div>

    <div class="glass70">
    <h3><?php tr('about', 'WElche wissenschaftliche Basis hat der Climate Inspector?'); ?></h3>	

	<p>
	  <?php tr('about', 'IM Upper Rhine Climate Inspector wurden klimatischen Daten mit einer Auflösung von ca. 12*12km kartographisch aufbereitet.'); ?> 
	  <?php tr('about', 'DIe Daten wurden vom Deutschen Wetterdienst zur Verfügung gestellt und entstammen der EURO-CORDEX-Initiative.'); ?> 
	  <?php tr('about', 'ZEitpunkt der Modellläufe war November 2016.'); ?>	
	  <?php tr('about', 'DIe hinterlegten Daten geben die Mittelwerte eines Ensembles aus 16 Klimamodellen wieder, die das zukünftige Klima der Oberrheinregion rechnerisch simuliert haben.'); ?> 
	  <?php tr('about', 'TRotz der großen Bandbreite der simulierten Ergebnisse lassen sich gewisse Trends mit hoher Wahrscheinlichkeit vorhersagen.'); ?> 
	  <?php tr('about', 'DIe wichtigsten klimatischen Trends der Oberrheinregion sind durch die 6 ausgewerteten klimatischen Parameter abgedeckt.'); ?>	  
	</p>
	<p>
          <?php tr('about', 'GRundlage für die Klimarisiken und Anpassungsmaßnahmen ist die Auswertung von über 200 Interviews, die mit regionalen Unternehmen im Rahmen der Interreg-Projekte Clim’Ability (2016-2019) und Clim’Ability Design (2019-2022) geführt wurden.'); ?> 
        </p>
    </div>	
   </div>	
   <div class="col-md-1">
   </div>  
   <div class="col-md-2">
    <?php echo Html::img("@web/images/logo_climate_inspector.png", ['width'=>'350', 'id'=>'logo', 'alt'=>'climate inspector logo']); ?>       
   </div>
  </div>	  
 </div>	
	
</div>
