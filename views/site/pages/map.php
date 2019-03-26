<?php
use app\modules\libraries\bundles\MapAsset;
$assets = MapAsset::register($this);

header('Access-Control-Allow-Origin: *');
?>

<script>
    var mapBaseUrl = "<?php echo $assets->baseUrl; ?>";
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

      <h1 class="my-4">Changing of climate
        <small>at the Upper Rhine</small>
      </h1>

 <div class="container-fluid">
  <div class="row" style="margin: 8px;">
   <div class="col-md-6 "> <!-- eoEvents Upper-Left Corner -->

    <div id="map"></div>
    <div id="controls">
     <button id="removeIndicators">hide indicators</button>
     <button id="addTropical">tropical nights</button>
     <button id="addRainfall">annual rainfall</button>
     <button id="addFrostdays">frost days</button>
     <button id="addDrydays">dry days</button>
     <button id="addSummerRain">summer rain</button>
     <button id="addWinterRain">winter rain</button>
    </div>

   </div> <!-- end md-6 -->

   <div class="col-md-6"> <!-- Tabbed Upper-Right Corner -->
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
	 



