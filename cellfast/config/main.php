<?php
$params = array_merge(
	require( __DIR__ . '/../../common/config/params.php' ),
	require( __DIR__ . '/../../common/config/params-local.php' ),
	require( __DIR__ . '/params.php' ),
	require( __DIR__ . '/params-local.php' )
);
return [
    'id' => 'cellfast',
    'name' => 'Cell Fast Ukraine',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'uk-UA',
    'controllerNamespace' => 'cellfast\controllers',
    'on beforeRequest' => function () {
	    \noIT\seo\helpers\RedirectHelper::beforeRequest();
    },
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-cellfast',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'cellfast\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-cellfast', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'cellfast',
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
	        'decimalSeparator' => '.',
	        'thousandSeparator' => ' ',
        ],
    ],
    'modules' => [
        'webapi' => [
            'class' => 'noIT\soap\SoapServerModule',
        ],
    ],
    'params' => $params,
];
