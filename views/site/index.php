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
	<p>{{nearestStation.name}}</p>
	 <br/>
     <?php tr('main', 'Nearest City'); ?>: {{ info.nearest_city.name }}, <?php tr('main', 'Elevation'); ?>: {{ roundedElevation }} m <br/>
	 <br/>
	 <!-- Dry Days: <span v-html="roundedCddp"></span> days/year <br/> -->
	 <span :class="('fd' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('fd')" title='<?php echo \Yii::t('Hazard:description', 'fd', []); ?>' >
	   <?php tr('Hazard:Name', 'fd'); ?>: </span>
	  <span v-html="roundedFd"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('sd' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('sd')" title='<?php echo \Yii::t('Hazard:description', 'sd', []); ?>' >
	 <?php tr('Hazard:Name', 'sd'); ?>: </span>
	   <span v-html="roundedSd"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('tr' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('tr')" title='<?php echo \Yii::t('Hazard:description', 'tr', []); ?>' >
	 <?php tr('Hazard:Name', 'tr'); ?>: </span>
	   <span v-html="roundedTr"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('rr20' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr20')" title='<?php echo \Yii::t('Hazard:description', 'rr20', []); ?>' >
	 <?php tr('Hazard:Name', 'rr20'); ?>: </span>
	   <span v-html="roundedRr20"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('rr_winter' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr_winter')" title='<?php echo \Yii::t('Hazard:description', 'rr_winter', []); ?>' >
	 <?php tr('Hazard:Name', 'rr_winter'); ?>: </span>
	   <span v-html="roundedRw"></span> % <br/>
	 <span :class="('rr_summer' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr_summer')" title='<?php echo \Yii::t('Hazard:description', 'rr_summer', []); ?>' >
	 <?php tr('Hazard:Name', 'rr_summer'); ?>: </span>
	   <span v-html="roundedRs"></span> % <br/>	 
	 <br/>
	 <p>
      <?php tr('hazard', 'Alle Änderungen beziehen sich auf den Bezugszeitraum von 1971-2000.'); ?> 
     </p>	
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
	 



