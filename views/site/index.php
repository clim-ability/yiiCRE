<?php
use app\modules\libraries\bundles\MapAsset;
use yii\helpers\Url;
$assets = MapAsset::register($this);

header('Access-Control-Allow-Origin: *');
?>

<script>
    var mapBaseUrl = "<?php echo $assets->baseUrl; ?>";
	var apiBaseUrl = "<?php echo Url::home('https'); ?>";
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="climateinspector">

      <h1 class="my-4">Changing of climate
        <small>at the Upper Rhine</small>
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
     Nearest City: {{ info.nearest_city.name }}, Elevation: {{ roundedElevation }} m <br/>
	 <br/>
	 <!-- Dry Days: <span v-html="roundedCddp"></span> days/year <br/> -->
	 <span :class="('fd' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('fd')">Frost Days: </span>
	  <span v-html="roundedFd"></span> days/year <br/>
	 <span :class="('sd' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('sd')">Summer Days: </span>
	   <span v-html="roundedSd"></span> days/year <br/>
	 <span :class="('tr' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('tr')">Tropical Nights: </span>
	   <span v-html="roundedTr"></span> days/year <br/>
	 <span :class="('rr20' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr20')">Torrential Rain: </span>
	   <span v-html="roundedRr20"></span> days/year <br/>
	 <span :class="('rr_winter' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr_winter')">Winter Rain: </span>
	   <span v-html="roundedRw"></span> % <br/>
	 <span :class="('rr_summer' == currHazard)? 'active':'inactive'" v-on:click="switchHazard('rr_summer')">Summer Rain: </span>
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
	 



