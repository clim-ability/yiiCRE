//var mapBaseUrl = '.';


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

 var tmo_region = L.tileLayer.wms("http://climability.uni-landau.de/cgi-bin/qgis_mapserv.fcgi?map=/var/www/html/climability/cgi-bin/climability_TMO.qgs", {
    layers: 'tmo_region',
    format: 'image/png',
    transparent: true,
    attribution: "climability"
  }).addTo(map);

//locate by click
var popup = L.popup();
function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("I am here: " + e.latlng.toString())
        .openOn(map);
}
map.on('click', onMapClick);


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

function setStyleColor(d) {
        return d > 100  ? '#FC4E2A' :
               d > 50   ? '#FD8D3C' :
               d > 20   ? '#FEB24C' :
               d > 10   ? '#FED976' :
                          '#FFEDA0';
        }
		
function LayerStyle(feature) {
  return {
  fillColor: setStyleColor(feature.properties.value),
  weight: 2,
  opacity: 0.7,
  color: 'white',
  dashArray: '3',
  fillOpacity: 0.5 };
}		

function loadGeoJson(data) {
    //console.log(data);
	geojsonLayerWells.clearLayers();
    geojsonLayerWells.addData(data);
	mylayer.setStyle(LayerStyle);
	//map.removeLayer(geojsonLayerWells);
    //map.addLayer(geojsonLayerWells);
};

//$hazard='cddp', $epoch='2041-2070', $scenario='rcp45'

map.on('moveend', function(){
 if(map.getZoom() > -200){
    var geoJsonUrl ='https://gis.clim-ability.eu/index.php/api/hazard-geom'; 
    var defaultParameters = {
        hazard: 'cddp',
        epoch: '2041-2070',
        scenario: 'rcp45',
        };

    var customParams = {
        bbox: map.getBounds().toBBoxString(),
        };
    var parameters = L.Util.extend(defaultParameters, customParams);
    //console.log(geoJsonUrl + L.Util.getParamString(parameters));

  $.ajax({
        url: geoJsonUrl + L.Util.getParamString(parameters),
        datatype: 'json',
        jsonCallback: 'getJson',
        success: loadGeoJson
        });
    }else{
    map.removeLayer(geojsonLayerWells);
    };
});

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
   L.control.layers(baseMaps,{'<strong>openstreetmap<strong/><br />':OpenStreetMap_DE, '<strong>upper Rhine region<strong/><br />':tmo_region, '<strong>climate indicators<strong/><br />':imageUrl}).addTo(map);   
