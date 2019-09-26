<?php
use app\modules\libraries\bundles\MapAsset;
use yii\helpers\Url;
use app\modules\translation\widgets\LanguageTranslate;
use app\models\User;

$assets = MapAsset::register($this);

header('Access-Control-Allow-Origin: *');

function tr($c, $m, $p = []) {
    echo LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p]);
}
?>

<script>
    var mapBaseUrl = "<?php echo $assets->baseUrl; ?>";
	var apiBaseUrl = "<?php echo Url::home('https'); ?>";
</script>

<div id="climateinspector">


 <div class="container-fluid">


  <div class="row" style="margin: 8px;">
   <div class="col-md-6 "> <!-- eoEvents Upper-Left Corner -->




   <div id="map"></div>

   </div> <!-- end md-6 -->

   <div class="col-md-6" > <!-- Tabbed Upper-Right Corner -->

      <h1 class="my-4"><?php tr('main', 'Changing of climate'); ?>
        <small><?php tr('main', 'at the Upper Rhine'); ?></small>
      </h1>

    <div class="row" id="selectionrow">
 	  <div class="col-md-4">
	   <?php tr('hazard', 'Klima-Parameter'); ?><br/>
       <select v-model="hazard" v-on:change="updateParameters" class="form-control">
        <option v-for="hazard in hazards" v-bind:value="hazard.name">
         {{ hazard.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-4">
	    <?php tr('hazard', 'Zeithorizont'); ?><br/>
       <select v-model="epoch" v-on:change="updateParameters" class="form-control">
        <option v-for="epoch in epochs" v-bind:value="epoch.name">
         {{ epoch.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-4">
	   <?php tr('hazard', 'Szenario'); ?><br/>
       <select v-model="scenario" v-on:change="updateParameters" class="form-control">
        <option v-for="scenario in scenarios" v-bind:value="scenario.name">
         {{ scenario.label }} 
        </option>
       </select>
	  </div>		 
	</div> 
	
    <div id="informationfield">
    <template v-if="info === 'none'">
	<br/>
	<h3><?php tr('hazard', 'To start choose your location by {clicking on the map} and/or change parameters.', 
	    ['clicking on the map' => '<strong>'.yii::t('hazard', 'clicking on the map').'</strong>']); ?> 
    </h3>
	<br/>
	<p>
	<?php tr('hazard', 'Die Karte zeigt den Klimawandel in der transnationalen Oberrheinregion, illustriert an {6 klimatischen Parametern}, deren projizierte Änderungen statistisch ausgewertet wurden.', 
	    ['6 klimatischen Parametern' => '<strong>'.yii::t('hazard', '6 klimatischen Parametern').'</strong>']); ?> 
	<?php tr('hazard', 'Jedes {Tortendiagramm} steht für eine Rasterzelle mit den Ausmaßen 18*18 km, für die die klimatischen Änderungen errechnet wurden.', 
	    ['Tortendiagramm' => '<strong>'.yii::t('hazard', 'Tortendiagramm').'</strong>']); ?> 
	<?php tr('hazard', 'Die Farben stehen für die einzelnen untersuchten Parameter.'); ?> 
	<?php tr('hazard', 'Die kräftigen Farbtöne bedeuten eine im regionalen Vergleich besonders starke, die hellen Farbtöne eine besonders schwache erwartete Änderung.'); ?> 
	<?php tr('hazard', 'Hat ein Tortendiagramm z. B. einen dunkelroten Anteil, dann werden sich dort die Tropennächte besonders stark erhöhen ({oberstes Quartil} = oberste 25% innerhalb der Oberrheinregion). ', 
	    ['oberstes Quartil' => '<strong>'.yii::t('hazard', 'oberstes Quartil').'</strong>']); ?> 
	<?php tr('hazard', 'Hat es hingegen einen schwachroten Anteil dann werden dort die Tropennächte eher wenig zunehmen ({unterstes Quartil} = untere 25% innerhalb der Oberrheinregion).', 
	    ['unterstes Quartil' => '<strong>'.yii::t('hazard', 'unterstes Quartil').'</strong>']); ?> 
	</p>
	
	<p>
	<?php tr('hazard', 'Wichtig: Die Bewertungen beziehen sich auf den statistischen Vergleich der Rasterzellen innerhalb der Region und machen {keine Aussage zu absoluten Änderungswerten}.', 
	    ['keine Aussage zu absoluten Änderungswerten' => '<strong>'.yii::t('hazard', 'keine Aussage zu absoluten Änderungswerten').'</strong>']); ?> 
	<?php tr('hazard', 'Wir empfehlen daher, auf jeden Fall auch die Werte zu den einzelnen Parametern abzurufen.'); ?> 
	</p>
    </template>	
   
    <template v-if="info !== 'none'">
	 <br/>	
	 <table class="table">
	  <tr>
	   <td><?php tr('main', 'Nearest City'); ?>:</td><td>{{ info.nearest_city.name }}</td>
	   <td><?php tr('main', 'Elevation'); ?>:</td><td>{{ roundedElevation }} m </td><td></td>
	   </tr>
		  <tr>   
	   <td><?php tr('main', 'Nearest Station'); ?>:</td><td>{{nearestStation.name}} ({{nearestStation.abbreviation}})</td>
	   <td><?php tr('main', 'Elevation'); ?>:</td><td>{{nearestStation.elevation}} m</td><td><?php tr('hazards', 'Distance'); ?>: {{Math.round(parseFloat(nearestStation.distance/1000)).toString()}} km</td>
	  </tr>	
	 </table>	
     </template>		 
	 <template v-if="((info !== 'none') && (currHazard === 'all'))">
	 <br/>	
	 <table class="table">
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('fd')" title='<?php echo \Yii::t('Hazard:description', 'fd', []); ?>' > <?php tr('Hazard:Name', 'fd'); ?></span></td>
	   <td>{{roundedFd.tendency}}</td><td>{{roundedFd.min}}</td><td>{{roundedFd.to}}</td><td>{{roundedFd.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{nearestStation.fd}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('sd')" title='<?php echo \Yii::t('Hazard:description', 'sd', []); ?>' > <?php tr('Hazard:Name', 'sd'); ?></span></td>
	   <td>{{roundedSd.tendency}}</td><td>{{roundedSd.min}}</td><td>{{roundedSd.to}}</td><td>{{roundedSd.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{nearestStation.sd}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('tr')" title='<?php echo \Yii::t('Hazard:description', 'tr', []); ?>' > <?php tr('Hazard:Name', 'tr'); ?></span></td>
	   <td>{{roundedTr.tendency}}</td><td>{{roundedTr.min}}</td><td>{{roundedTr.to}}</td><td>{{roundedTr.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{nearestStation.tr}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>	  
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('rr20')" title='<?php echo \Yii::t('Hazard:description', 'rr20', []); ?>' > <?php tr('Hazard:Name', 'rr20'); ?></span></td>
	   <td>{{roundedRr20.tendency}}</td><td>{{roundedRr20.min}}</td><td>{{roundedRr20.to}}</td><td>{{roundedRr20.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{nearestStation.rr20}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('rr_winter')" title='<?php echo \Yii::t('Hazard:description', 'rr_winter', []); ?>' > <?php tr('Hazard:Name', 'rr_winter'); ?></span></td>
	   <td>{{roundedRw.tendency}}</td><td>{{roundedRw.min}}</td><td>{{roundedRw.to}}</td><td>{{roundedRw.max}}</td>
	   <td>%</td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{nearestStation.rr_winter}}</td><td>mm</td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('rr_summer')" title='<?php echo \Yii::t('Hazard:description', 'rr_summer', []); ?>' > <?php tr('Hazard:Name', 'rr_summer'); ?></span></td>
	   <td>{{roundedRs.tendency}}</td><td>{{roundedRs.min}}</td><td>{{roundedRs.to}}</td><td>{{roundedRs.max}}</td>
	   <td>%</td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{nearestStation.rr_summer}}</td><td>mm</td>
	  </tr>
	 </table>
	<br/>
	 <p>
      <?php tr('hazard', 'Alle Änderungen beziehen sich auf den Bezugszeitraum von 1971-2000.'); ?> 
     </p>	
    </template>	
	
	<template v-if="((info !== 'none') && (currHazard === 'fd'))">
	 <h2><?php tr('Hazard:Name', 'fd'); ?></h2>
	 <p><?php tr('Hazard:description', 'fd'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedFd.tendency}} {{roundedFd.min}} bis  {{roundedFd.max}} Frosttagen zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.fd}} Tage pro Jahr.'); ?>
     </p>
    </template>
	
	<template v-if="((info !== 'none') && (currHazard === 'sd'))">
	 <h2><?php tr('Hazard:Name', 'sd'); ?></h2>
	 <p><?php tr('Hazard:description', 'sd'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedSd.tendency}} {{roundedSd.min}} bis  {{roundedSd.max}} Sommertagen zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.sd}} Tage pro Jahr.'); ?>
     </p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'tr'))">
	 <h2><?php tr('Hazard:Name', 'tr'); ?></h2>
	 <p><?php tr('Hazard:description', 'tr'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedTr.tendency}} {{roundedTr.min}} bis  {{roundedTr.max}} Tropennächten zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.tr}} Nächte pro Jahr.'); ?>
     </p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'rr20'))">
	 <h2><?php tr('Hazard:Name', 'rr20'); ?></h2>
	 <p><?php tr('Hazard:description', 'rr20'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedRr20.tendency}} {{roundedRr20.min}} bis  {{roundedRr20.max}} Starkregentagen zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.rr20}} Tage pro Jahr'); ?>
     </p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'rr_winter'))">
	 <h2><?php tr('Hazard:Name', 'rr_winter'); ?></h2>
	 <p><?php tr('Hazard:description', 'rr_winter'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedRw.tendency}} {{roundedRw.min}} bis  {{roundedRw.max}} % an Winterniederschlag zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.rr_winter}} mm für die Wintermonate.'); ?>
     </p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'rr_summer'))">
	 <h2><?php tr('Hazard:Name', 'rr_summer'); ?></h2>
	 <p><?php tr('Hazard:description', 'rr_summer'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedRs.tendency}} {{roundedRs.min}} bis  {{roundedRs.max}} % an Sommerniederschlag zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.rr_summer}} mm für die Sommermonate.'); ?>
     </p>
    </template>

    <p><?php 
	   if(User::hasRole('sysadmin')) {
	     tr('hazards', 'Dabei können die Naturgefahren {{dangerText}} auftreten.'); 
	     echo '<br/><table class="table">';
		 echo '<row v-for="danger in dangers">';
		   echo '<td>{{danger.name}}</td><td>{{danger.value}}</td>';
		   echo '<td><button v-on:click="voteDanger(danger.name, 1.0)">'; 
		     tr('Danger:name', 'Mehr'); 
		   echo '</button></td>';
		   echo '<td><button v-on:click="voteDanger(danger.name, -1.0)">';
		     tr('Danger:name', 'Weniger');
		   echo '</button></td>';
		 echo '</row>';
         echo '</table>';
	   }	  
	   ?></p>

	</div> <!-- end #informationfield -->
   </div> <!-- end md-6 -->

  </div> <!-- end row -->

	
  <div class="row">
   <div class="col-md-12 "><!-- for better alignment -->
  </div>
  </div> <!-- end UPPER row -->
				
  <div class="row">
   <div class="col-md-6 "> <!-- table Lower-Right Corner-->
   </div>
  
   <div class="col-md-6 "><!-- charts Lower-Left Corner -->
   </div>
  </div> <!-- end LOWER row -->	

 </div> <!-- end container-fluid -->

    </div>
</div>
	 



