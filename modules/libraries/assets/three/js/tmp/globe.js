$(document).ready(function () {
var renderDiv = document.getElementById("rendering");
var camera = new THREE.PerspectiveCamera(45, window.innerWidth/window.innerHeight, 0.1, 10000); 
//var camera = new THREE.OrthographicCamera( window.innerWidth/window.innerHeight, window.innerWidth, 0.5*window.innerHeight, -0.5*window.innerHeight, -1000, 1000 );

var renderer = new THREE.WebGLRenderer({ antialias: true }); 
renderer.gammaInput = true;
renderer.gammaOutput = true;
                
var addCube = function (scene,year,lat,long,amount,age,own) {
// CUBE

switch (dataMode) {
  case 'cubic':
// linear coordinates   
    radius = 60.0 * amount;
    break;
  case 'earth':
  case 'countries':
// polar coordinates
    radius = 6.0 * amount;
    break;
case 'lastyear':
// polar coordinates
    radius = 1.0 * amount;
    break;
}

geo = new THREE.SphereGeometry( radius, 20.0, 20.0 );
switch(true) {
    case (age > -30.0):
      f = age/-30.0;
      geoColor = new THREE.Color( 1-f, f, 0 );
      break;
    case (age > -90.0):
      f = (age+30.0)/-60.0;  
      geoColor = new THREE.Color( 0, 1-f, f );
      break;
    case (age > -360.0):
      f = (age+90.0)/-270.0;  
      geoColor = new THREE.Color( f, f, 1-f );
      break;
    default:
        f = (age+360.0)/-360.0;
        f = 0.5+0.5/(1+f);
      geoColor = new THREE.Color( f, f, f );
}
if ('lastyear' == dataMode) {
  if (own > 1E-10) {
    f = Math.sqrt(0.2+0.8*Math.sqrt(own));
  } else {
    f = 0.0;
  }
  geoColor = new THREE.Color( f, 1-f, 0.5 );
  geoColor = new THREE.Color( 1.0, 1-f, 1-f );
}

var mat = new THREE.MeshPhongMaterial( { color: geoColor, specular: 0x101010, shininess: 80, combine: THREE.MixOperation, reflectivity: 0.1, side: THREE.DoubleSide, opacity: Math.sqrt(amount), transparent: true  } );

cube = new THREE.Mesh( geo, mat );	



switch (dataMode) {
case 'cubic':    
// linear coordinates   
cube.translateX(4*(year - dataCenter[0]));
cube.translateY(20*(lat - dataCenter[1]));
cube.translateZ(20*(long - dataCenter[2]));
break;

case 'earth':
case 'countries':  
case 'lastyear':
// polar coordinates
cube.rotateY(lat*Math.PI/180);
cube.rotateZ(long*Math.PI/180);
cube.translateX(250+year/10);
break;
}
//cube.updateMatrix();
scene.add( cube );


}

var drawEarth = function (scene, minYear) {
if (minYear < 1000) {minYear = 1000; }
var geometry = new THREE.SphereGeometry( minYear/4 , 40, 40 );
	var material	= new THREE.MeshPhongMaterial({
		map		: THREE.ImageUtils.loadTexture(urlAsset + '/images/earthmap1k.jpg'),
		bumpMap		: THREE.ImageUtils.loadTexture(urlAsset + '/images/earthbump1k.jpg'),
		bumpScale	: 0.05,
		specularMap	: THREE.ImageUtils.loadTexture(urlAsset + '/images/earthspec1k.jpg'),
		specular	: new THREE.Color('grey'),
	});
cube = new THREE.Mesh( geometry, material );	
scene.add( cube );    
}

var drawBlock = function (scene) {
var ymin = 4*(0-dataCenter[0]);    
var ymax = 4*(2000-dataCenter[0]);
var ltmin = 20*(-180-dataCenter[1]);    
var ltmax = 20*(+180-dataCenter[1]);
var lgmin = 20*(-90-dataCenter[1]);    
var lgmax = 20*(+90-dataCenter[1]);

var material = new THREE.LineBasicMaterial({ color: 0xAAAAAA, opacity: 0.8, transparent: true });
var geo1 = new THREE.Geometry();
geo1.vertices.push(new THREE.Vector3(ymin, 0, 0));
geo1.vertices.push(new THREE.Vector3(ymax, 0, 0));
var line1 = new THREE.Line(geo1, material);
scene.add(line1);
var geo2 = new THREE.Geometry();
geo2.vertices.push(new THREE.Vector3(0, 0, lgmin));
geo2.vertices.push(new THREE.Vector3(0, 0, lgmax));
var line2 = new THREE.Line(geo2, material);
scene.add(line2);
var geo3 = new THREE.Geometry();
geo3.vertices.push(new THREE.Vector3(0, ltmin, 0));
geo3.vertices.push(new THREE.Vector3(0, ltmax, 0));
var line3 = new THREE.Line(geo3, material);
scene.add(line3);   
var geo4 = new THREE.Geometry();
geo4.vertices.push(new THREE.Vector3(ymax, ltmin, lgmin));
geo4.vertices.push(new THREE.Vector3(ymax, ltmin, lgmax));
geo4.vertices.push(new THREE.Vector3(ymin, ltmin, lgmax));
geo4.vertices.push(new THREE.Vector3(ymin, ltmax, lgmax));
geo4.vertices.push(new THREE.Vector3(ymin, ltmax, lgmin));
geo4.vertices.push(new THREE.Vector3(ymax, ltmax, lgmin));
geo4.vertices.push(new THREE.Vector3(ymax, ltmax, lgmax));
var line4 = new THREE.Line(geo4, material);
scene.add(line4);     

var geo5 = new THREE.Geometry();
geo5.vertices.push(new THREE.Vector3(ymax, ltmax, lgmin));
geo5.vertices.push(new THREE.Vector3(ymax, ltmin, lgmin));
geo5.vertices.push(new THREE.Vector3(ymin, ltmin, lgmin));
geo5.vertices.push(new THREE.Vector3(ymin, ltmax, lgmin));
var line5 = new THREE.Line(geo5, material);
scene.add(line5);  

var geo6 = new THREE.Geometry();
geo6.vertices.push(new THREE.Vector3(ymin, ltmin, lgmax));
geo6.vertices.push(new THREE.Vector3(ymin, ltmin, lgmin));
var line6 = new THREE.Line(geo6, material);
scene.add(line6);  
}




renderer.setSize(window.innerWidth, window.innerHeight); 
renderer.setClearColor(0xffffff, 0); // bg color

renderDiv.appendChild(renderer.domElement); // displays canvas

camera.position.z = 4000; // move away to see coord center
camera.position.x = 0;
camera.position.y = 0;

controls = new THREE.TrackballControls(camera, renderer.domElement);


var scene = new THREE.Scene(); 

//scene.fog = new THREE.Fog( 0xffffff, 1, 5000 );
//scene.fog.color.setHSL( 0.6, 0, 1 );
  
// LIGHTS
hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffff, 0.6 );
hemiLight.color.setHSL( 0.6, 1, 0.6 );
hemiLight.groundColor.setHSL( 0.095, 1, 0.75 );
hemiLight.position.set( 0, 500, 0 );
scene.add( hemiLight );

dirLight = new THREE.DirectionalLight( 0xffffff, 1 );
dirLight.color.setHSL( 0.1, 1, 0.95 );
dirLight.position.set( -1, 1.75, 1 );
dirLight.position.multiplyScalar( 50 );
scene.add( dirLight );

var index,year,lat,long,opa;
var yearMax = [];
var valMax  = [];
var minYear = 9999;

for (index in dataCubes) {
    
    dataRow = dataCubes[index];
    year = dataRow[0];
    lat = dataRow[1];
    long = dataRow[2];
    opa = dataRow[3];
    age = dataRow[4];
    own = dataRow[5];
    addCube(scene,year,lat,long,opa,age,own);
    
    if (minYear > year) {
        minYear = year;
    }
    maxyear = yearMax[[lat, long]];
    if ((maxyear == null) || (maxyear < year)) {
       yearMax[[lat, long]] = year;
    }
    maxval = valMax[[lat, long]];
    if ((maxval == null) || (maxval < opa)) {
       valMax[[lat, long]] = opa;
    }    
    
}

if (('earth' == dataMode) || ('lastyear' == dataMode) || ('countries' == dataMode)) {
for (index in yearMax) {
    year = yearMax[index];
    opa = valMax[index]
    coord = index.split(',')
    lat = parseInt(coord[0]);
    long = parseInt(coord[1]);
    
var material = new THREE.LineBasicMaterial({ color: 0x888888, opacity: opa, transparent: true });
var geo1 = new THREE.Geometry();
geo1.vertices.push(new THREE.Vector3(250 + year/10,0,0));
geo1.vertices.push(new THREE.Vector3(0, 0, 0));
var line1 = new THREE.Line(geo1, material);  
line1.rotateY(lat*Math.PI/180);
line1.rotateZ(long*Math.PI/180);
scene.add( line1 );
}
}

switch (dataMode) {
 case 'cubic':   
// linear coordinates   
drawBlock(scene);
break;

case 'earth':
case 'lastyear':
case 'countries':    
// polar coordinates
drawEarth(scene, minYear);
break;
}

var render = function () { 
    requestAnimationFrame(render); 
    controls.update();
    renderer.render(scene, camera); 
};

render();   
});


    