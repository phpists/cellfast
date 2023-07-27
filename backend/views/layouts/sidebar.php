<?php

use \backend\widgets\MetronicSidebar;
use yii\helpers\Html;

?>
<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>

<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">

        <!-- SIDEBAR START -->
		<?= MetronicSidebar::widget([
				'items' => [
					[
						'label' => 'Обратная связь',
						'template' => 'submenu',
						'icon' => 'flaticon-tool',
						'url' => 'javascript:;',
						'items' => [
							[
								'label' => 'Заказы',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/order/index'],
							],
							[
								'label' => 'Запросы с сайта',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/feedback'],
							],

						],
					],

					[
						'label' => 'Каталог',
						'template' => 'submenu',
						'icon' => 'flaticon-tool',
						'url' => 'javascript:;',
						'items' => [

							[
								'label' => Yii::t('app', 'Categories'),
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/category/index'],
							],
							[
								'label' => 'Товары',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/product/index'],
							],
							[
								'label' => 'Типы товаров',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/product-type/index'],
							],
							[
								'label' => 'Характеристики',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/product-feature/index'],
							],
							[
								'label' => 'Импорт товаров',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/import'],
							],


						],
					],
					[
						'label' => 'Контент',
						'template' => 'submenu',
						'icon' => 'flaticon-tool',
						'url' => 'javascript:;',
						'items' => [
							[
								'label' => 'Статьи',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/article'],
							],
							[
								'label' => 'События',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/event'],
							],
							[
								'label' => 'Документы для скачивания',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/document'],
							],

						]
					],
					[
						'label' => 'Настройки',
						'template' => 'submenu',
						'icon' => 'flaticon-tool',
						'url' => 'javascript:;',
						'items' => [
							[
								'label' => 'Баннеры',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/banner'],
							],
							[
								'label' => 'О нас (Главная)',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/about-main-page'],
							],
							[
								'label' => 'О компании',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/about-us'],
							],
							[
								'label' => 'Контакты стр.',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/settings/contacts-page'],
							],
							[
								'label' => 'Типы цен',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/price-type/index'],
							],
							[
								'label' => 'Типы упаковки',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/package/index'],
							],
							[
								'label' => 'Места хранения',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/warehouse/index'],
							],
							[
								'label' => 'Статусы заказов',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/order-status/index'],
							],
							[
								'label' => 'Способы доставки',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/delivery/index'],
							],
							[
								'label' => 'Где купить',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/partner/index'],
							],
							[
								'label' => 'Способы оплаты',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/payment/index'],
							],
							[
								'label' => 'Регионы',
								'template' => 'submenu',
								'icon' => 'flaticon-tool',
								'url' => 'javascript:;',
								'items' => [
									[
										'label' => 'Страны',
										'template' => 'single',
										'icon' => 'flaticon-tool',
										'url' => ['/location-country/index'],
									],
									[
										'label' => 'Области',
										'template' => 'single',
										'icon' => 'flaticon-tool',
										'url' => ['/location-region/index'],
									],
									[
										'label' => 'Города',
										'template' => 'single',
										'icon' => 'flaticon-tool',
										'url' => ['/location-place/index'],
									],
								]
							],
							[
								'label' => 'E-mail',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/settings/email'],
							],

						],
					],
					[
						'label' => 'Администрирование',
						'icon' => 'fa fa-cog',
						'template' => 'submenu',
						'url' => ['#'],
						'items' => [
							[
								'label' => 'Управление пользователями',
								'icon' => 'fa fa-user`',
								'url' => ['/user/admin/index'],
							],
							[
								'label' => 'Подсказки в админке',
								'url' => ['/tips/tip'],
							],
						]
					],
					[
						'label' => '1C',
						'template' => 'submenu',
						'icon' => 'flaticon-tool',
						'url' => 'javascript:;',
						'items' => [
							[
								'label' => 'Связывание товаров',
								'template' => 'single',
								'icon' => 'flaticon-tool',
								'url' => ['/e1c-binding/index'],
							],
						]
					],
				]
			]
		) ?>
        <!-- SIDEBAR END -->

    </div>
    <!-- END: Aside Menu -->

</div>
<!-- END: Left Aside -->
