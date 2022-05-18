<?php
use app\modules\libraries\bundles\MapAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use app\modules\translation\widgets\LanguageTranslate;
use app\models\User;

$assets = MapAsset::register($this);

header('Access-Control-Allow-Origin: *');

function tr($c, $m, $p = []) {
    echo LanguageTranslate::widget(['category' => $c, 'message' => $m, 'params' => $p]);
}

function md() {
	return ('abs' == Yii::$app->getRequest()->getQueryParam('mode'));
}
?> 

<script>
    var mapBaseUrl = "<?php echo $assets->baseUrl; ?>";
	var apiBaseUrl = "<?php echo Url::home('https'); ?>";
	var absMode = "<?php echo md()?'abs':'rel'; ?>";
</script>

<style>
 body {
   background-image: url('/images/wolke4.jpg') !important; 
 }
</style>

<div id="climateinspector">


 <div class="container-fluid">


  <div class="row" style="margin: 8px;">
   <div class="col-md-6 "> <!-- eoEvents Upper-Left Corner -->



   <div class="glass70 map">
       <div id="map"></div>
   </div>    

   </div> <!-- end md-6 -->

   <div class="col-md-6" > <!-- Tabbed Upper-Right Corner -->

    <div class="glass70">

      <h1 class="my-4"><?php tr('main', 'Changing of climate'); ?>
        <small><?php tr('main', 'at the Upper Rhine'); ?></small>
      </h1>

      <div class="row" id="selectionrow">
 	  <div class="col-md-3">
	   <?php tr('hazard', 'Klima-Parameter'); ?><br/>
       <select v-model="hazard" v-on:change="updateParameters" class="form-control">
        <option v-for="hazard in hazards" v-bind:value="hazard.name">
         {{ hazard.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-3">
	    <?php tr('hazard', 'Zeithorizont'); ?><br/>
       <select v-model="epoch" v-on:change="updateParameters" class="form-control">
        <option v-for="epoch in epochs" v-bind:value="epoch.name">
         {{ epoch.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-3">
	   <?php tr('hazard', 'Szenario'); ?><br/>
       <select v-model="scenario" v-on:change="updateParameters" class="form-control">
        <option v-for="scenario in scenarios" v-bind:value="scenario.name">
         {{ scenario.label }} 
        </option>
       </select>
	  </div>
	  <div class="col-md-3">
	   <?php tr('sector', 'Branche'); ?><br/>
       <select v-model="sector" v-on:change="updateParameters" class="form-control">
        <option v-for="sector in sectors" v-bind:value="sector.name">
         {{ sector.label }} 
        </option>
       </select>
	  </div>  
	  
	</div> 

      </div>
	
    <br/>

     <!--  https://www.w3schools.com/howto/howto_js_tabs.asp  -->
	
	<div id="tabselect" class="tab">
		<button class="tablinks" v-on:click="activateTab('climate_info')">Climate</button>
		<!--button class="tablinks" v-on:click="activateTab('impacts_adaptions')">Impacts & Adaptions</button-->
		<!--button class="tablinks" v-on:click="activateTab('other')" disabled>Other</button-->
    </div>
    
    
    <div class='glass70 tabbody'>  

	<div id="other" v-show="isTabActive('other')">
	  <br/>	
	  
      <p> other here</p>

    </div> <!-- end div other  --> 

    <div id="impacts_adaptions" v-show="isTabActive('impacts_adaptions')">
	  <br/>	
	  
      <p> impacts here</p>
	  <d3-network ref='net' :net-nodes="nodes" :net-links="links" :options="options"  :link-cb="lcb" @node-click='setCurrentNode' />

    </div> <!-- end div impacts_adaptions  --> 

    <div id="climate_info" v-show="isTabActive('climate_info')">
    <template v-if="info === 'none'">
	<br/>
	<h3 style="display:inline;"><?php tr('hazard', 'To start choose your location by'); ?> </h3>
	<button class="btn-lg" style="display:inline;" v-on:click="getGeoLocation()"><b><?php tr('hazard', 'clicking on the map'); ?></b></button>
	<h3 style="display:inline;"><?php tr('hazard', 'and/or change parameters.'); ?></h3>
	<br/>
	</template>

   
    <template v-if="info !== 'none'">
	 <br/>	
	 <table class="table">
	  <tr>
	   <td><img src='https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/images/marker-icon.png' /> 
	       <?php tr('main', 'Nearest City'); ?>:</td><td>{{ info.nearest_city.name }}</td>
	   <td><?php tr('main', 'Elevation'); ?>:</td><td>{{ roundedElevation }} m </td>
	   <td> {{ info.landscape.name }} </td>
	   </tr>
	  <tr>   
	     <td><div class="leaflet-marker-icon extra-marker extra-marker-square-yellow leaflet-zoom-animated leaflet-clickable" tabindex="0" style="position: relative; display: inline-block;"><i v-bind:number="nearestStation.abbreviation" style="color: black" class=" fa fa-number"></i></div>
	       <?php tr('main', 'Nearest Station'); ?>:</td><td>{{nearestStation.name}}</td>
	     <td><?php tr('main', 'Elevation'); ?>:</td><td>{{nearestStation.elevation}} m</td>
	     <td><?php tr('hazards', 'Distance'); ?>: {{Math.round(parseFloat(nearestStation.distance/1000)).toString()}} km</td>
	  </tr>	
	  <tr>   
	   <td><div class="leaflet-marker-icon extra-marker extra-marker-square-yellow leaflet-zoom-animated leaflet-clickable" tabindex="0" style="position: relative; display: inline-block;"><i v-bind:number="bestStation.abbreviation" style="color: black" class=" fa fa-number"></i></div>
	       <?php tr('main', 'Reference Station'); ?>:</td><td>{{bestStation.name}}</td>
	   <td><?php tr('main', 'Elevation'); ?>:</td><td>{{bestStation.elevation}} m</td>
	   <td><?php tr('hazards', 'Distance'); ?>: {{Math.round(parseFloat(bestStation.distance/1000)).toString()}} km</td>
	  </tr>	

	 </table>	
     </template>		 
	 <template v-if="((info !== 'none') && (currHazard === 'all'))">
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
	
	 <br/>
	 <table class="table">
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('fd')" title='<?php echo \Yii::t('Hazard:description', 'fd', []); ?>' > <?php tr('Hazard:Name', 'fd'); ?></span></td>
	   <td>{{roundedFd.tendency}}</td><td>{{roundedFd.min}}</td><td>{{roundedFd.to}}</td><td>{{roundedFd.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{bestStation.fd}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('sd')" title='<?php echo \Yii::t('Hazard:description', 'sd', []); ?>' > <?php tr('Hazard:Name', 'sd'); ?></span></td>
	   <td>{{roundedSd.tendency}}</td><td>{{roundedSd.min}}</td><td>{{roundedSd.to}}</td><td>{{roundedSd.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{bestStation.sd}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('tr')" title='<?php echo \Yii::t('Hazard:description', 'tr', []); ?>' > <?php tr('Hazard:Name', 'tr'); ?></span></td>
	   <td>{{roundedTr.tendency}}</td><td>{{roundedTr.min}}</td><td>{{roundedTr.to}}</td><td>{{roundedTr.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{bestStation.tr}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>	  
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('rr20')" title='<?php echo \Yii::t('Hazard:description', 'rr20', []); ?>' > <?php tr('Hazard:Name', 'rr20'); ?></span></td>
	   <td>{{roundedRr20.tendency}}</td><td>{{roundedRr20.min}}</td><td>{{roundedRr20.to}}</td><td>{{roundedRr20.max}}</td>
	   <td><?php tr('hazards', 'days/year'); ?></td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{bestStation.rr20}}</td><td><?php tr('hazards', 'days/year'); ?></td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('rr_winter')" title='<?php echo \Yii::t('Hazard:description', 'rr_winter', []); ?>' > <?php tr('Hazard:Name', 'rr_winter'); ?></span></td>
	   <td>{{roundedRw.tendency}}</td><td>{{roundedRw.min}}</td><td>{{roundedRw.to}}</td><td>{{roundedRw.max}}</td>
	   <td>%</td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{bestStation.rr_winter}}</td><td>mm</td>
	  </tr>
	  <tr>
	   <td><span class='inactive' v-on:click="switchHazard('rr_summer')" title='<?php echo \Yii::t('Hazard:description', 'rr_summer', []); ?>' > <?php tr('Hazard:Name', 'rr_summer'); ?></span></td>
	   <td>{{roundedRs.tendency}}</td><td>{{roundedRs.min}}</td><td>{{roundedRs.to}}</td><td>{{roundedRs.max}}</td>
	   <td>%</td>
	   <td></td><td><?php tr('hazards', 'Referenzwert'); ?></td><td>{{bestStation.rr_summer}}</td><td>mm</td>
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
  	  <?php tr('hazards', 'Für die Referenz-Wetterstation {{bestStation.name}} in ähnlicher Höhenlage ({{ info.landscape.name }}) beträgt er {{bestStation.fd}} Tage pro Jahr.'); ?>
     </p>
	 <p><?php tr('hazards', 'Mehr Informationen finden Sie in unserem Flyer über'); 
	  echo ' ';
	  echo Html::a(\Yii::t('hazards', 'Wintertourismus'), '/media/flyer/Ski-ClimAbility.de.pdf', ['class' => 'no-wait', 'target'=>'_flyer']);
	  echo ".";
	  ?></p>
	  <?php
	  ?>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'sd'))">
	 <h2><?php tr('Hazard:Name', 'sd'); ?></h2>
	 <p><?php tr('Hazard:description', 'sd'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedSd.tendency}} {{roundedSd.min}} bis  {{roundedSd.max}} Sommertagen zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.sd}} Tage pro Jahr.'); ?>
  	  <?php tr('hazards', 'Für die Referenz-Wetterstation {{bestStation.name}} in ähnlicher Höhenlage ({{ info.landscape.name }}) beträgt er {{bestStation.sd}} Tage pro Jahr.'); ?>
	</p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'tr'))">
	 <h2><?php tr('Hazard:Name', 'tr'); ?></h2>
	 <p><?php tr('Hazard:description', 'tr'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedTr.tendency}} {{roundedTr.min}} bis  {{roundedTr.max}} Tropennächten zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.tr}} Nächte pro Jahr.'); ?>
  	  <?php tr('hazards', 'Für die Referenz-Wetterstation {{bestStation.name}} in ähnlicher Höhenlage ({{ info.landscape.name }}) beträgt er {{bestStation.tr}} Nächte pro Jahr.'); ?>
     </p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'rr20'))">
	 <h2><?php tr('Hazard:Name', 'rr20'); ?></h2>
	 <p><?php tr('Hazard:description', 'rr20'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedRr20.tendency}} {{roundedRr20.min}} bis  {{roundedRr20.max}} Starkregentagen zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.rr20}} Tage pro Jahr.'); ?>
  	  <?php tr('hazards', 'Für die Referenz-Wetterstation {{bestStation.name}} in ähnlicher Höhenlage ({{ info.landscape.name }}) beträgt er {{bestStation.rr20}} Tage pro Jahr.'); ?>
     </p>
	 <p><?php tr('hazards', 'Mehr Informationen finden Sie in unserem Flyer über'); 
	  echo ' ';
	  echo Html::a(\Yii::t('hazards', 'Starkregen'), '/media/flyer/Flood-ClimAbility.de.pdf', ['class' => 'no-wait', 'target'=>'_flyer']);
	  echo ".";
	  ?></p>	  
	  
	  
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'rr_winter'))">
	 <h2><?php tr('Hazard:Name', 'rr_winter'); ?></h2>
	 <p><?php tr('Hazard:description', 'rr_winter'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedRw.tendency}} {{roundedRw.min}} bis  {{roundedRw.max}} % an Winterniederschlag zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.rr_winter}} mm für die Wintermonate.'); ?>
  	  <?php tr('hazards', 'Für die Referenz-Wetterstation {{bestStation.name}} in ähnlicher Höhenlage ({{ info.landscape.name }}) beträgt er {{bestStation.rr_winter}} mm für die Wintermonate.'); ?>
     </p>
	 <p><?php tr('hazards', 'Mehr Informationen finden Sie in unserem Flyer über'); 
	  echo ' ';
	  echo Html::a(\Yii::t('hazards', 'Hochwasser'), '/media/flyer/Flood-ClimAbility.de.pdf', ['class' => 'no-wait', 'target'=>'_flyer']);
	  echo ".";
	  ?></p>
    </template>

	<template v-if="((info !== 'none') && (currHazard === 'rr_summer'))">
	 <h2><?php tr('Hazard:Name', 'rr_summer'); ?></h2>
	 <p><?php tr('Hazard:description', 'rr_summer'); ?></p>
     <p>
      <?php tr('hazards', 'In der Nähe der Gemeinde {{ info.nearest_city.name }} ist im Zeitraum von {{currEpoch}} mit einer {{roundedRs.tendency}} {{roundedRs.min}} bis  {{roundedRs.max}} % an Sommerniederschlag zu rechnen'); ?>
	  <?php tr('hazards', '(Vorhersage nach dem Szenario {{currSzenario}}).'); ?>
  	  <?php tr('hazards', 'Der Referenzwert für den Zeitraum 1971-2000 beträgt für die nächstgelegene Wetterstation {{nearestStation.name}} {{nearestStation.rr_summer}} mm für die Sommermonate.'); ?>
  	  <?php tr('hazards', 'Für die Referenz-Wetterstation {{bestStation.name}} in ähnlicher Höhenlage ({{ info.landscape.name }}) beträgt er {{bestStation.rr_summer}} mm für die Sommermonate.'); ?>

	</p>
    </template>

	
	</div> <!-- end #climate_info -->



    </div> <!-- end div glass -->

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
	 



