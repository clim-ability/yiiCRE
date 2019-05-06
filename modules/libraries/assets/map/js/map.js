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
//L.imageOverlay(imageUrl, imageBounds).addTo(map);


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


{
// Create Leaflet Control Object for Legend
  var legend = L.control({position: 'bottomright'});
  legend.onAdd = function (map) {return L.DomUtil.create('div', 'legend')};
  legend.addTo(map);
  function updateLegend(epoch, szenario, parameters) {
    map.removeControl(legend);	
    // Function that runs when legend is added to map
    legend.onAdd = function (map) {
	// Create Div Element and Populate it with HTML
	  var div = L.DomUtil.create('div', 'legend');		    
	  div.innerHTML += '<b>Climate Indicators</b><br />';
	  div.innerHTML += epoch + '; ' + szenario + '<br />';
	  div.innerHTML += '<small></small><br />';  
      for (var i = 0; i < parameters.length; i++) {
        div.innerHTML += '<i style="background: '+parameters[i].color_max+'"></i><p>'+parameters[i].label+'</p>';
      }
	  // Return the Legend div containing the HTML content
	  return div;
    };
    legend.addTo(map);
  }
}

var geojsonLayerWells = new L.GeoJSON();
map.addLayer(geojsonLayerWells);
geojsonLayerWells.on('click', onMapClick);

function interpolateColor(a, b, amount) { 
    var ah = parseInt(a.replace('#', '0x'), 16),
        ar = ah >> 16, ag = ah >> 8 & 0xff, ab = ah & 0xff,
        bh = parseInt(b.replace('#', '0x'), 16),
        br = bh >> 16, bg = bh >> 8 & 0xff, bb = bh & 0xff,
        rr = ar + amount * (br - ar),
        rg = ag + amount * (bg - ag),
        rb = ab + amount * (bb - ab);
    return '#' + ((1 << 24) + (rr << 16) + (rg << 8) + rb | 0).toString(16).slice(1);
}



//$hazard='cddp', $epoch='2041-2070', $scenario='rcp45'

{
	
  var colorParameters = {
	minValueLocal: -1000.0,
	maxValueLocal: +1000.0,
    minValueGlobal: -1000.0,
	maxValueGlobal: +1000.0,
	minOpacity: 0.2,
	maxOpacity: 0.8,
	minColor: '#000000',
	maxColor: '#000000'
  }	  

  function setExtremeValuesLocal(vmin, vmax)
  {
    colorParameters.minValueLocal = vmin;
	colorParameters.maxValueLocal = vmax;
  }	 
	
  function setExtremeValuesGlobal(vmin, vmax)
  {
    colorParameters.minValueGlobal = vmin;
	colorParameters.maxValueGlobal = vmax;
  }	  

  function setExtremeColors(cmin, cmax)
  {
    colorParameters.minColor = cmin;
    colorParameters.maxColor = cmax;	  
  }	
	
  function setStyleColor(d) {
	var amount = (parseFloat(d) - colorParameters.minValueGlobal) / (colorParameters.maxValueGlobal - colorParameters.minValueGlobal);
	amount = Math.min(1.0, Math.min(1.0, amount)); 
    var color = interpolateColor(colorParameters.minColor, colorParameters.maxColor, amount);
	return color;
  }

  function setStyleOpacity(d) {
	var amount = (parseFloat(d) - colorParameters.minValueLocal) / (colorParameters.maxValueLocal - colorParameters.minValueLocal);
	amount = Math.min(1.0, Math.min(1.0, amount)); 
    var opacity = amount*(colorParameters.maxOpacity - colorParameters.minOpacity) + colorParameters.minOpacity;
	return opacity;
  }
		
  function LayerStyle(feature) {
    return {
    fillColor: setStyleColor(feature.properties.value),
    weight: 0.9,
    opacity: 0.8,
    color: 'black',
    dashArray: '0',
    fillOpacity: setStyleOpacity(feature.properties.value) 
	};
  }		

  function loadGeoJson(data) {
    //console.log(data);
	geojsonLayerWells.clearLayers();
    geojsonLayerWells.addData(data);
	geojsonLayerWells.setStyle(LayerStyle);
	//map.removeLayer(geojsonLayerWells);
    //map.addLayer(geojsonLayerWells);
  };
	
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
        for (var i = 0; i < this.hazards.length; i++) {
          if (this.hazard == this.hazards[i].name) {
			setExtremeColors(this.hazards[i].color_min, this.hazards[i].color_max);  

		  }	
	    }
  		axios
          .get(apiBaseUrl+'/api/hazard-extremes?hazard='+this.hazard)
		  //.get(apiBaseUrl+'/api/hazard-extremes?hazard='+this.hazard+'&epoch='+this.epoch+'&scenario='+this.scenario)
          .then(response => { 
		    setExtremeValuesGlobal(response.data[0].min, response.data[0].max);
	  	});	
  		axios
          //.get(apiBaseUrl+'/api/hazard-extremes?hazard='+this.hazard)
		  .get(apiBaseUrl+'/api/hazard-extremes?hazard='+this.hazard+'&epoch='+this.epoch+'&scenario='+this.scenario)
          .then(response => { 
		    setExtremeValuesLocal(response.data[0].min, response.data[0].max);
	  	});		
		updateLegend(this.epoch, this.scenario, this.hazards);
		//window.viewInfo.clickOnMap();
		vueEventBus.$emit('updatedParameters', this);
        setParametersOnMap(this.hazard, this.epoch, this.scenario);		
	  }
	},
	getCurrentEpoch() {return this.epoch; },
	getCurrentSzenario() {return this.scenario; },
  },
  mounted () {
    axios
      .get(apiBaseUrl+'/api/hazards')
	  //.get('https://gis.clim-ability.eu/index.php/api/hazards')
      .then(response => { 
	    this.hazards = response.data; 
		this.hazard = this.hazards[0].name;
		this.updateParameters();
		updateLegend(this.epoch, this.scenario, this.hazards);
		});
    axios
      .get(apiBaseUrl+'/api/epochs')
	  //.get('https://gis.clim-ability.eu/index.php/api/epochs')
      .then(response => { this.epochs = response.data; 
		this.epoch = this.epochs[0].name;
		this.updateParameters();
		 });
    axios
      .get(apiBaseUrl+'/api/scenarios')
	  //.get('https://gis.clim-ability.eu/index.php/api/scenarios')
      .then(response => { this.scenarios = response.data; 
		this.scenario = this.scenarios[0].name;
		this.updateParameters();
		 });
    axios
      .get(apiBaseUrl+'/api/languages')
	  //.get('https://gis.clim-ability.eu/index.php/api/languages')
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
		if (latitude !== 0 && longitude !== 0) {
		  var epoch = vueSelect.getCurrentEpoch();
		  var szenario = vueSelect.getCurrentSzenario();
	      var url = apiBaseUrl+'/api/hazard-values';
	         // url = 'https://gis.clim-ability.eu/index.php/api/hazard-values';
	      url = url + '?latitude='+latitude+'&longitude='+longitude+'&epoch='+epoch+'&scenario='+szenario;
          axios.get(url).then(response => {
	         this.info = response.data; 
	         this.infoVisible = true;
	      });
	    }
	},
	roundedValue(value, digits) {
	   var pot = Math.pow(10.0, digits);
	   return Math.round(pot*parseFloat(value))/pot;
	},
	roundedRange(para, digits) {
	  if(para) {	
	   
		var minus = this.roundedValue(parseFloat(para.value) - parseFloat(para.std), digits);
		var plus = this.roundedValue(parseFloat(para.value) + parseFloat(para.std), digits);
		if(parseFloat(para.value) < 0.0) {
		   return 'Decrease by <b>'+(0.0-plus)+' - '+(0.0-minus)+'</b>';		   
		} else {
           return 'Increase by <b>'+minus+' bis '+plus+'</b>';
		}
	  }
	  return '';
	}
  },
  computed: {
	roundedElevation: function() {
      return this.roundedValue(this.info.elevation_calculated.value, 2);
	},		
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