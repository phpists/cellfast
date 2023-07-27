<?php

use kartik\datecontrol\Module;

$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id' => 'cellfast-admin',
	'name' => 'Cell Fast CMS',
	'basePath' => dirname(__DIR__),
	'language' => 'ru-RU',
	'controllerNamespace' => 'backend\controllers',
	'bootstrap' => ['log'],
	'as beforeRequest' => [
		'class' => \yii\filters\AccessControl::className(),
		'ruleConfig' => [
			'class' => \Da\User\Filter\AccessRuleFilter::className(),
		],
		'rules' => [
			[
				'actions' => ['login', 'error'],
//			     'actions' => ['login', 'error', 'register', 'resend'],
				'allow' => true,
				'roles' => ['?'],
			],
			[
				'allow' => true,
				'roles' => ['@'],
			],
		],
	],
	'modules' => [
		'user' => [
			'class' => Da\User\Module::className(),
			'layout' => '@backend/views/layouts/main-clear',
			'administrators' => [
				'root',
			],
			'controllerMap' => [
				'admin' => 'noIT\auth\controllers\AdminController',
				'role' => 'noIT\auth\controllers\RoleController',
				'permission' => 'noIT\auth\controllers\PermissionController',
				'rule' => 'noIT\auth\controllers\RuleController',
			],
		],
		'gridview' =>  [
			'class' => '\kartik\grid\Module',
			'i18n' => [
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => '@kvgrid/messages',
				'forceTranslation' => true
			]
		],
		'tips' => [
			'class'  => 'noIT\tips\Module',
			'models' => [
				// TODO add models
			]
		],
		'redirect' => [
			'class' => \dmstr\modules\redirect\Module::className(),
		],
		'wysiwyg' => [
			'class' => \noIT\wysiwyg\WysiwygModule::className(),
			'uploadPath' => '@cdn/content',
			'uploadUrl' => '@cdnUrl/content',
		],
		'datecontrol' =>  [
			'class' => '\kartik\datecontrol\Module',
			'displaySettings' => [
				Module::FORMAT_DATE => 'dd.MM.yyyy',
				Module::FORMAT_TIME => 'hh:mm',
				Module::FORMAT_DATETIME => 'dd.MM.yyyy hh:mm',
			],
			'saveSettings' => [
				Module::FORMAT_DATE => 'php:U',
				Module::FORMAT_TIME => 'php:U',
				Module::FORMAT_DATETIME => 'php:U',
			],
			'autoWidget' => true,
			'autoWidgetSettings' => [
				Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true]],
				Module::FORMAT_DATETIME => [],
				Module::FORMAT_TIME => [],
			],
			'widgetSettings' => [
				Module::FORMAT_DATE => [
					'class' => 'yii\jui\DatePicker',
					'options' => [
						'dateFormat' => 'php:d-M-Y',
						'options' => ['class'=>'form-control'],
					]
				]
			]
		],
		'webapi' => [
			'class' => 'noIT\soap\SoapServerModule',
		]
	],
	'components' => [
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'request' => [
			'csrfParam' => '_csrf-cellfast-admin',
			'baseUrl' => '',
		],
		'user' => [
			'identityClass' => \Da\User\Model\User::className(),
			'loginUrl' => ['user/login'],
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
		],
		'session' => [
			'name' => 'cellfast-admin',
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
		'assetManager' => [
			'bundles' => [
				'yii\web\JqueryAsset' => [
					'sourcePath' => null,
					'basePath' => '@webroot',
					'baseUrl' => '@web',
					'js' => [
						'metronic-admin/vendors/base/vendors.bundle.js',
						'metronic-admin/custom/js/jquery-migrate.min.js',
						'metronic-admin/demo/default/base/scripts.bundle.js',
						'metronic-admin/components-bundle/js/bootstrap-select.js',
					]
				]
			]
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => require(__DIR__ . '/routes.php'),
		],
		'imagecache' => [
			'presets' => [
				'admin_thumb' => [
					'thumbnail' => [
						'width' => 180,
						'height' => 180,
					],
				],
				'admin_thumb_grid' => [
					'thumbnail' => [
						'width' => 60,
						'height' => 40,
					],
				],
				'admin_thumb_md' => [
					'thumbnail' => [
						'width' => 480,
						'height' => 360,
					],
				],
				'admin_thumb_lg' => [
					'thumbnail' => [
						'width' => 720,
						'height' => 640,
					],
				],
			],
		],
		'category' => [
			'class' => \common\components\CategoryComponent::className(),
			'categoryModel' => \backend\models\Category::className(),
		],
		'componentHelper' => [
			'class' => 'backend\components\ComponentHelper'
		],
		'defaultEditorAssetComponent' => [
			'class' => 'noIT\editor\content\DefaultEditorAssetComponent',
		],
	],
	'params' => $params,
];
