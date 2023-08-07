<?php
$params = array_merge(
	require( __DIR__ . '/../../common/config/params.php' ),
	require( __DIR__ . '/../../common/config/params-local.php' ),
	require( __DIR__ . '/params.php' ),
	require( __DIR__ . '/params-local.php' )
);
return [
    'id' => 'ines',
    'name' => 'Ines Ukraine',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'uk-UA',
    'controllerNamespace' => 'ines\controllers',
    'on beforeRequest' => function () {
	    \noIT\seo\helpers\RedirectHelper::beforeRequest();
    },
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-ines',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'ines\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-ines', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the ines
            'name' => 'ines',
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
            'class' => \ines\components\GdsCalcDrain::className(),
            'alias' => 'gdscalcDrain',
            'type_id' => 110, // Drain systems Ines
            'feature_type' => 126, // Водосток Ines - Тип для калькулятора
            'feature_type_options' => [
                'gutter' => 761,
                'pipe' => 762,
                'gutter_connector' => 763,
                'gutter_corner_connector_inner' => 764,
                'gutter_corner_connector_outer' => 764,
                'gutter_dummy_left' => 766,
//                'gutter_dummy_right' => 766,
                'pipe_connector' => 768,
                'pipe_knee' => 775,
                'gutter_gully' => 769,
                // крепление желоба - зависит от типа крепления
                'gutter_bracket' => [
                    '0' => 770, // Прямое крепление
//                    2 => 772, // Крепление сверху
//                    3 => 771, // Крепление сбоку
                ],
                'pipe_bracket' => 773,
                'pipe_bracket_hook' => 774
            ],

            'pipe_bracket_hook' => [
                0 => '70-671', // в расчете используем крюк хомута 120 мм (артикул 70-004)
                40 => '70-672', // в расчете используем крюк хомута 160 мм (артикул 70-005)
                60 => '70-673', // в расчете используем крюк хомута 180 мм (артикул 70-006)
                100 => '70-674', // в расчете используем крюк хомута 220 мм (артикул 70-010)
                130 => '70-675', // в расчете используем крюк хомута 250 мм (артикул 70-019)
            ],
//            'gutter_length' => 3, // Длины желоба для расчета
//            'pipe_length' => 3, // Длины труб для расчета
            'feature_system' => 127,
            // Рассчетные площади систем для расчета количества воронок
            // feature_value_id => [площадь] м2
            'feature_system_calc_areas' => [
                'default' => 120,
            ],
            'feature_color' => 121,
            'feature_color_options' => [
                720 => '#fff', // Белый (RAL 9010)
                721 => '#783f04', // Коричневый (RAL 8017)
                723 => '#fce5cd', // Бежевый (RAL 1001)
                776 => '#666666', // Серый (RAL 7040)
            ],
//            'feature_length' => 124, // Длина Bryza
            'component_labels' => [
                'gutter' => 'Желоб (3 м)',
                'pipe' => 'Труба (3 м)',
                'gutter_connector' => 'Соединитель желоба',
                'gutter_corner_connector_outer' => 'Угловой соединитель желоба внешний',
                'gutter_corner_connector_inner' => 'Угловой соединитель желоба внутренний',
                'gutter_dummy_left' => 'Заглушка желоба',
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
