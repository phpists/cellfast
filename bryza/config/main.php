<?php
$params = array_merge(
	require( __DIR__ . '/../../common/config/params.php' ),
	require( __DIR__ . '/../../common/config/params-local.php' ),
	require( __DIR__ . '/params.php' ),
	require( __DIR__ . '/params-local.php' )
);
return [
    'id' => 'bryza',
    'name' => 'BRYZA',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'controllerNamespace' => 'bryza\controllers',
    'on beforeRequest' => function () {
	    \noIT\seo\helpers\RedirectHelper::beforeRequest();
    },
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-bryza',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'bryza\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-bryza', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'bryza',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
	        'class' => 'yii\web\AssetManager',
	        'bundles' => [
		        'yii\web\JqueryAsset' => [
			        'js' => [
				        YII_ENV_DEV ? '/js/libs/jquery-1.9.1.js' : '/js/libs/jquery-1.9.1.js'
			        ]
		        ],
	        ],
        ],
        'urlManager' => [
        	'class' => 'noIT\core\components\CoreUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'normalizer' => [
                'class' => \yii\web\UrlNormalizer::className(),
            ],
            'languages' => [
                'ru' => 'ru-RU',
                'ua' => 'uk-UA',
            ],
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'category' => [
            'class' => \common\components\CategoryComponent::className(),
            'categoryModel' => \common\models\Category::className(),
        ],
        'productFeature' => [
            'class' => \noIT\feature\components\FeatureComponent::className(),
            'groupModel' => \common\models\ProductFeatureEntity::className(),
            'valueModel' => \common\models\ProductFeatureValueEntity::className(),
	        'groupFieldName' => 'feature_id',
        ],
        'formatter' => [
	        'dateFormat' => 'dd/MM/yyyy',
	        'datetimeFormat' => 'dd/MM/yyyy H:i',
	        'decimalSeparator' => ',',
	        'thousandSeparator' => ' ',
        ],
        'gdscalcDrain' => [
            'class' => 'common\components\GdsCalc\components\GdsCalcDrain',
            'alias' => 'gdscalcDrain',
            'type_id' => 103, // Drain systems
            'feature_type' => 125,
            'feature_type_options' => [
                'gutter' => 746,
                'pipe' => 747,
                'gutter_connector' => 748,
                'gutter_corner_connector_inner' => 749,
                'gutter_corner_connector_outer' => 755,
                'gutter_dummy_left' => 756,
                'gutter_dummy_right' => 750,
                'pipe_connector' => 751,
                'pipe_knee' => 760,
                'gutter_gully' => 752,
                // крепление желоба - зависит от типа крепления
                'gutter_bracket' => [
                    1 => 753, // Прямое крепление
                    2 => 759, // Крепление сверху
                    3 => 758, // Крепление сбоку
                ],
                'pipe_bracket' => 754,
                'pipe_bracket_hook' => 757,
            ],

            'pipe_bracket_hook' => [
                0 => '70-004', // в расчете используем крюк хомута 120 мм (артикул 70-004)
                40 => '70-005', // в расчете используем крюк хомута 160 мм (артикул 70-005)
                60 => '70-006', // в расчете используем крюк хомута 180 мм (артикул 70-006)
                100 => '70-010', // в расчете используем крюк хомута 220 мм (артикул 70-010)
                130 => '70-019', // в расчете используем крюк хомута 250 мм (артикул 70-019)
            ],
//            'gutter_length' => 3, // Длины желоба для расчета
//            'pipe_length' => 3, // Длины труб для расчета
            'feature_system' => 123,
            // Рассчетные площади систем для расчета количества воронок
            // feature_value_id => [площадь] м2
            'feature_system_calc_areas' => [
                75 => 50,
                100 => 80,
                125 => 110,
                150 => 150,
            ],
            'feature_color' => 98,
            'feature_color_options' => [
                561 => '#fff', // Белый (RAL 9010)
                562 => '#783f04', // Коричневый (RAL 8017)
                563 => '#cc0000', // Красный (RAL 3011)
                564 => '#434343', // Графит (RAL 7021)
                565 => '#274e13', // Зеленый (RAL 6020)
                566 => '#660000', // Кирпичный (RAL 8004)
                567 => '#000000', // Черный (RAL 9005)
                568 => '#a61c00', // Медный
                731 => '#666666', // Серый
//                732 => '#ffffff', // -
            ],
            'feature_length' => 124, // Длина Bryza
            'component_labels' => [
                'gutter' => 'Желоб (3 м)',
                'pipe' => 'Труба (3 м)',
                'gutter_connector' => 'Соединитель желоба',
                'gutter_corner_connector_outer' => 'Угловой соединитель желоба внешний',
                'gutter_corner_connector_inner' => 'Угловой соединитель желоба внутренний',
                'gutter_dummy_left' => 'Заглушка желоба левая',
                'gutter_dummy_right' => 'Заглушка желоба правая',
                'pipe_connector' => 'Соединитель трубы',
                'pipe_knee' => 'Колено трубы',
                'gutter_gully' => 'Ливнеприемник',
                'gutter_bracket' => 'Крепление желоба',
                'pipe_bracket' => 'Хомут трубы',
                'pipe_bracket_hook' => 'Крюк хомута трубы',
            ],
            'models' => [
                [
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseA::className(),
                ],
                [
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseB::className(),
                ],
                /*[
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseC::className(),
                ],*/
                /*[
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseD::className(),
                ],*/
                /*[
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseE::className(),
                ],*/
                [
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseF::className(),
                ],
                /*[
                    'class' => \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseG::className(),
                ],*/
            ],
        ],
    ],
    'modules' => [
        'webapi' => [
            'class' => 'noIT\soap\SoapServerModule',
        ],
    ],
    'params' => $params,
];
