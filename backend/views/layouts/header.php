<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Url;
?>
<header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">

            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-dark ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="/admin" class="m-brand__logo-wrapper"><?= Yii::$app->name?></a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">

                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                            <span></span>
                        </a>
                        <!-- END -->

                        <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                        <!-- END -->

                        <!-- BEGIN: Responsive Header Menu Toggler -->
                        <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>

                        <!-- END -->

                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>

                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>
            <!-- END: Brand -->

            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                <!-- BEGIN: Horizontal Menu -->
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
                    <i class="la la-close"></i>
                </button>
                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark " style="float: right; margin-right: 20px;">
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">

                        <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" aria-haspopup="true">
                            <a href="/" class="m-menu__link" target="_blank">
                                <i class="m-menu__link-icon flaticon-up-arrow"></i>
                                <span class="m-menu__link-text">На сайт</span>
                            </a>
                        </li>

                        <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" aria-haspopup="true">
                            <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                <i class="m-menu__link-icon flaticon-avatar"></i>
                                <span class="m-menu__link-text">Admin<?php // Yii::$app->user->identity->username ?></span>
                            </a>
                        </li>

                        <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" aria-haspopup="true">
                            <a href="<?= Url::to(['/site/logout'])?>" class="m-menu__link" data-method="post">
                                <i class="m-menu__link-icon flaticon-close"></i>
                                <span class="m-menu__link-text">Выйти</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- END: Horizontal Menu -->

                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                    <div class="m-stack__item m-topbar__nav-wrapper">

                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <!-- EMPTY LIST -->
                        </ul>

                    </div>
                </div>
                <!-- END: Topbar -->
            </div>

        </div>
    </div>
</header>