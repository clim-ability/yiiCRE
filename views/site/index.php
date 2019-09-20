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
      <h1 class="my-4"><?php tr('main', 'Changing of climate'); ?>
        <small><?php tr('main', 'at the Upper Rhine'); ?></small>
      </h1>

 <div class="container-fluid">

    <div class="row" id="selectionrow">
 	  <div class="col-md-3">
       <select v-model="hazard" v-on:change="updateParameters" class="form-control">
        <option v-for="hazard in hazards" v-bind:value="hazard.name">
         {{ hazard.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-3">
       <select v-model="epoch" v-on:change="updateParameters" class="form-control">
        <option v-for="epoch in epochs" v-bind:value="epoch.name">
         {{ epoch.label }} 
        </option>
       </select>
	  </div>	
 	  <div class="col-md-3">
       <select v-model="scenario" v-on:change="updateParameters" class="form-control">
        <option v-for="scenario in scenarios" v-bind:value="scenario.name">
         {{ scenario.label }} 
        </option>
       </select>
	  </div>		 
	  <div class="col-md-3 ">	   
	   <select v-model="language" class="form-control">
        <option v-for="language in languages" v-bind:value="language.name">
         {{ language.label }}
        </option>
       </select>
	  </div>
	</div> 

 
  <div class="row" style="margin: 8px;">
   <div class="col-md-6 "> <!-- eoEvents Upper-Left Corner -->




   <div id="map"></div>

   </div> <!-- end md-6 -->

   <div class="col-md-6" id="informationfield"> <!-- Tabbed Upper-Right Corner -->
    <template v-if="info !== 'none'">
     <?php tr('main', 'Nearest City'); ?>: {{ info.nearest_city.name }}, <?php tr('main', 'Elevation'); ?>: {{ roundedElevation }} m <br/>
	 <br/>
	 <!-- Dry Days: <span v-html="roundedCddp"></span> days/year <br/> -->
	 <span :class="('fd' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('fd')" title='<?php \Yii::t('Hazard:description', 'fd', []); ?>'>
	   <?php tr('Hazard:Name', 'fd'); ?>: </span>
	  <span v-html="roundedFd"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('sd' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('sd')"><?php tr('Hazard:Name', 'sd'); ?>: </span>
	   <span v-html="roundedSd"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('tr' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('tr')"><?php tr('Hazard:Name', 'tr'); ?>: </span>
	   <span v-html="roundedTr"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('rr20' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr20')"><?php tr('Hazard:Name', 'rr20'); ?>: </span>
	   <span v-html="roundedRr20"></span> <?php tr('hazards', 'days/year'); ?> <br/>
	 <span :class="('rr_winter' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr_winter')"><?php tr('Hazard:Name', 'rr_winter'); ?>: </span>
	   <span v-html="roundedRw"></span> % <br/>
	 <span :class="('rr_summer' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr_summer')"><?php tr('Hazard:Name', 'rr_summer'); ?>: </span>
	   <span v-html="roundedRs"></span> % <br/>	 
	 <br/>

    </template>	
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
	 



