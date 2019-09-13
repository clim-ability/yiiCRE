 <?php
use app\widgets\TmbMenu;
//use app\widgets\TmbLogin;
use app\modules\translation\models\Language;
use app\modules\translation\widgets\LanguageSelector;
use yii\helpers\Url;
use app\models\User;

$url = Yii::$app->request->getUrl();
$params = Yii::$app->request->getQueryParams();


$special = (User::current() && User::current()->special);
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
            'visible' => (\Yii::$app->user->can('sysadmin')),
            'items' => [
                ['label' => Language::t('p:menue', 'Add user'), 'url' => ['/user/user/create'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Manage user'), 'url' => ['/user/user/index'], 'visible' => $special],
                '<li class="divider'.($special ? '' : ' hidden' ).'"></li>',
                ['label' => Language::t('p:menue', 'List Projects'), 'url' => ['/grouping/project/list'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Manage Projects'), 'url' => ['/grouping/project/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Create Project'), 'url' => ['/grouping/project/create'], 'visible' => $special],
                '<li class="divider'.($special ? '' : ' hidden'). '"></li>',
                ['label' => Language::t('p:menue', 'Manage Searches'), 'url' => ['/mysearch/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Manage Collection'), 'url' => ['/grouping/collection/index'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Manage Result'), 'url' => ['/result/index'], 'visible' => $special],
                '<li class="divider'.($special ? '' : ' hidden').'"></li>',
                ['label' => Language::t('p:menue', 'Manage Coding'), 'url' => ['/coding/frontend/manage']],
                ['label' => Language::t('p:menue', 'Test Coding'), 'url' => ['/grouping/event/code'], 'visible' => $special],
                ['label' => Language::t('p:menue', 'Manage Units'), 'url' => ['/coding/unitui/index']],
                ['label' => Language::t('p:menue', 'Manage Scales'), 'url' => ['/coding/scaleui/index']],
                ['label' => Language::t('p:menue', 'Manage Metrics'), 'url' => ['/coding/metricui/index']],                
            ]
        ],

        [
            'label' => Language::t('p:menue', 'Geographic Names'),
            'url' => ['/locating/name/index'],
            'visible' => (\Yii::$app->user->can('sysadmin')),
            'items' => [
                ['label' => Language::t('p:menue', 'Names'), 'url' => ['/locating/name/index']],
                ['label' => Language::t('p:menue', 'Location'), 'url' => ['/locating/location/index']],
                ['label' => Language::t('p:menue', 'Location types'), 'url' => ['/locating/locationtype/index']],
                ['label' => Language::t('p:menue', 'Tag groups'), 'url' => ['/locating/taggroup/index']],
                ['label' => Language::t('p:menue', 'Tags'), 'url' => ['/locating/tag/index']],
            ]
        ],
        ['label' => Language::t('p:menue', 'About'), 'url' => ['/site/page', 'view' => 'about']],
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
            'visible' => (isset($this->params['help']) && \Yii::$app->user->can('sysadmin')),
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
        \Yii::$app->user->isGuest ?
            [
                'label' => Language::t('p:menue', 'Login'),
                'url' => '/site/login',

            ]
            : // noGuest
            [
                'label' => '<span class="glyphicon glyphicon-user"></span>',
                'url' => ['/user/admin/update-profile', 'id' => Yii::$app->user->id],
                'items' => [
                    [   'visible' => false, 
                        'label' => Language::t('p:menue', 'Profile'),
                        'url' => '#',
                        'linkOptions' => ['class' => 'modalItem', 'data-title' => Language::t('p:menue', 'Profile'),
                            'data-url'=> \Yii::$app->urlManager->createUrl(["/user/user/update-user", 'id' => Yii::$app->user->id]),
                            'data-url-succ' => Url::current(),
                    ]],
                    [
                        'label' => Language::t('p:menue', 'Logout {username}',
                            ['username' => Yii::$app->user->identity->username]),
                        'url' => ['/user/security/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
                ]
            ], // end isGuest
    ],
];

echo TmbMenu::widget(['menu' => $menu]);




?>
