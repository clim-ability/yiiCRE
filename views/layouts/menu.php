 <?php
 use yii\helpers\Html;
use app\widgets\TmbMenu;
//use app\widgets\TmbLogin;
use app\modules\translation\models\Language;
use app\modules\translation\widgets\LanguageSelector;
use yii\helpers\Url;
use app\models\User;

$url = Yii::$app->request->getUrl();
$params = Yii::$app->request->getQueryParams();


$special = true;
$languages = [];
foreach (Language::getVisibleLanguages() as $lg => $label) {
    $languages[] = [
        'label' => Language::t('m:language-names', $lg, [] ,$lg),
        'url' => $url,
        'linkOptions' => ['data-method' => 'post', 'data-params' => array_merge($params, ['_lang' => $lg])],
        'active' => ($lg == Yii::$app->language),
        'activateParents' => false,
    ];
}
     
$left = 
    [
        [
            'label' => Language::t('p:menue', 'Admin'),
            'visible' => (User::hasRole('sysadmin')), 
            'items' => [
                ['label' => Language::t('p:menue', 'Manage Hazards'), 'url' => ['/hazard/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Hazards'), 'url' => ['/hazard/translate'], 'visible' => $special],
                '<li class="divider'.($special ? '' : ' hidden' ).'"></li>',
                ['label' => Language::t('p:menue', 'Manage Dangers'), 'url' => ['/danger/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Dangers'), 'url' => ['/danger/translate'], 'visible' => $special],
                '<li class="divider'.($special ? '' : ' hidden').'"></li>',
                ['label' => Language::t('p:menue', 'Manage Zones'), 'url' => ['/zone/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Zones'), 'url' => ['/zone/translate'], 'visible' => $special],  
                '<li class="divider'.($special ? '' : ' hidden').'"></li>',
                ['label' => Language::t('p:menue', 'Manage Sectors'), 'url' => ['/sector/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Sectors'), 'url' => ['/sector/translate'], 'visible' => $special],  
                '<li class="divider'.($special ? '' : ' hidden').'"></li>',
                ['label' => Language::t('p:menue', 'Manage Stations'), 'url' => ['/station/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Stations'), 'url' => ['/station/translate'], 'visible' => $special],  
                '<li class="divider'.($special ? '' : ' hidden').'"></li>',       
                ['label' => Language::t('p:menue', 'Manage Landscapes'), 'url' => ['/landscape/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Landscapes'), 'url' => ['/landscape/translate'], 'visible' => $special],  
                '<li class="divider'.($special ? '' : ' hidden').'"></li>',             
                //['label' => Language::t('p:menue', 'Manage Scenarios'), 'url' => ['/scenario/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Scenarios'), 'url' => ['/scenario/translate'], 'visible' => $special],				
            ]
        ],

        [
            'label' => Language::t('p:menue', 'Admin2'),
            'visible' => (User::hasRole('sysadmin')), 
            'items' => [
                ['label' => Language::t('p:menue', 'Manage Impacts'), 'url' => ['/risk/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Impacts'), 'url' => ['/risk/translate'], 'visible' => $special],
                '<li class="divider'.($special ? '' : ' hidden' ).'"></li>',
                ['label' => Language::t('p:menue', 'Manage Adaptions'), 'url' => ['/adaption/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Translate Adaptions'), 'url' => ['/adaption/translate'], 'visible' => $special],
            ]
        ],

        [
            'label' => Language::t('p:menue', 'Geographic Names'),
            'url' => ['/locating/name/index'],
            'visible' => false,
            'items' => [
                ['label' => Language::t('p:menue', 'Names'), 'url' => ['/locating/name/index']],
                ['label' => Language::t('p:menue', 'Location'), 'url' => ['/locating/location/index']],
                ['label' => Language::t('p:menue', 'Location types'), 'url' => ['/locating/locationtype/index']],
                ['label' => Language::t('p:menue', 'Tag groups'), 'url' => ['/locating/taggroup/index']],
                ['label' => Language::t('p:menue', 'Tags'), 'url' => ['/locating/tag/index']],
            ]
        ],
		['label' => Language::t('p:menue', 'Flyer'), 'url' => ['/site/page', 'view' => 'flyer']],
        ['label' => Language::t('p:menue', 'About'), 'url' => '/site/about'],
    ];

if (isset($this->params['menu'])) {
    $left = $this->params['menu'];
}

$menu = [
    'brand' => ['label' => Language::t('p:menue', 'Climability'), 'url' => ['/site/index']],
    'left' => $left,
    'right' => [
        [
            'label' => '',
            'url' => ['/site/page', 'view' => 'onlineHelp', '#'=> isset($this->params['help']) ? $this->params['help'] : 'toc'],
            'linkOptions' => ['class' => 'no-wait', 'id' => 'help', 'target'=>'_tmbHelp'],
            'visible' => (isset($this->params['help']) && User::hasRole('sysadmin')),
        ],
        [ 
            'label' => LanguageSelector::widget([]),
            'linkOptions' => ['class' => 'no-wait hidden']
        ],
        [
            'label' => Language::t('m:language-names', Yii::$app->language),
            'items' => $languages,
            'linkOptions' => ['class' => 'dumbly'],
            'active' =>false,
        ],
        Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
    ],
];

echo TmbMenu::widget(['menu' => $menu]);




?>
