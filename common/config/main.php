<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \noIT\cache\components\CacheComponent::className(),
            'localCache' => [
		        'class' => \noIT\cache\components\LocalCache::className(),
	        ],
//	        'memoryCache' => [
//		        'class' => \yii\caching\MemCache::className(),
//		        'useMemcached' => true,
//		        'keyPrefix' => 'cellfast_',
//		        'servers' => [
//			        [
//				        'host' => 'localhost',
//				        'port' => 11211,
//				        'weight' => 100,
//			        ],
//		        ],
//	        ],
//	        'fileCache' => [
//	            'class' => \yii\caching\FileCache::className(),
//	        ],
        ],
//        'queryCache' => [
//	        'class' => 'yii\caching\FileCache',
//	        'useMemcached' => true,
//	        'keyPrefix' => 'cellfast_',
//	        'servers' => [
//		        [
//			        'host' => 'localhost',
//			        'port' => 11211,
//			        'weight' => 100,
//		        ],
//	        ],
//        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'feature' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@noIT/modules/feature/messages',
                ],
            ],
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
        ],
        'projects' => [
            'class' => \common\components\projects\Projects::className(),
            'projects' => [
                'cellfast',
                'bryza',
                'ines',
            ]
        ],
	    'cart' => [
	    	'class' => \common\models\Cart::className(),
		    'cookieName' => 'cart',
	    ],
        'languages' => [
            'class' => \common\components\language\Language::className(),
            'languageModel' => \common\components\language\LanguageModel::className(),
            'languages' => [
                'ru-RU' => [
                    'url' => 'ru',
                    'name' => 'Русский',
                    'short' => 'Рус',
                ],
                'uk-UA' => [
                    'url' => 'ua',
                    'name' => 'Українська',
                    'short' => 'Укр',
                ],
            ],
            'default' => 'ru-RU',
        ],

        'imagecache' => [
	        'class' => \noIT\imagecache\components\Imagecache::className(),
	        'driver' => 'GD',
	        'rootPath' => '@cdn',
	        'rootUrl' => '@cdnUrl',
	        'presets' => [
	        	'entity_image_gallery' => [
			        'thumbnail' => [
				        'width' => 180,
				        'height' => 180,
			        ],
		        ],
	        	'content_cover_list' => [
			        'thumbnail' => [
				        'width' => 555,
				        'height' => 340,
				        'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
			        ],
		        ],
	        	'fullsize' => [
			        'thumbnail' => [
				        'width' => 1110,
				        'height' => 910,
			        ],
		        ],
	        	'content_cover' => [
			        'thumbnail' => [
				        'width' => 1110,
				        'height' => 440,
				        'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
			        ],
		        ],
		        'product_list_cover' => [
			        'thumbnail' => [
				        'width' => 209,
				        'height' => 147,
			        ],
		        ],
		        'related_static' => [
			        'thumbnail' => [
				        'width' => 350,
				        'height' => 290,
				        'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
			        ],
		        ],
		        'related_slider' => [
			        'thumbnail' => [
				        'width' => 220,
				        'height' => 300,
				        'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
			        ],
		        ],
	        ],
        ],
        'settings' => [
	        'class' => 'pheme\settings\components\Settings',
        ],
        'emailSettingsComponent' => [
	        'class' => 'common\components\EmailSettingsComponent'
        ],
    ],
    'modules' => [ ],
];
