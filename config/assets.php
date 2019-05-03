<?php

return [
    'forceCopy' => YII_ENV_DEV,
    'appendTimestamp' => true,
    'bundles' => [
        'yii\web\JqueryAsset' => [    // 'app\modules\libraries\bundles\JqueryAsset'
            'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js',
                YII_ENV_DEV ? 'jquery.cookie.js' : 'jquery.cookie.min.js',
            ],
        ],
        'yii\bootstrap\BootstrapAsset' => [    // 'app\modules\libraries\bundles\BootstrapAsset'
            'sourcePath' => '@app/modules/libraries/assets/bootstrap', // do publish the bundle, as it is customized
              'basePath' => null,
              'baseUrl'  => null,
            'css' => [
                YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css'
            ],
        ],
        'app\modules\libraries\bundles\JqueryUiAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'css' => [
                'jquery-ui.css',
            ],
            'js' => [
                YII_ENV_DEV ? 'jquery-ui.js' : 'jquery-ui.min.js'
            ],
        ],
        'app\modules\libraries\bundles\ContextMenu' => [
            'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'jquery.ui-contextmenu.min.js' : 'jquery.ui-contextmenu.min.js'
            ],
        ],
        'app\modules\libraries\bundles\AngularAsset' => [
            //'sourcePath' => null, // do not publish the bundle if null (ng-modules is local)
            'js' => [
                YII_ENV_DEV ? 'angular.js' : 'angular.min.js',
                YII_ENV_DEV ? 'angular-route.js' : 'angular-route.min.js',
                YII_ENV_DEV ? 'angular-resource.js' : 'angular-resource.min.js',
                'js/angular.ng-modules.js', // https://github.com/luisperezphd/ngModule
            ],
        ],
        'app\modules\libraries\bundles\D3Asset' => [
            //'sourcePath' => null, // do not publish the bundle if null
            'css' => [
                YII_ENV_DEV ? 'css/c3.css' : 'css/c3.min.css',
                YII_ENV_DEV ? 'css/dc.css' : 'css/dc.min.css',
                'css/colorbrewer.css',
            ],
            'js' => [
                YII_ENV_DEV ? 'd3.js' : 'd3.min.js',
                YII_ENV_DEV ? 'd3-legend.js' : 'd3-legend.min.js',
                YII_ENV_DEV ? 'c3.js' : 'c3.min.js',
                'js/crossfilter.js',
                'js/colorbrewer.js',
                YII_ENV_DEV ? 'dc.js' : 'dc.min.js',                
                YII_ENV_DEV ? 'js/cloud.js' : 'js/cloud.js',

            ],
        ],
        'app\modules\libraries\bundles\DynaTableAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'css' => [
                YII_ENV_DEV ? 'dynatable.css' : 'dynatable.min.css'
            ],
            'js' => [
                YII_ENV_DEV ? 'dynatable.js' : 'dynatable.min.js'
            ],
        ],
        'app\modules\libraries\bundles\LeafletAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'css' => [
                'leaflet.css',
            ],
            'js' => [
                YII_ENV_DEV ? 'leaflet.js' : 'leaflet.min.js',
				'javascript.util.min.js',
				'jsts.min.js',
				'leaflet-dvf.js',
            ],
        ],
        'app\modules\libraries\bundles\OpenlayersAsset' => [
            //'sourcePath' => null, // do not publish the bundle if null
            'css' => [
                YII_ENV_DEV ? 'ol3.css' : 'ol3.min.css',
                'css/ol3-layerswitcher.css',
            ],
            'js' => [
                YII_ENV_DEV ? 'ol3.js' : 'ol3.min.js',
                'js/ol3-layerswitcher.js',
            ],
        ],        
        'app\modules\libraries\bundles\FancyTreeAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'css' => [
                YII_ENV_DEV ? 'fancytree.css' : 'fancytree.min.css'
            ],
            'js' => [
                YII_ENV_DEV ? 'fancytree.js' : 'fancytree.min.js'
            ],
        ],
        'app\modules\libraries\bundles\HighlightAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'css' => [
             // YII_ENV_DEV ? 'default.css' : 'default.min.css',
             // YII_ENV_DEV ? 'atelier-dune-light.css' : 'atelier-dune-light.min.css' ,   
             // YII_ENV_DEV ? 'agate.css' : 'agate.min.css' , 
             // YII_ENV_DEV ? 'foundation.css' : 'foundation.min.css' , 
             // YII_ENV_DEV ? 'github.css' : 'github.min.css' , 
                YII_ENV_DEV ? 'zenburn.css' : 'zenburn.min.css' , 
           ],
            'js' => [
                YII_ENV_DEV ? 'highlight.js' : 'highlight.min.js',
                YII_ENV_DEV ? 'http.js' : 'http.min.js',
                YII_ENV_DEV ? 'json.js' : 'json.min.js',
                YII_ENV_DEV ? 'xml.js' : 'xml.min.js',
             // YII_ENV_DEV ? 'cpp.js' : 'cpp.min.js',      
                YII_ENV_DEV ? 'r.js' : 'r.min.js',                 
            ],
        ],        
        'app\modules\libraries\bundles\IntroAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'css' => [
                YII_ENV_DEV ? 'hopscotch.css' : 'hopscotch.min.css'
            ],
            'js' => [
                YII_ENV_DEV ? 'hopscotch.js' : 'hopscotch.min.js'
            ],
        ],
        'app\modules\libraries\bundles\ThreeAsset' => [
            //'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'three.js' : 'three.min.js',
                YII_ENV_DEV ? 'js/TrackballControls.js' : 'js/TrackballControls.js',
            ],
        ], 
        'app\modules\libraries\bundles\LockrAsset' => [
            'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'lockr.js' : 'lockr.min.js',
            ],
        ],   
        'app\modules\libraries\bundles\TweenAsset' => [
            //'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'tweenjs.js' : 'tweenjs.min.js',
                'js/tween.js',
            ],
        ],  
        'app\modules\libraries\bundles\PdfAsset' => [
            //'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'jspdf.js' : 'jspdf.min.js',
                YII_ENV_DEV ? 'jspdf.plugin.autotable.js' : 'jspdf.plugin.autotable.min.js',
                //'pdfjs.js',
            ],
        ], 
        'app\modules\libraries\bundles\VueAsset' => [
            //'sourcePath' => null, // do not publish the bundle
            'js' => [
                YII_ENV_DEV ? 'vue.js' : 'vue.min.js',
				YII_ENV_DEV ? 'axios.js' : 'axios.min.js',
            ],
        ], 		
    ],
    'assetMap' => [
        // https://developers.google.com/speed/libraries/
        // https://cdnjs.com/libraries
        // 
        'tinymce.jquery.min.js' => '//cdnjs.cloudflare.com/ajax/libs/tinymce/4.2.1/tinymce.jquery.min.js', 
        'tinymce.jquery.js' => '//cdnjs.cloudflare.com/ajax/libs/tinymce/4.2.1/tinymce.jquery.js', 
        // jquery - https://jquery.com/
        'jquery.min.js' => '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js',
        'jquery.js' => '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.js',
        // cookie 
        'jquery.cookie.min.js' => '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
        'jquery.cookie.js' => '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js',
        //
        // jquery-ui - https://jqueryui.com/
        'jquery-ui.min.js' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
        'jquery-ui.js' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js',
        'jquery-ui.css' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css',
        //
        // jquery-context-menu - https://github.com/mar10/jquery-ui-contextmenu/
        // https://cdnjs.cloudflare.com/ajax/libs/jquery.ui-contextmenu/1.10.0/jquery.ui-contextmenu.min.js
        'jquery.ui-contextmenu.min.js' => '//cdn.jsdelivr.net/jquery.ui-contextmenu/1.8.2/jquery.ui-contextmenu.min.js',
        'jquery.ui-contextmenu.js' => '//cdn.jsdelivr.net/jquery.ui-contextmenu/1.8.2/jquery.ui-contextmenu.min.js',
        //
        // angular - https://angularjs.org/
        'angular.min.js' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js',
        'angular.js' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.js',
        'angular-resource.min.js' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-resource.min.js',
        'angular-resource.js' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-resource.js',
        'angular-route.min.js' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-route.min.js',
        'angular-route.js' => '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-route.js',
        //
        // d3 - http://d3js.org/
        'd3.min.js' => '//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
        'd3.js' => '//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.js',

        'd3-legend.min.js' => '//cdnjs.cloudflare.com/ajax/libs/d3-legend/1.10.0/d3-legend.js',
        'd3-legend.js' => '//cdnjs.cloudflare.com/ajax/libs/d3-legend/1.10.0/d3-legend.min.js',
        //
        // c3 - http://c3js.org
        'c3.min.js' => '//cdnjs.cloudflare.com/ajax/libs/c3/0.1.29/c3.min.js',
        'c3.js' => '//cdnjs.cloudflare.com/ajax/libs/c3/0.1.29/c3.js', 
        'c3.min.css' => '//cdnjs.cloudflare.com/ajax/libs/c3/0.1.29/c3.min.css',
        'c3.css' => '//cdnjs.cloudflare.com/ajax/libs/c3/0.1.29/c3.css',
        //
        // dc - https://dc-js.github.io/dc.js/
        'dc.js' => '//cdnjs.cloudflare.com/ajax/libs/dc/1.7.5/dc.js',
        'dc.min.js' => '//cdnjs.cloudflare.com/ajax/libs/dc/1.7.5/dc.min.js',
        'dc.css' => '//cdnjs.cloudflare.com/ajax/libs/dc/1.7.5/dc.css',
        'dc.min.css' => '//cdnjs.cloudflare.com/ajax/libs/dc/1.7.5/dc.min.css',   
        //
        // dynatable - https://www.dynatable.com/
        'dynatable.js' => '//cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.js',
        'dynatable.min.js' => '//cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.min.js',
        'dynatable.css' => '//cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.css',
        'dynatable.min.css' => '//cdnjs.cloudflare.com/ajax/libs/Dynatable/0.3.1/jquery.dynatable.min.css',        
        //
        // leaflet - http://leafletjs.com/ && https://github.com/humangeo/leaflet-dvf
        'leaflet.min.js' => '//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js',
        'leaflet.js' => '//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet-src.js',
        'leaflet.css' => '//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css',
		'javascript.util.min.js' => '//cdnjs.cloudflare.com/ajax/libs/javascript.util/0.12.12/javascript.util.min.js',
		'jsts.min.js' => '//cdnjs.cloudflare.com/ajax/libs/jsts/2.0.4/jsts.min.js',
		'leaflet-dvf.js' => '//cdnjs.cloudflare.com/ajax/libs/leaflet-dvf/0.2.6/leaflet-dvf.js',
	    //
        // ol3 - http://openlayers.org/
        'ol3.min.js' => '//cdnjs.cloudflare.com/ajax/libs/ol3/3.15.1/ol.js',
        'ol3.js' => '//cdnjs.cloudflare.com/ajax/libs/ol3/3.15.1/ol-debug.js', 
        'ol3.min.css' => '//cdnjs.cloudflare.com/ajax/libs/ol3/3.15.1/ol.css',
        'ol3.css' => '//cdnjs.cloudflare.com/ajax/libs/ol3/3.15.1/ol-debug.css',
        //
        // fancytree - https://github.com/mar10/fancytree
        'fancytree.js' => '//cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.10.1/jquery.fancytree-all.js',
        'fancytree.min.js' => '//cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.10.1/jquery.fancytree-all.min.js',
        'fancytree.css' => '//cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.10.1/skin-bootstrap/ui.fancytree.css',
        'fancytree.min.css' => '//cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.10.1/skin-bootstrap/ui.fancytree.min.css',
        //
        // highlight.js - https://highlightjs.org
        'highlight.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js',
        'highlight.min.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js',
        'http.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/http.min.js',
        'http.min.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/http.min.js',   
        'json.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/json.min.js',
        'json.min.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/json.min.js',
        'xml.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/xml.min.js',
        'xml.min.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/xml.min.js',
     // 'cpp.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/cpp.min.js',
     // 'cpp.min.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/cpp.min.js',
        'r.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/r.min.js',
        'r.min.js' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/languages/r.min.js',   
        // css
        'default.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/default.min.css',
        'default.min.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/default.min.css',
        'atelier-dune-light.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/atelier-dune-light.min.css',
        'atelier-dune-light.min.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/atelier-dune-light.min.css',        
    //  'agate.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/agate.min.css',
    //  'agate.min.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/agate.min.css',
    //  'foundation.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/foundation.min.css',
    //  'foundation.min.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/foundation.min.css',        
    //  'github.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/github.min.css',
    //  'github.min.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/github.min.css',        
        'zenburn.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/zenburn.min.css',
        'zenburn.min.css' => '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/zenburn.min.css',
        //
        // hopscotch - https://github.com/linkedin/hopscotch
        'hopscotch.js' => '//cdnjs.cloudflare.com/ajax/libs/hopscotch/0.2.5/js/hopscotch.js',
        'hopscotch.min.js' => '//cdnjs.cloudflare.com/ajax/libs/hopscotch/0.2.5/js/hopscotch.min.js',  
        'hopscotch.css' => '//cdnjs.cloudflare.com/ajax/libs/hopscotch/0.2.5/css/hopscotch.css',
        'hopscotch.min.css' => '//cdnjs.cloudflare.com/ajax/libs/hopscotch/0.2.5/css/hopscotch.min.css',
        //
        // three - http://threejs.org/
        'three.js' => '//cdnjs.cloudflare.com/ajax/libs/three.js/r71/three.js',
        'three.min.js' => '//cdnjs.cloudflare.com/ajax/libs/three.js/r71/three.min.js',  
        //'TrackballControls.js' => '//mrdoob.github.com/three.js/examples/js/controls/TrackballControls.js',
        //
        // lockr - https://github.com/tsironis/lockr
        'lockr.js' => '//cdnjs.cloudflare.com/ajax/libs/lockr/0.8.4/lockr.js',
        'lockr.min.js' => '//cdnjs.cloudflare.com/ajax/libs/lockr/0.8.4/lockr.min.js',  
        //
        // tween - https://github.com/tweenjs/tween.js
        'tweenjs.js' => '//cdnjs.cloudflare.com/ajax/libs/tweenjs/0.6.1/tweenjs.min.js',
        'tweenjs.min.js' => '//cdnjs.cloudflare.com/ajax/libs/tweenjs/0.6.1/tweenjs.min.js',  
        //
        // create pdf - https://parall.ax/products/jspdf
        // see pdf - http://mozilla.github.io/pdf.js/
        'jspdf.js' => '//cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.debug.js',
        'jspdf.min.js' => '//cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js', 
        'jspdf.plugin.autotable.js' => '//cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.24/jspdf.plugin.autotable.src.js',
        'jspdf.plugin.autotable.min.js' => '//cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.0.24/jspdf.plugin.autotable.js', 
		// vue - http://threejs.org/
        'vue.js' => '//cdn.jsdelivr.net/npm/vue@2.5.22/dist/vue.js',
        'vue.min.js' => '//cdn.jsdelivr.net/npm/vue@2.5.22/dist/vue.min.js', 
		'axios.js' => '//unpkg.com/axios/dist/axios.js',
        'axios.min.js' => '//unpkg.com/axios/dist/axios.min.js', 
    /*
      //maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css
      //maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js
      //fonts.googleapis.com/css?family=Open+Sans
      //maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css
     
     */
    ],
    /*
      'converter' => [
      'class' => 'yii\web\AssetConverter',
      'commands' => [
      'less' => ['css', 'lessc {from} {to} --no-color '],
      ],
      ],
     */
];

?>