//var mapBaseUrl = '.';


var statisticOptions = {
	recordsField: null,
	locationMode: L.LocationModes.LATLNG,
	//codeField: 'state',
	latitudeField: 'latitude',
	longitudeField: 'longitude',
	chartOptions: {
		'estimates[choice=Romney].value': {
			color: 'hsl(0,100%,25%)',
			fillColor: 'hsl(0,70%,60%)',
			maxValue: 1,
			maxHeight: 20,
			displayName: 'Romney',
			displayText: function (value) {
				return value.toFixed(2);
			}
		},
		'estimates[choice=Obama].value': {
			color: 'hsl(240,100%,25%)',
			fillColor: 'hsl(240,70%,60%)',
			maxValue: 1,
			maxHeight: 20,
			displayName: 'Obama',
			displayText: function (value) {
				return value.toFixed(2);
			}
		},
		'estimates[choice=Other].value': {
			color: 'hsl(240,5%,75%)',
			fillColor: 'hsl(240,5%,75%)',
			maxValue: 1,
			maxHeight: 20,
			displayName: 'Other',
			displayText: function (value) {
				return value.toFixed(2);
			}
		}
	},
	layerOptions: {
		fillOpacity: 0.9,
		opacity: 1,
		weight: 0.5,
		radius: 10,
		width: 5,
		barThickness: 5
	},
	// Use displayOptions to dynamically size the radius and barThickness according to the number of
	// polling results
	displayOptions: {
		'poll_count': {
			radius: new L.LinearFunction(new L.Point(0, 10), new L.Point(1000, 100)),
			barThickness: new L.LinearFunction(new L.Point(0, 4), new L.Point(1000, 80))
		}
	}
	/* ,
	tooltipOptions: {
		iconSize: new L.Point(80,55),
		iconAnchor: new L.Point(-5,55)
	},
	onEachRecord: function (layer,record) {
		var $html = $(L.HTMLUtils.buildTable(record));
			layer.bindPopup($html.wrap('<div/>').parent().html(),{
			minWidth: 400,
			maxWidth: 400
		});
	} */
};



    // initialize the map
  var map = L.map('map').setView([48.25, 8], 8);

  // load a tile layer
  var OpenStreetMap_DE = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
  maxZoom: 12,
  minZoom: 8,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

// create image layer indicator pies
var imageUrl = mapBaseUrl + '/images/pie_indicator.png',
    imageBounds = [[49.772, 6.66], [46.66, 9.12]];
L.imageOverlay(imageUrl, imageBounds).addTo(map);


// add statistic here
//var statisticLayer = new L.PieChartDataLayer(data,statisticOptions);
//map.addLayer(statisticLayer);

/*
 var tmo_region = L.tileLayer.wms("http://climability.uni-landau.de/cgi-bin/qgis_mapserv.fcgi?map=/var/www/html/climability/cgi-bin/climability_TMO.qgs", {
    layers: 'tmo_region',
    format: 'image/png',
    transparent: true,
    attribution: "climability"
  }).addTo(map);
*/

//locate by click
var popup = L.popup();
function onMapClickOld(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("I am here: " + e.latlng.toString())
        .openOn(map);
}
{

var marker = new L.marker([0,0], {id:'uni', draggable:'true'});
function getCurrentLatitude() { return marker.getLatLng().lat; }
function getCurrentLongitude() { return marker.getLatLng().lng; }

function onMapClick(e) {
	//var latlong = e.latlng.split(",");
	map.removeLayer(marker);
	marker = new L.marker(e.latlng, {id:'uni', draggable:'true'});
    marker.on('dragend', function(event){
            var marker = event.target;
            var position = marker.getLatLng();
	        var latitude = position.lat;
	        var longitude = position.lng;
	        window.vueInfo.clickOnMap(latitude, longitude);
            marker.setLatLng(position,{id:'uni',draggable:'true'}).bindPopup(position).update();
    });
    map.addLayer(marker);
	
	var latitude = e.latlng.lat;
	var longitude = e.latlng.lng;
	window.vueInfo.clickOnMap(latitude, longitude);
    // set marker
}
map.on('click', onMapClick);
}

// Create Leaflet Control Object for Legend
var legend = L.control({position: 'bottomright'});

// Function that runs when legend is added to map
legend.onAdd = function (map) {

	// Create Div Element and Populate it with HTML
	var div = L.DomUtil.create('div', 'legend');		    
			div.innerHTML += '<b>Climate Indicators</b><br />';
			div.innerHTML += 'near future<br />';
			div.innerHTML += '<small></small><br />';  
			div.innerHTML += '<i style="background: #FF0000"></i><p>tropical nights</p>';
			div.innerHTML += '<i style="background: #CC6600"></i><p>dry days</p>';
			div.innerHTML += '<i style="background: #33FFFF"></i><p>annual rainfall</p>';
			div.innerHTML += '<i style="background: #33FF66"></i><p>rain summer</p>';
			div.innerHTML += '<i style="background: #9999CC"></i><p>rain winter</p>';
      div.innerHTML += '<i style="background: #3333FF"></i><p>frost days</p>';

	// Return the Legend div containing the HTML content
	return div;
};

legend.addTo(map);

var geojsonLayerWells = new L.GeoJSON();
map.addLayer(geojsonLayerWells);
geojsonLayerWells.on('click', onMapClick);

function setStyleColor(d) {
        return d > 20  ? '#FF9988'  :
               d > 15  ? '#EEAA88' :
			   d > 15  ? '#DDBB88' :
               d > 5   ? '#BBCC88'  :
			   d > 0   ? '#AADD88'  : 
               d > -5  ? '#99EE88' :
                         '#88FF88' ;
        }
		
function LayerStyle(feature) {
  return {
  fillColor: setStyleColor(feature.properties.value),
  weight: 0.5,
  opacity: 0.7,
  color: 'black',
  dashArray: '0',
  fillOpacity: 0.5 };
}		

function loadGeoJson(data) {
    //console.log(data);
	geojsonLayerWells.clearLayers();
    geojsonLayerWells.addData(data);
	geojsonLayerWells.setStyle(LayerStyle);
	//map.removeLayer(geojsonLayerWells);
    //map.addLayer(geojsonLayerWells);
};

//$hazard='cddp', $epoch='2041-2070', $scenario='rcp45'

{
    var defaultParameters = {
        hazard: 'cddp',
        epoch: '2041-2070',
        scenario: 'rcp45',
        };
		
    var customParams = {
        bbox: map.getBounds().toBBoxString(),
        };		
		
    function setParametersOnMap(hazard, epoch, scenario) {
      defaultParameters = {
        hazard: hazard,
        epoch: epoch,
        scenario: scenario,
        };
	  redrawParameters();
    }	

	function redrawParameters() {
      var geoJsonUrl ='https://gis.clim-ability.eu/index.php/api/hazard-geom'; 
      var parameters = L.Util.extend(defaultParameters, customParams);
      $.ajax({
        url: geoJsonUrl + L.Util.getParamString(parameters),
        datatype: 'json',
        jsonCallback: 'getJson',
        success: loadGeoJson
        });
	}
	
map.on('moveend', function(){
 if(map.getZoom() > -200){
    customParams = {
        bbox: map.getBounds().toBBoxString(),
        };
  
    //console.log(geoJsonUrl + L.Util.getParamString(parameters));
    redrawParameters();

 }else{
    map.removeLayer(geojsonLayerWells);
    };
});

}

redrawParameters();
/*

// Null variable that will hold our data
  var clim_ex = null;


// load GeoJSON from an external file
  $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
    // set clim_ex to GeoJSON, add GeoJSON layer to the map once the file is loaded
    clim_ex = L.geoJson(data, {color:'black', weight:1, opacity:0.1
    },{
        onEachFeature: function (feature, layer) {
            layer.bindPopup(feature.properties.cat_trop);
      }
    }).addTo(map);
});

// Use $( "elementID") and the jQuery click listener method to remove all of the data layers
$( "#removeIndicators" ).click(function() {
    map.removeLayer(clim_ex);
});

// Use $( "elementID") and the jQuery click listener method to create a filter
$( "#addTropical" ).click(function() {
    map.removeLayer(clim_ex);
    $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
        // add GeoJSON layer to the map once the file is loaded
      clim_ex = L.geoJson(data,{ 
        style: function(feature){
        var fillColor,
          cat_trop = feature.properties.cat_trop;
        if ( cat_trop = "high" ) fillColor = "#FF0000";
        else fillColor = "#fee5d9";  // no data
        return { color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6 };
      },
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.cat_trop);
            }, filter: function (feature, layer) {
                return feature.properties.cat_trop == "high";
            }
        }).addTo(map);
    });
});

$( "#addRainfall" ).click(function() {
    map.removeLayer(clim_ex);
    $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
        // add GeoJSON layer to the map once the file is loaded
        clim_ex = L.geoJson(data,{
          style: function(feature){
        var fillColor,
          cat_rainf = feature.properties.cat_rainf;
        if ( cat_rainf = "high" ) fillColor = "#33FFFF";
        else fillColor = "#fee5d9";  // no data
        return { color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6 };
      },
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.cat_rainf);
            }, filter: function (feature, layer) {
                return feature.properties.cat_rainf == "high";
            }
        }).addTo(map);
    });
});

$( "#addFrostdays" ).click(function() {
    map.removeLayer(clim_ex);
    $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
        // add GeoJSON layer to the map once the file is loaded
        clim_ex = L.geoJson(data,{
          style: function(feature){
        var fillColor,
          cat_frost = feature.properties.cat_frost;
        if ( cat_frost = "high" ) fillColor = "#3333FF";
        else fillColor = "#fee5d9";  // no data
        return { color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6 };
      },
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.cat_frost);
            }, filter: function (feature, layer) {
                return feature.properties.cat_frost == "high";
            }
        }).addTo(map);
    });
});

$( "#addDrydays" ).click(function() {
    map.removeLayer(clim_ex);
    $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
        // add GeoJSON layer to the map once the file is loaded
        clim_ex = L.geoJson(data,{
          style: function(feature){
        var fillColor,
          cat_dryd = feature.properties.cat_dryd;
        if ( cat_trop = "high" ) fillColor = "#CC6600";
        else fillColor = "#fee5d9";  // no data
        return { color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6 };
      },
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.cat_dryd);
            }, filter: function (feature, layer) {
                return feature.properties.cat_dryd == "high";
            }
        }).addTo(map);
    });
});

$( "#addSummerRain" ).click(function() {
    map.removeLayer(clim_ex);
    $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
        // add GeoJSON layer to the map once the file is loaded
        clim_ex = L.geoJson(data,{
          style: function(feature){
        var fillColor,
          cat_rain_s = feature.properties.cat_rain_s;
        if ( cat_rain_s = "high" ) fillColor = "#33FF66";
        else fillColor = "#fee5d9";  // no data
        return { color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6 };
      },
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.cat_rain_s);
            }, filter: function (feature, layer) {
                return feature.properties.cat_rain_s == "high";
            }
        }).addTo(map);
    });
});

$( "#addWinterRain" ).click(function() {
    map.removeLayer(clim_ex);
    $.getJSON("./layer/clim_ex_wgs84.geojson",function(data){
        // add GeoJSON layer to the map once the file is loaded
        clim_ex = L.geoJson(data,{
          style: function(feature){
        var fillColor,
          cat_rain_w = feature.properties.cat_rain_w;
        if ( cat_rain_w = "high" ) fillColor = "#9999CC";
        else fillColor = "#fee5d9";  // no data
        return { color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6 };
      },
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.cat_rain_w);
            }, filter: function (feature, layer) {
                return feature.properties.cat_rain_w == "high";
            }
        }).addTo(map);
    });
});

*/



  var baseMaps = {};    
   L.control.layers(baseMaps,{
	   '<strong>openstreetmap<strong/><br />':OpenStreetMap_DE, 
    /*  '<strong>upper Rhine region<strong/><br />': tmo_region,  */
	  '<strong>climate indicators<strong/><br />':imageUrl}).addTo(map); 

var vueEventBus = new Vue({ });
	  
var vueSelect = new Vue({
  el: '#selectionrow',
  data: {
    hazard: 'none',
    hazards: [ { label: '', name: 'none' } ],
    epoch: 'none',
    epochs: [ { label: '', name: 'none' } ],
    scenario: 'none',
    scenarios: [ { label: '', name: 'none' } ],
    language: 'none',
    languages: [ { label: '', name: 'none' } ]	
  },
  methods: {
    updateParameters() {
	  if(this.hazard !== 'none' && this.epoch !== 'none' && this.scenario !== 'none' ) {	 
        setParametersOnMap(this.hazard, this.epoch, this.scenario);
		//window.viewInfo.clickOnMap();
		vueEventBus.$emit('updatedParameters', this);
	  }
	},
	getCurrentEpoch() {return this.epoch; },
	getCurrentSzenario() {return this.scenario; },
  },
  mounted () {
    axios
      //.get('mapBaseUrl'+'/api/hazards')
	  .get('https://gis.clim-ability.eu/index.php/api/hazards')
      .then(response => { 
	    this.hazards = response.data; 
		this.hazard = this.hazards[0].name;
		this.updateParameters();
		});
    axios
      //.get('mapBaseUrl'+'/api/epochs')
	  .get('https://gis.clim-ability.eu/index.php/api/epochs')
      .then(response => { this.epochs = response.data; 
		this.epoch = this.epochs[0].name;
		this.updateParameters();
		 });
    axios
      //.get('mapBaseUrl'+'/api/scenarios')
	  .get('https://gis.clim-ability.eu/index.php/api/scenarios')
      .then(response => { this.scenarios = response.data; 
		this.scenario = this.scenarios[0].name;
		this.updateParameters();
		 });
    axios
      //.get('mapBaseUrl'+'/api/languages')
	  .get('https://gis.clim-ability.eu/index.php/api/languages')
      .then(response => { this.languages = response.data; 
		this.language = this.languages[0].name;
		 });	  
  }
})


var vueInfo = new Vue({
  el: '#informationfield',
  data: {
    info: 'none',
	infoVisible: false
  },
  methods: { 
    clickOnMap() {
		var latitude = getCurrentLatitude();
		var longitude = getCurrentLongitude();
		var epoch = vueSelect.getCurrentEpoch();
		var szenario = vueSelect.getCurrentSzenario();
	var url = 'mapBaseUrl'+'/api/hazard-values';
	    url = 'https://gis.clim-ability.eu/index.php/api/hazard-values';
	url = url + '?latitude='+latitude+'&longitude='+longitude+'&epoch='+epoch+'&scenario='+szenario;
    axios.get(url).then(response => ( this.info = response.data ));
	},
	roundedRange(para, digits) {
	  if(para) {	
	    var pot = Math.pow(10, digits);
	    var minus = Math.round(10.0*(parseFloat(para.value) - parseFloat(para.std)))/10.0;
	    var plus = Math.round(10.0*(parseFloat(para.value) + parseFloat(para.std)))/10.0;
        return ''+minus+' - '+plus;	
	  }
	  return '';
	}
  },
  computed: {
    roundedCddp: function () {
	  return this.roundedRange(this.info.cddp_delta_calculated, 1);
    },
    roundedFd: function () {
	  return this.roundedRange(this.info.fd_delta_calculated, 1);
    },	
	roundedRr20: function () {
	  return this.roundedRange(this.info.rr20_delta_calculated, 1);
    },
	roundedRs: function () {
	  return this.roundedRange(this.info.rr_summer_delta_calculated, 1);
    },
	roundedRw: function () {
	  return this.roundedRange(this.info.rr_winter_delta_calculated, 1);
    },
	roundedTr: function () {
	  return this.roundedRange(this.info.tr_delta_calculated, 1);
    },
  },
  mounted () {
    vueEventBus.$on('updatedParameters', e => { this.clickOnMap();})
  }
})