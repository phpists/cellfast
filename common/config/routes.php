<?php
return array_merge(
	[
		'/' => 'site/index',
		'about' => 'site/about',
		'contact' => 'site/contact',
		'partners' => 'site/partners',
		'register' => 'user/signup',
		'login' => 'user/signin',
		'docs' => 'docs/index',
        'search' => 'site/search',
		'password-forgot' => 'user/request-password-reset',

		'feedback/send-form' => 'feedback/send-form',
	],
	\noIT\content\helpers\ContentHelper::defaultRoutes('article'),
	\noIT\content\helpers\ContentHelper::defaultRoutes('event'),
	[
		// Комбинация товара
		[
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "products/<product>/item",
			'route' => "catalog/product-item",
			'suffix' => '.json',
			'filters' => [
				[
					'class' => 'noIT\content\components\ContentEntityUrlFilter',
					'part' => 'product',
					'entity' => 'common\models\ProductEntity',
				],
				[
					'class' => 'noIT\core\components\NotEmptyUrlFilter',
					'part'  => 'product',
				],
			],
		],
		// Комбинации товара
		[
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "products/<product>/items",
			'route' => "catalog/product-items",
			'filters' => [
				[
					'class' => 'noIT\content\components\ContentEntityUrlFilter',
					'part' => 'product',
					'entity' => 'common\models\ProductEntity',
				],
				[
					'class' => 'noIT\core\components\NotEmptyUrlFilter',
					'part'  => 'product',
				],
			],
		],
		// Карточка товара
		[
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "products/<product>",
			'route' => "catalog/product",
			'suffix' => '.html',
			'filters' => [
				[
					'class' => 'noIT\content\components\ContentEntityUrlFilter',
					'part' => 'product',
					'entity' => 'common\models\ProductEntity',
				],

				[
					'class' => 'noIT\core\components\NotEmptyUrlFilter',
					'part'  => 'product',
				],
			],
		],
		// Списковая товаров категории с фильтром
		[
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "catalog/<category>/<filter>",
			'route' => "catalog/category",
			'filters' => [
				[
					'class' => 'noIT\content\components\CheckPaginatorUrlFilter',
					'part'  => 'page',
				],
				[
					'class' => 'noIT\content\components\ContentEntityUrlFilter',
					'part' => 'category',
					'entity' => 'common\models\Category',
				],
				[
					'class' => 'noIT\feature\components\FeatureMultipleUrlFilter',
					'part'  => 'filter',
					'componentId' => 'productFeature'
				],
				[
					'class' => 'noIT\core\components\NotEmptyUrlFilter',
					'part'  => 'filter',
				],
			],
		],

		// Списковая товаров категории
		[
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "catalog/<category>",
			'route' => "catalog/category",
			'filters' => [
				[
					'class' => 'noIT\content\components\ContentEntityUrlFilter',
					'part' => 'category',
					'entity' => 'common\models\Category',
				],
			],
		],

		// Списковая категорий
		[
			'class' => 'noIT\content\components\ContentUrlRule',
			'pattern' => "catalog",
			'route' => "catalog/index",
		],
	],
	[
		'cart' => 'order/cart',
		'cart/badge' => 'order/cart-badge',
		'cart/add' => 'order/cart-add',
		'cart/remove' => 'order/cart-remove',
		'cart/update' => 'order/cart-update',
		'cart/up' => 'order/cart-up',
		'cart/down' => 'order/cart-down',
	],
	[
        'gdscalc/index' => 'gdscalc/index',
        'gdscalc/form' => 'gdscalc/form',
        'gdscalc/calc' => 'gdscalc/calc',
        'gdscalc/order' => 'gdscalc/order',
    ],
	\noIT\user\UserModule::DefaultRoutes()
);
