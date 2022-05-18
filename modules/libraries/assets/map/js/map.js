//var mapBaseUrl = '.';

addCategoryToTranslationPool('hazards');
addCategoryToTranslationPool('Hazard:name');
addCategoryToTranslationPool('Hazard:description');

//addCategoryToTranslationPool('scenarios');
//addCategoryToTranslationPool('Scenario:name');
//addCategoryToTranslationPool('Scenario:description');


    // initialize the map
  var map = L.map('map').setView([48.25, 8], 8);

  // load a tile layer
  var OpenStreetMap_DE = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
  maxZoom: 12,
  minZoom: 8,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);


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
	//mk// updateChartData();
    // set marker
}
map.on('click', onMapClick);
map.on('zoomend', function() { updateZoomLevel(); });
}

// Manual removing of hover event and binding of click event....
L.PieChartMarker2 = L.PieChartMarker.extend({
	_bindMouseEvents: function (layer, a, b) { 
	        layer.off('mouseover');
			layer.on({click: onMapClick});
	}
})
L.PieChartMarker = L.PieChartMarker2.extend({})



function roundedValue(value, digits) {
	   var pot = Math.pow(10.0, digits);
	   return Math.round(pot*parseFloat(value))/pot;
	}
{
// Create Leaflet Control Object for Legend
  var legend = L.control({position: 'bottomright'});
  legend.onAdd = function (map) {return L.DomUtil.create('div', 'legend')};
  legend.addTo(map);
  function updateLegend(hazard, epoch, szenario, parameters) {
   map.removeControl(legend);	
   // Function that runs when legend is added to map

    legend.onAdd = function (map) {
	// Create Div Element and Populate it with HTML
	 var climateParameters = tr('hazards', 'Climate Parameters');
	 var div = L.DomUtil.create('div', 'legend');	
	 if('off' !== hazard) {
	  div.innerHTML += '<b>'+climateParameters+'</b><br />';
	  div.innerHTML += epoch + '; ' + szenario + '<br />';
	  if ('all' == hazard) {
		//div.innerHTML += '<small></small><br />'; 
        for (var i = 0; i < parameters.length; i++) {
		  if ('all' != parameters[i].name) {	
            div.innerHTML += '<i style="background: '+parameters[i].color_min+'">-</i><i style="background: '+parameters[i].color_max+'">+</i><p><small>'+parameters[i].label+'</small></p>';
		  }
        }
	  } else {
		var hazardTranslation = tr('Hazard:name', hazard);
		var upto = tr('hazards', 'to');
		div.innerHTML += hazardTranslation + '<br />';
		var digits = (getDigitsGlobal()>1.2)?0:1;
		var percents = '';
		if (('rr_summer' == hazard) || ('rr_winter' == hazard)) {
		  percents = ' %';
		}
        for (var i = 0.0; i < 7.0; i++) {
		  var d1 = getValueGlobal(i/7.0);
		  var d2 = getValueGlobal((i+1)/7.0);
		  if(d1*d2<0.0) {
		  var label = ''+ roundedValue(d1,digits)+' '+upto+' '+roundedValue(0.0,digits)+percents;
		  var color = getStyleColor(d1);
          div.innerHTML += '<i style="background: '+color+'"></i><p><small>'+label+'</small></p>';
		  var label = ''+ roundedValue(0.0,digits)+' '+upto+' '+roundedValue(d2,digits)+percents;;
		  var color = getStyleColor(0.0);
          div.innerHTML += '<i style="background: '+color+'"></i><p><small>'+label+'</small></p>';		  
		  } else {
		  var label = ''+ roundedValue(d1,digits)+' '+upto+' '+roundedValue(d2,digits)+percents;;
		  var color = getStyleColor(d1);
          div.innerHTML += '<i style="background: '+color+'"></i><p><small>'+label+'</small></p>';
		  }
        }
       }
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

var geojsonLayerBorder = new L.GeoJSON();
map.addLayer(geojsonLayerBorder);
geojsonLayerBorder.on('click', onMapClick);

function LayerStyleBorder(feature) {
    return {
    //fillColor: getStyleColor(feature.properties.value),
    weight: 1.5,
    opacity: 1.0,
    color: 'black',
    dashArray: '0',
    fillOpacity: 0 
	};
  }		

function loadGeoJsonBorder(data) {
    //console.log(data);
	geojsonLayerBorder.clearLayers();
    geojsonLayerBorder.addData(data);
	geojsonLayerBorder.setStyle(LayerStyleBorder);
	geojsonLayerBorder.on('click', onMapClick);
	//map.removeLayer(geojsonLayerBorder);
    //map.addLayer(geojsonLayerBorder);
  };

function initBorders() {
    //var geoJsonUrl ='https://gis.clim-ability.eu/index.php/api/borders'; 
	var geoJsonUrl = apiBaseUrl+'/api/borders';
    //var parameters = L.Util.extend(defaultParameters, customParams);
    $.ajax({
          url: geoJsonUrl,  // + L.Util.getParamString(parameters),
          datatype: 'json',
          jsonCallback: 'getJson',
          success: loadGeoJsonBorder
    });
}
 
var geojsonMarkerOptions = {
	radius: 10,
	fillColor: "#ffFF00",
	color: "#000",
	weight: 1,
	opacity: 1,
	fillOpacity: 0.6
};

function getMarkerOptions(label) {
  var extraOptions = {icon: 'fa-number',
    markerColor: 'yellow',
	iconColor: 'black',
    shape: 'square',
    prefix: 'fa',
	number: label
  };
  return extraOptions;
}  

var pointLayer = new L.GeoJSON(null, {
	pointToLayer: function (feature, latlng) {
		return L.marker( latlng, {icon: L.ExtraMarkers.icon(getMarkerOptions(feature.properties.abbreviation))} );
	}
}).addTo(map).on('click', onMapClick);  
  
map.addLayer(pointLayer);

function initStationData() 
{

  var url = apiBaseUrl+'/api/stations-geojson';
  axios.get(url).then(response => {
	pointLayer.addData(response.data);
  });
 
	
}

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

{

/*
var c3Data = {
      columns: [
        ['rcp45', 30, 200, 100, 400, 150, 250],
        ['rcp85', 50, 20, 10, 40, 15, 25]
      ],
    };

var c3Chart = c3.generate({
    bindto: '#chartHazard',
    data: c3Data,
});
*/

function updateChartData() {
  var latitude = getCurrentLatitude();
  var longitude = getCurrentLongitude();
  var hazard = vueSelect.getCurrentHazard();
  var url = apiBaseUrl+'/api/hazard-time-chart';
  // url = 'https://gis.clim-ability.eu/index.php/api/hazard-values';
  url = url + '?latitude='+latitude+'&longitude='+longitude+'&hazard='+hazard;
  axios.get(url).then(response => {
    c3Data.columns = response.data; 
	c3Chart = c3.generate({
      bindto: '#chartHazard',
      data: c3Data,
    });
  });
}
}

{
	

var statisticData = [];
		   
function setStatisticData(data) {
   statisticData = data;
}	

function updateZoomLevel() {
	var zoom = map.getZoom();
	
	statisticOptions['displayOptions'] = {
		'poll_count': {
			radius: 6.0 * Math.pow(2.0, zoom - 7.0),
			barThickness: 5.0 * Math.pow(2.0, zoom - 7.0)
	}};
}

function initStatisticOptions(hazards)
{
	var zoom = map.getZoom();
	var chartOptions = {};
	chartOptions['values[hazard=none].value'] =
		{
			color: '#000000',
			fillColor: '#FFFFFF',
			maxValue: 1,
			maxHeight: 20,
			displayName: 'none'
		};	
	for (var i = 0; i < hazards.length; i++) {
		chartOptions['values[hazard='+hazards[i].name+'_plus].value'] =
		{
			color: '#000000',
			fillColor: hazards[i].color_max,
			maxValue: 1,
			maxHeight: 20,
			displayName: hazards[i].label+'+',
			displayText: function (value) {
				return value.toFixed(2);
			}
		};	
		chartOptions['values[hazard='+hazards[i].name+'_minus].value'] =
		{
			color: '#000000',
			fillColor: hazards[i].color_min,
			maxValue: 1,
			maxHeight: 20,
			displayName: hazards[i].label+'-',
			displayText: function (value) {
				return value.toFixed(2);
			}
		};			
    }
	statisticOptions['chartOptions'] = chartOptions;
	//statisticOptions['chartOptions'] = {};
}

var statisticOptions = {
	recordsField: null,
	locationMode: L.LocationModes.LATLNG,
	//codeField: 'state',
	latitudeField: 'latitude',
	longitudeField: 'longitude',
	showLegendTooltips: false,
	tooltipOptions: {
		iconSize: new L.Point(0, 0),
		iconAnchor: new L.Point(0, 0),
		mouseOverExaggeration: 1,
	},
	chartOptions: {},
	layerOptions: {
		fillOpacity: 0.9,
		opacity: 1,
		weight: 0.5,
		radius: 13,
		width: 1,
		barThickness: 8
	},
	displayOptions: {
		'poll_count': {
			radius: 13,
			barThickness: 10
		}
	}

};


  var colorParameters = {
	minValueLocal: -1000.0,
	maxValueLocal: +1000.0,
    minValueGlobal: -1000.0,
	maxValueGlobal: +1000.0,
	minOpacity: 0.2,
	maxOpacity: 0.8,
	minColor: '#000000',
	maxColor: '#000000',
	reverse: false
  }	  

  function setExtremeValuesLocal(vmin, vmax)
  {
    colorParameters.minValueLocal = parseFloat(vmin);
	colorParameters.maxValueLocal = parseFloat(vmax);
  }	 
	
  function setExtremeValuesGlobal(vmin, vmax)
  {
    colorParameters.minValueGlobal = parseFloat(vmin);
	colorParameters.maxValueGlobal = parseFloat(vmax);
	colorParameters.reverse = ((colorParameters.minValueGlobal+colorParameters.maxValueGlobal) < 0.0);
  }	  

  function setExtremeColors(cmin, cmax)
  {
    colorParameters.minColor = cmin;
    colorParameters.maxColor = cmax;	  
  }	
	
  function getStyleColor(d) {
	var amount = (parseFloat(d) - colorParameters.minValueGlobal) / (colorParameters.maxValueGlobal - colorParameters.minValueGlobal);
	amount = Math.min(1.0, Math.min(1.0, amount)); 
	if (colorParameters.reverse) {
		amount = 1.0 - amount;
	}
    var color = interpolateColor(colorParameters.minColor, colorParameters.maxColor, amount);
	return color;
  }

  function getStyleOpacity(d) {
	var amount = (parseFloat(d) - colorParameters.minValueLocal) / (colorParameters.maxValueLocal - colorParameters.minValueLocal);
	amount = Math.min(1.0, Math.min(1.0, amount)); 
	if (colorParameters.reverse) {
		amount = 1.0 - amount;
	}
    var opacity = amount*(colorParameters.maxOpacity - colorParameters.minOpacity) + colorParameters.minOpacity;
	return opacity;
  }
  
  function getDigitsGlobal() {
	 return Math.log10(Math.abs(colorParameters.maxValueGlobal - colorParameters.minValueGlobal));  
  }
  
  function getValueGlobal(amount) {
    var value = amount*(colorParameters.maxValueGlobal - colorParameters.minValueGlobal) + colorParameters.minValueGlobal;
	return value;
  }  
		
  function LayerStyle(feature) {
    return {
    fillColor: getStyleColor(feature.properties.value),
    weight: 0.9,
    opacity: 0.8,
    color: 'black',
    dashArray: '0',
    fillOpacity: getStyleOpacity(feature.properties.value) 
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
		absolute: absMode
        };
	  redrawParameters();
    }	
  
    var imageAdded = null;
	var statisticLayer = null;

	function redrawParameters() {
	  if('off' == defaultParameters.hazard) {
        geojsonLayerWells.clearLayers();
		if (map.hasLayer(statisticLayer)) { map.removeLayer(statisticLayer); }
        if (!map.hasLayer(pointLayer)) { map.addLayer(pointLayer); }	
        if (map.hasLayer(imageAdded)) { map.removeLayer(imageAdded); }		
	  } else if('all' == defaultParameters.hazard) {
        geojsonLayerWells.clearLayers();
		if (map.hasLayer(statisticLayer)) { map.removeLayer(statisticLayer); }
        if (map.hasLayer(pointLayer)) { map.removeLayer(pointLayer); }		
        statisticLayer = new L.PieChartDataLayer(statisticData,statisticOptions);
		//statisticLayer.unbindTooltip();
		//statisticLayer.off('mouseover');

        map.addLayer(statisticLayer);
		statisticLayer.on('click', onMapClick);
      } else {	
        if (!map.hasLayer(pointLayer)) { map.addLayer(pointLayer); }					
        if (map.hasLayer(imageAdded)) { map.removeLayer(imageAdded); }
        if (map.hasLayer(statisticLayer)) { map.removeLayer(statisticLayer); }			
        //var geoJsonUrl ='https://gis.clim-ability.eu/index.php/api/hazard-geom'; 
		var geoJsonUrl = apiBaseUrl+'/api/hazard-geom';
        var parameters = L.Util.extend(defaultParameters, customParams);
        $.ajax({
          url: geoJsonUrl + L.Util.getParamString(parameters),
          datatype: 'json',
          jsonCallback: 'getJson',
          success: loadGeoJson
        });
	  }	
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


var baseMaps = {};    
L.control.layers(baseMaps,{
	   '<strong>Openstreetmap<strong/><br />':OpenStreetMap_DE, 
       '<strong>Border<strong/><br />':geojsonLayerBorder,  
	   '<strong>Stations<strong/><br />':pointLayer 
}).addTo(map); 


}

redrawParameters();
initStationData();
initBorders();



var vueEventBus = new Vue({ });
	  
var tabSelect = new Vue({
	el: '#tabselect',
	data: {
		activeTab: 'climate_info'
	},
	methods: {
		isTabActive(tab) {
           return (tab == this.activeTab);
		},
		activateTab(tab) {
			this.activeTab = tab;	 
		}
	}
})

var vueOther = new Vue({
	el: '#other',
	methods: {
		isTabActive(tab) {
			return tabSelect.isTabActive(tab);
		}
	}
})

var vueImpacts = new Vue({
	el: '#impacts_adaptions',
	methods: {
		isTabActive(tab) {
			return tabSelect.isTabActive(tab);
		}
	}
})

var vueSelect = new Vue({
  el: '#selectionrow',
  data: {
    hazard: 'none',
    hazards: [ { label: '', name: 'none' } ],
    epoch: 'none',
    epochs: [ { label: '', name: 'none' } ],
    scenario: 'none',
    scenarios: [ { label: '', name: 'none' } ],
    sector: 'none',
    sectors: [ { label: '', name: 'none' } ],	
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
	    if(('all' != this.hazard) && ('off' != this.hazard)) {
		  //mk// updateChartData();
		  var url = apiBaseUrl+'/api/hazard-extremes?hazard='+this.hazard;
          if('abs' == absMode) {
            url += '&absolute=true';
		  }			  
  		  axios
            .get(url)
            .then(response => { 
		      setExtremeValuesGlobal(response.data[0].min, response.data[0].max);
              setParametersOnMap(this.hazard, this.epoch, this.scenario);	
		      updateLegend(this.hazard, this.epoch, this.scenario, this.hazards);				
	    	});	
		  var url = apiBaseUrl+'/api/hazard-extremes?hazard='+this.hazard+'&epoch='+this.epoch+'&scenario='+this.scenario;
          if('abs' == absMode) {
            url += '&absolute=true';
		  }	
  		  axios
		    .get(url)
            .then(response => { 
		      setExtremeValuesLocal(response.data[0].min, response.data[0].max);
              setParametersOnMap(this.hazard, this.epoch, this.scenario);	
	    	  updateLegend(this.hazard, this.epoch, this.scenario, this.hazards);				
	  	  });		
        } else {
	      var url = apiBaseUrl+'/api/hazards-statistic?epoch='+this.epoch+'&scenario='+this.scenario;
          if('abs' == absMode) {
            url += '&absolute=true';
		  }			  
  		  axios
			.get(url)
            .then(response => { 
              setStatisticData(response.data);		
		      setParametersOnMap(this.hazard, this.epoch, this.scenario);		
		      updateLegend(this.hazard, this.epoch, this.scenario, this.hazards);			
	    	});			
		}	
		//window.viewInfo.clickOnMap();
		vueEventBus.$emit('updatedParameters', this);
        //setParametersOnMap(this.hazard, this.epoch, this.scenario);		
	  }
	},
	getCurrentSector() {return this.sector; },
	getCurrentEpoch() {return this.epoch; },
	getCurrentSzenario() {return this.scenario; },
	getCurrentHazard() {return this.hazard; },
	setCurrentHazard(hazard) {this.hazard = hazard; }
  },
  mounted () {
    axios
      .get(apiBaseUrl+'/api/hazards?language='+currentLanguage)
	  //.get('https://gis.clim-ability.eu/index.php/api/hazards')
      .then(response => { 
	    this.hazards = response.data; 
		initStatisticOptions(this.hazards);
		var noneTranslate = tr('Hazard:name', 'none');
		this.hazards.unshift({name: 'off', label: noneTranslate, color_min: '#000000', color_max: '#FFFFFF'});
		var allTranslate = tr('Hazard:name', 'all');
		this.hazards.push({name: 'all', label: allTranslate, color_min: '#000000', color_max: '#FFFFFF'});
		this.hazard = this.hazards[0].name;
		this.updateParameters();
		updateLegend(this.hazard, this.epoch, this.scenario, this.hazards);
		});
    axios
      .get(apiBaseUrl+'/api/epochs')
	  //.get('https://gis.clim-ability.eu/index.php/api/epochs')
      .then(response => { this.epochs = response.data; 
		this.epoch = this.epochs[0].name;
		this.updateParameters();
		 });
    axios
      .get(apiBaseUrl+'/api/scenarios?language='+currentLanguage)
	  //.get('https://gis.clim-ability.eu/index.php/api/scenarios')
      .then(response => { this.scenarios = response.data; 
		this.scenario = this.scenarios[0].name;
		this.updateParameters();
		 });
	axios
		 .get(apiBaseUrl+'/api/sectors?language='+currentLanguage)
		 //.get('https://gis.clim-ability.eu/index.php/api/hazards')
		 .then(response => { 
		   this.sectors = response.data; 
		   //initStatisticOptions(this.hazards);
		   var noneTranslate = tr('Sector:name', 'none');
		   //this.hazards.unshift({name: 'off', label: noneTranslate, color_min: '#000000', color_max: '#FFFFFF'});
		   var allTranslate = tr('Sector:name', 'all');
		   //this.hazards.push({name: 'all', label: allTranslate, color_min: '#000000', color_max: '#FFFFFF'});
		   this.sector = this.sectors[0].name;
		   //this.updateParameters();
		   //updateLegend(this.hazard, this.epoch, this.scenario, this.hazards);
		   });		 
    axios
      .get(apiBaseUrl+'/api/languages')
	  //.get('https://gis.clim-ability.eu/index.php/api/languages')
      .then(response => { this.languages = response.data; 
		this.language = this.languages[0].name;
		 });	  
  }
})

var nodeIcon3 = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="32" viewBox="0 0 20 32"><path d="M20 14h-8l6-14-18 18h8l-6 14 18-18z"></path></svg>'
// https://www.svgrepo.com/collection/zwicon-line-icons/1
// https://icons8.de/icons/set/wetter



var D3Network = window['vue-d3-network']
var vueNetwork = new Vue({
	el: '#impacts_adaptions',
	components: {
		D3Network
	  },	
	data: {
       nodes: [
        { id: 1, name:'orange node', _color: 'orange' },
        { id: 2, name:'blue node',_color: '#00aaff', svgSym: this.iconSvg},
        { id: 3, svgSym:nodeIcon3 },
      ],
       links: [
        { sid: 1, tid: 2, name:'link 1' },
        { sid: 2, tid: 3, }, 
	   ],	
	   svgIcon: '<svg></svg>',
	   nodeSize:12,
	   canvas:false,
	   currentNode:0	      
	},
	computed:{
		iconSvg(){
          return this.svgIcon;
        },
		options(){
		  return{
			force: 3000,
			size:{ w:500, h:500},
			nodeSize: this.nodeSize,
			nodeLabels: true,
			linkLabels: true,
			canvas: this.canvas,
			linkWidth:2
		  }
		}
	},
	methods:{
	  lcb (link) {
		return link
	  },
	  setIcon(svg) {
        this.svgIcon = svg;
	  },
	  setNodes(nodes) {
        this.nodes = nodes;
	  },
	  setLinks(links) {
        this.links = links;
	  },
	  setCurrentNode(event, node) {
        this.currentNode = node.id;
		this.getGraph();
	  },
	  getGraph() {
		var currSector = vueSelect.getCurrentSector();  
        var url = apiBaseUrl+'/graph/full-graph';
		var url = url+'?current='+this.currentNode.toString();
		var url = url+'&sector='+currSector;
		axios
		  .get(url)
		  //.get('https://gis.clim-ability.eu/index.php/api/hazards')
		  .then(response => { 
			this.setNodes(response.data.nodes);
			this.setLinks(response.data.links);    
			});	
	  },
	  xxx() {
		var url = apiBaseUrl+'/images/test.svg';
        url = url.replace('/index.php/','/');
		axios
		  .get(url)
		  //.get('https://gis.clim-ability.eu/index.php/api/hazards')
		  .then(response => { 
			this.setIcon(response.data);  
			});	
		var oldLinks = this.links;	
		this.nodes = [
				{ id: 1, name:'red Node', _color: '#00aaff' },
				{ id: 2, name:'blue Node',_color: 'red', svgSym: this.iconSvg},
				{ id: 3, svgSym:nodeIcon3 },
			  ];
		this.links = [
			{ sid: 1, tid: 2, name:'Link 1' },
			{ sid: 2, tid: 3, }, 
		   ];	  
	  },
	},
	mounted () {
		vueEventBus.$on('updatedParameters', e => { this.getGraph();})
		var url = apiBaseUrl+'/images/small.svg';
        url = url.replace('/index.php/','/');
		axios
		  .get(url)
		  //.get('https://gis.clim-ability.eu/index.php/api/hazards')
		  .then(response => { 
			this.setIcon(response.data);  
			});	
        this.getGraph();		
		},

})


var vueInfo = new Vue({
  el: '#climate_info',
  data: {
    info: 'none',
	infoVisible: false,
	currHazard: '',
	currEpoch: '',
	currSzenario: '',
    nearestStation: {},
    bestStation: {},
	dangerText: '',
//	dangers: {},
//	risks: {},
    sector: 'none',
    sectors: [ { label: '', name: 'none' } ]
  },
  methods: { 
	isTabActive(tab) {
		return tabSelect.isTabActive(tab);
	},   
    switchHazard(hazard) {
		vueSelect.setCurrentHazard(hazard);
		vueSelect.updateParameters();
		this.currHazard = vueSelect.getCurrentHazard(); 
	},	 
	updateSector() {
		// should get risks only...
		// for know all
		this.clickOnMap();
	},
    clickOnMap() {
		var latitude = getCurrentLatitude();
		var longitude = getCurrentLongitude();
		if (latitude !== 0 && longitude !== 0) {
		  var url = apiBaseUrl+'/api/station-data';
	      url = url + '?latitude='+latitude+'&longitude='+longitude+'&language='+currentLanguage;
          axios.get(url).then(response => {
	         this.nearestStation = response.data; 
	      });
		  this.currEpoch = vueSelect.getCurrentEpoch();
		  this.currSzenario = vueSelect.getCurrentSzenario();
		  this.currHazard = vueSelect.getCurrentHazard();
	      var url = apiBaseUrl+'/api/hazard-values';
	      url = url + '?latitude='+latitude+'&longitude='+longitude+'&epoch='+this.currEpoch+'&scenario='+this.currSzenario;
          axios.get(url).then(response => {
	         this.info = response.data; 
	         this.infoVisible = true;

		    var latitude = getCurrentLatitude();
		    var longitude = getCurrentLongitude();
                    //  this.info.landscape
	    	    if (latitude !== 0 && longitude !== 0 && response.data.landscape) {
                      var elevMin = response.data.landscape.elevMin;
                      var elevMax = response.data.landscape.elevMax;
                      //var elevMin = 300;
                      //var elevMax = 600;
		      var url = apiBaseUrl+'/api/station-data';
	              url = url + '?latitude='+latitude+'&longitude='+longitude+'&language='+currentLanguage+'&elevmin='+elevMin+'&elevmax='+elevMax;
                      axios.get(url).then(response => {
	                  this.bestStation = response.data; 
	              });
                    }

	      });
	  
	    }
	},
	getGeoLocation() {
	  if (navigator.geolocation) { 
        navigator.geolocation.getCurrentPosition(function(position) {
          let latitude = position.coords.latitude;
          let longitude = position.coords.longitude;
	      var e = {'latlng': {'lat': latitude, 'lng': longitude}};
	      onMapClick(e);
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
		var decrease = tr('hazards', 'Decrease by');
		var increase = tr('hazards', 'Increase by');
		var upto = tr('hazards', 'to');
		if(parseFloat(para.value) < 0.0) {
		   var allText = decrease+' <b>'+(0.0-plus)+' '+upto+' '+(0.0-minus)+'</b>';	
           return {'allText':allText, 'tendency':decrease, 'min':(0.0-plus), 'max':(0.0-minus), 'to':upto};		   
		} else {
           var allText = increase+' <b>'+minus+' '+upto+' '+plus+'</b>';
		   return {'allText':allText, 'tendency':increase, 'min':minus, 'max':plus, 'to':upto};	
		}
	  }
	  return '';
	}

  },
  computed: {
	roundedElevation: function() {
      //return this.roundedValue(this.info.elevation_calculated.value, 2);
	  return this.roundedValue(this.info.elevation_iso_raster.value, 2);
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
	roundedSd: function () {
	  return this.roundedRange(this.info.sd_delta_calculated, 1);
    },
	roundedTr: function () {
	  return this.roundedRange(this.info.tr_delta_calculated, 1);
    },
  },
  mounted () {
    vueEventBus.$on('updatedParameters', e => { this.clickOnMap();})
	//addCategoryToTranslationPool('hazards');
	axios
      .get(apiBaseUrl+'/api/sectors?language='+currentLanguage)
      .then(response => { 
	    this.sectors = response.data; 
		var allTranslate = tr('Sector:name', 'all');
		this.sectors.unshift({name: 'all', label: allTranslate});
		this.sector = this.sectors[0].name;
		//this.updateSector();
		});
  }
})
