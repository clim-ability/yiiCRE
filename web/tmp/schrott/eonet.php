<?php
use app\modules\libraries\bundles\EonetAsset;
$assets = EonetAsset::register($this);

header('Access-Control-Allow-Origin: *');
?>

<script>
    var eonetBaseUrl = "<?php echo $assets->baseUrl; ?>";
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

 <div class="container-fluid">
  <div class="row" style="margin: 8px;">
   <div class="col-md-6 "> <!-- eoEvents Upper-Left Corner -->
    <!-- Left and right controls -->
    <ul class = "pager" style="text-align:left;">
     <span style="font-size:175%;">
	    <a href="http://eonet.sci.gsfc.nasa.gov/">E<img src="<?php echo $assets->baseUrl; ?>/images/nasa.ico" height="24px" alt="O" style="position:relative; top:-2px;"/>Net</a> 
	 </span>
     <li class = "next"><a class="no-wait" href = "#myCarousel" data-slide = "next">Older &rarr;</a></li>
     <li class = "next"><a class="no-wait" href = "#myCarousel" data-slide = "prev">&larr; Newer</a></li>   
    </ul>

    <div id="myCarousel" class="carousel slide" data-interval="false">
     <!-- Wrapper for slides -->
     <div class="carousel-inner eo-events" role="listbox"></div>
    </div>
   </div> <!-- end md-6 -->

   <div class="col-md-6"> <!-- Tabbed Upper-Right Corner -->
    <ul class="nav nav-tabs">
     <li class="active"><a data-toggle="tab" href="#globe">Globe</a></li>
     <li><a data-toggle="tab" href="#mapEvents">Map Events</a></li>	
     <li><a data-toggle="tab" href="#mapSatelite">Map Satellite</a></li>		 
     <li><a data-toggle="tab" href="#about">About</a></li>
    </ul>
    <div id='tab-content' class="tab-content">
      <div id="globe" class="tab-pane fade in active">
      <div id='rendering' class='urTabContent' ></div>
    </div>
    <div id="mapEvents" class="tab-pane fade">
      <div id="olMapEvents" class="olMapEvents urTabContent" ></div>
    </div>
    <div id="mapSatelite" class="tab-pane fade">
      <div id="olMapSatelite" class="olMapSatelite urTabContent"></div>
      <div id="olControlDate" class="ol-unselectable ol-control">
	<span class="currentDay small">yyyy-mm-dd</span><br/>
	<button class="minusDay" type="button" title="Previous Day"><span class="glyphicon glyphicon-chevron-left"></span></button>
	<button class="resetDay" type="button" title="Reset Day" ><span class="glyphicon glyphicon-time"></span></button>
	<button class="plusDay" type="button" title="Next Day" ><span class="glyphicon glyphicon-chevron-right"></span></button>
      </div>
    </div>	 
    <div id="about" class="tab-pane fade">
      <div class='urTabContent'>
         <div class="row">
	   <br/>
           <div class="col-md-7 col-md-offset-1">
             <img src="<?php echo $assets->baseUrl; ?>/images/nasa-logo.png" height="64px" alt="Nasa Logo"  />
             <span class="glyphicon glyphicon-plus" style="font-size:32px;position:relative;top:10px"></span>
             <img src="<?php echo $assets->baseUrl; ?>/images/tambora-logo.png" height="64px" alt="Tambora Logo"  />
           </div>
		   <div class="col-md-4">
			 <p><br/><span class="small">©2016 (<a href="http://www.apache.org/licenses/LICENSE-2.0">Apache 2.0</a>)</span>
			 <br/>Michael Kahle</p>
           </div>
         </div>
         <div class="row">
           <div class="col-md-12 ">
<p class="small">What happened the last weeks and did this happened centuries(!) ago as well?<p>

<p class="small">Nasa's eoNet near real time data derived from high technology measurement and satellite imagery is combined with tambora.org's historical data extracted from diaries, chronicles, newspapers and other documents from centuries ago.
<br/>Satellite images for present events can be selected and stepped through time.
<br/>It visualizes the spatial and temporal distribution of hazard events today as well as corresponding events in the past and thereby allows to compare those in quantity and intensity. 
This allows anybody to examine the impact of climate change on a global and local level.</p> 
	
          </div>
         </div>
         <div class="row">
           <div class="col-sm-4 ">
             <p class="small">Used APIs</p>
			 <ul class="small">
			  <li><a href="http://eonet.sci.gsfc.nasa.gov/">eoNet from Nasa</a></li>
			  <li><a href="https://www.tambora.org/index.php/site/page?view=about">Tambora from University Freiburg</a></li>
			 </ul>
           </div>
           <div class="col-sm-4 ">
             <p class="small">Used JS Libraries</p>
			 <ul class="small">
			  <li><a href="https://jquery.com/">jquery</a>, <a href="http://getbootstrap.com/">bootstrap</a></li>
			  <li><a href="http://openlayers.org/">openlayers3</a>, <a href="https://github.com/walkermatt/ol3-layerswitcher">ol3-layerswitcher
</a></li>			  
			  <li><a href="https://www.dynatable.com/">dynatable</a>, <a href="https://d3js.org/">d3</a>, <a href="https://dc-js.github.io/dc.js/">dc</a>, <a href="http://square.github.io/crossfilter/">crossfilter</a></li>
			  <li><a href="http://threejs.org/">three</a>, <a href="https://github.com/tsironis/lockr">lockr</a></li>
			 </ul>
           </div>
           <div class="col-sm-4 ">
             <p class="small">Free Images</p>
			 <ul class="small">
			  <li><a href="https://pixabay.com/">pixabay</a></li>
			 </ul>
           </div>

         </div>
      </div>
    </div>

   </div> <!-- end md-6 -->
  </div> <!-- end row -->

	
  <div class="row">
   <div class="col-md-12 "><!-- for better alignment -->
     <span id="tmbLogo" style="font-size:175%">
      <a href="https://www.tambora.org">Tamb<img src="<?php echo $assets->baseUrl; ?>/images/tambora.ico" height="24px" alt="O" />ra</a>
      <a class="tmbSearch" href="https://www.tambora.org"><span class="glyphicon glyphicon-search"></span></a> 
     </span>  
  </div>
  </div> <!-- end UPPER row -->
				
  <div class="row">
   <div class="col-md-6 "> <!-- table Lower-Right Corner-->

    <table id="tmb-data-table" class="table table-bordered small">
       <thead>
         <tr>
           <th data-dynatable-column="start">Date</th>
	        <th data-dynatable-column="days">Duration (Days)</th>
	        <th data-dynatable-column="distance">Distance</th>
	        <th data-dynatable-column="location">Location</th>
	        <th data-dynatable-column="index">Indices</th>	   
	        <th data-dynatable-column="id">Show</th>
         </tr>
       </thead>
    </table>	
	
	
   </div>
  
   <div class="col-md-6 tmbCharts"><!-- charts Lower-Left Corner -->

    <div class="row">
     <div class="col-md-6 ">
      <div id="year-chart">
        <strong>Year</strong>
        <span class="reset" style="display: none;">range: <span class="filter"></span></span>
        <a class="reset no-wait" href="javascript:yearChart.filterAll();dc.redrawAll();" style="display: none;">Reset</a>
        <div class="clearfix"></div>
      </div>
     </div>	
     <div class="col-md-3 ">	
      <div id="month-chart">
        <strong>Month</strong>
        <a class="reset no-wait" href="javascript:monthChart.filterAll();dc.redrawAll();" style="display: none;">Reset</a>
        <div class="clearfix"></div>
      </div>	
     </div>
     <div class="col-md-3 ">	
      <div id="index-chart">
        <strong>Indices</strong>
        <a class="reset no-wait" href="javascript:indexChart.filterAll();dc.redrawAll();" style="display: none;">reset</a>
        <div class="clearfix"></div>
      </div>	
     </div>
    </div>

    <div class="row"><div class="col-md-12"><p>&nbsp;</p></div></div>
     
    <div class="row">
     <div class="col-md-6 ">	
      <div id="dist-chart">
        <strong>Distance (km)</strong>
        <span class="reset" style="display: none;">range: <span class="filter"></span></span>
        <a class="reset no-wait" href="javascript:distChart.filterAll();dc.redrawAll();" style="display: none;">Reset</a>
        <div class="clearfix"></div>
      </div>
     </div>		
     <div class="col-md-6 ">
      <div id="days-chart">
        <strong>Duration (Days)</strong>
        <span class="reset" style="display: none;">range: <span class="filter"></span></span>
        <a class="reset no-wait" href="javascript:daysChart.filterAll();dc.redrawAll();" style="display: none;">Reset</a>
        <div class="clearfix"></div>
      </div>
     </div>
    </div>	
   </div>
  </div> <!-- end LOWER row -->	

 </div> <!-- end container-fluid -->
	 



