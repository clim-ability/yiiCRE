<?php
use app\modules\libraries\bundles\MapAsset;
use yii\helpers\Url;
use app\modules\translation\widgets\LanguageTranslate;
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

    <div class="row">
 	  <div class="col-md-4">
         <?php tr('hazard', 'Klima-Parameter'); ?>
	  </div>	
 	  <div class="col-md-4">
         <?php tr('hazard', 'Zeithorizont'); ?>
	  </div>	
 	  <div class="col-md-4">
         <?php tr('hazard', 'Szenario'); ?>
	  </div>		 
	</div> 

    <div class="row" id="selectionrow">
 	  <div class="col-md-4">
       <select v-model="hazard" v-on:change="updateParameters" class="form-control">
        <option v-for="hazard in hazards" v-bind:value="hazard.name">
         {{ hazard.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-4">
       <select v-model="epoch" v-on:change="updateParameters" class="form-control">
        <option v-for="epoch in epochs" v-bind:value="epoch.name">
         {{ epoch.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-4">
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
	<p><?php tr('hazard', 'To start choose your location by {clicking on the map} and/or change parameters.', 
	    ['clicking on the map' => '<strong>'.yii::t('hazard', 'clicking on the map').'</strong>']); ?> 
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

    </template>
	

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
	 



