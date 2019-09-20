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
 <div class="container-fluid">
  <div class="row">
   <div class="col-md-3">
   </div>
   <div class="col-md-6">

    <h3><?php tr('about', 'Warum noch ein Tool zum Klimawandel?'); ?></h3>

    <p>
	<?php tr('about', 'Die Erde erwärmt sich, das Klima wandelt sich.'); ?>
	<?php tr('about', 'Jedoch nicht überall im gleichen Maß.'); ?>
	<?php tr('about', 'Der globale Klimawandel hat regional sehr unterschiedliche Ausprägungen und Folgen.'); ?>
    </p>

    <p>
	<?php tr('about', 'Viele Menschen sehen sich mittlerweile einer regelrechten Flut von Informationen über den Klimawandel ausgesetzt, wobei es nicht immer leicht ist, die für einen selbst relevanten Informationen herauszufiltern.'); ?>
	<?php tr('about', 'Hier will der Climate Inspector Abhilfe schaffen.'); ?>
	<?php tr('about', 'Das einfach aufgebaute Tool soll es seinen Benutzern ermöglichen, sich in wenigen Klicks über die ganz konkreten Folgen des Klimawandels an ihrem Standort zu informieren.'); ?>
	<?php tr('about', 'Es kann dabei folgende Fragen beantworten:'); ?>	
    </p>
	<p><ul>
	 <li><?php tr('about', 'Wie wird sich das Klima in meiner Gemeinde konkret ändern?'); ?></li>
	 <li><?php tr('about', 'Wird es mehr warme Tage und schwüle Nächte geben?'); ?> <?php tr('about', 'Wenn ja, wie viele?'); ?></li>
	 <li><?php tr('about', 'Wird es weniger kalte Tage geben?'); ?> <?php tr('about', 'Wenn ja, wie viele?'); ?></li>
	 <li><?php tr('about', 'Wie wird sich der Niederschlag im Sommer und im Winter entwickeln?'); ?>
         <?php tr('about', 'Und wie sieht es mit Starkregen aus?'); ?></li>
	 <li><?php tr('about', 'Welche möglichen Auswirkungen haben die klimatischen Änderungen auf mein Unternehmen?'); ?></li>
	 <li><?php tr('about', 'Wie stark sind die Unterschiede zwischen den einzelnen Klimawandel-Szenarien?'); ?>
         <?php tr('about', 'Und lohnt es sich daher, in Klimaschutz und Klimaanpassung zu investieren?'); ?></li>	 
	</ul></p>
    
    <h3><?php tr('about', 'Wie funktioniert der Upper Rhine Climate Inspector?'); ?></h3>

	<p>
      <?php tr('about', 'Mit Hilfe des URCI kann man sich in wenigen Klicks einen Überblick über die Auswirkungen des Klimawandels in seiner Heimatgemeinde verschaffen.'); ?> 
      <?php tr('about', 'Die Daten zur Entwicklung wichtiger klimatischer Kenngrößen (Sommertage, Tropennächte, Frosttage, Starkregen, Winterniederschlag, Sommerniederschlag) können interaktiv abgefragt und angezeigt werden.'); ?> 
      <?php tr('about', 'Dabei kann zwischen zwei Szenarien („Klimaschutz-Szenario“ / RCP4.5 und „Weiter-wie bisher-Szenario“ / RCP8.5)  und dwei Zeithorizonten (nahe Zukunft = 2021-2050, mittlere Zukunft = 2041-2070 und ferne Zukunft = 2071-2100) unterschieden werden.'); ?> 	
	</p>
	
	<p>
      <?php tr('about', 'Das Tool besteht im Wesentlichen aus den folgenden Funktionen'); ?>: 
     </p>	
	
	<p><ul>
      <li>
	  <?php tr('about', 'Die {Eingangskarte} gibt an, welche Parameter sich wo besonders stark oder besonders schwach verändern.', 
	    ['Eingangskarte' => '<strong>'.yii::t('about', 'Eingangskarte').'</strong>']); ?> 
      <?php tr('about', 'Sie ist als eine Synthese der ausgewerteten klimatischen Daten zu verstehen.'); ?> 
	  <?php tr('about', 'Durch Klick in die Karte werden die Änderungssignale aller Parameter am ausgewählten Standort angezeigt.'); ?>
      </li>
      <li>
	  <?php tr('about', 'Die übrigen {Klimakarten} beziehen sich jeweils nur auf den ausgewählten Parameter und zeigen die prognostizierten Änderungssignale im Vergleich zur Periode 1971-2000.', 
	    ['Klimakarten' => '<strong>'.yii::t('about', 'Klimakarten').'</strong>']); ?> 
      <?php tr('about', 'Durch Klick in die Karte wird das Änderungssignal des ausgewählten Parameters am ausgewählten Standort angezeigt.'); ?>
      <?php tr('about', 'Die kartographischen Darstellungen machen deutlich, dass die klimatischen Änderungen innerhalb der Region zum Teil stark variieren.'); ?>	  
	  </li>
	  <li>
	  <?php tr('about', '{Referenzwerte} von mehreren Messstation in der Region ermöglichen zudem einen Vergleich mit dem aktuellen Klima der Region und helfen dabei, das Ausmaß der klimatischen Änderungen einzuschätzen.', 
	    ['Referenzwerte' => '<strong>'.yii::t('about', 'Referenzwerte').'</strong>']); ?> 
	  </li>
	  <li>
	  <?php tr('about', 'Speziell für Unternehmen werden {potentielle Folgen} des Klimawandels und ihre Auswirkungen auf verschiedene Unternehmensbereiche genannt.', 
	    ['potentielle Folgen' => '<strong>'.yii::t('about', 'potentielle Folgen').'</strong>']); ?> 
	  <?php tr('about', 'Diese Informationen helfen den Benutzern, die konkreten Folgen des Klimawandels an ihrem individuellen Standort einzuschätzen.'); ?>
	  <?php tr('about', 'Sie stellen die Basis für eine vertiefte Auseinandersetzung mit individuellen Klimaanpassungsmaßnahmen dar und geben Hinweise auf potentielle Risiken und Chancen.'); ?>	  
	  </li>	  
	</ul></p>  
	
    <h3><?php tr('about', 'Wie ist der Climate Inspector wissenschaftlich hinterlegt?'); ?></h3>	

	<p>
	  <?php tr('about', 'Im Upper Rhine Climate Inspector (URCI) wurden klimatischen Daten mit einer Auflösung von ca. 18*18km kartographisch aufbereitet.'); ?> 
	  <?php tr('about', 'Die Daten wurden vom Deutschen Wetterdienst zur Verfügung gestellt, Bearbeitungsstand ist November 2016.'); ?> 
	  <?php tr('about', 'Die Daten entstammen der EURO-CORDEX-Initiative und geben die Mittelwerte eines Ensembles aus 16 Klimamodellen wieder, die das zukünftige Klima der Oberrheinregion rechnerisch simulieren.'); ?>	  
	  <?php tr('about', 'Trotz der großen Bandbreite der simulierten Ergebnisse lassen sich gewisse Trends mit hoher Wahrscheinlichkeit vorhersagen.'); ?> 
	  <?php tr('about', 'Die wichtigsten klimatischen Trends der Oberrheinregion sind durch die 6 ausgewerteten klimatischen Parameter abgedeckt.'); ?>	  
	</p>	
   </div>	
   <div class="col-md-3">
     <?php echo Html::img("@web/images/logo_climate_inspector.png", ['width'=>'200', 'id'=>'logo', 'alt'=>'climate inspector logo']); ?>    
   </div>
  </div>	  
 </div>	
	
</div>
