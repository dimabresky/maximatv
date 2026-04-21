<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Maxima\Helpers\PageHelper;

global $APPLICATION;
global $USER;
$asset = Bitrix\Main\Page\Asset::getInstance();

$additionalMainDivClass = '';
$additionalGlobalClass = '';
$isShowBreadcrumbs = false;

if (PageHelper::isBroadcastDetail()) {
    $additionalMainDivClass = 'event';
}
if (PageHelper::isCompetitionDetail()) {
    if (isset($_REQUEST['foto']) && htmlspecialchars($_REQUEST['foto']) === 'Y') {
        $additionalMainDivClass = 'photo';
    } else {
        $additionalMainDivClass = 'event';
        $isShowBreadcrumbs = true;
    }
}
if (PageHelper::isEventDetail()) {
    $additionalMainDivClass = 'event';
    $isShowBreadcrumbs = true;
}
if (PageHelper::isPhotoDetail()) {
    $additionalMainDivClass = 'photo-archive';
}
if(PageHelper::isPhotoPage()){
    $additionalMainDivClass = 'photo';
    $isShowBreadcrumbs = false;
}

if (PageHelper::isLkSection() || PageHelper::isAuthSection()) {
    $additionalMainDivClass = 'lk';
    $additionalGlobalClass = 'l-page_lk';
}
if(PageHelper::isSearchSection()){
    $additionalMainDivClass = 'program-page';
}
if(PageHelper::isCartPage()){
    $additionalMainDivClass = 'cart';
    $additionalGlobalClass = '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="/favicon.png" type="image/png"/>
    <link rel="apple-touch-icon" href="/favicon.png" type="image/png"/>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET;?>">
	<?php
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'remodal/remodal.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'remodal/remodal-default-theme.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'slick-slider/slick.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'slick-slider/slick-theme.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'chosen/chosen.min.css');
        $asset->addCss(ASSETS_PATH_LIBS_JS . 'jquery.fancybox/jquery.fancybox.min.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'swiper/swiper.min.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'air-datepicker/datepicker.min.css');
		$asset->addCss(ASSETS_PATH_LIBS_JS . 'mCustomScrollbar/jquery.mCustomScrollbar.css');
		$asset->addCss(ASSETS_PATH_CSS . 'style.css');
		$asset->addCss('/local/templates/MaximaTV/css/custom.css');
    	//$asset->addJs(ASSETS_PATH_LIBS_JS . 'jquery-1.12.3.min.js');
	?>
    <script src="/local/markup/dist/libs/jquery-1.12.3.min.js"></script>
    <?php
        //$APPLICATION->ShowHead();
        $APPLICATION->ShowCSS();
        $APPLICATION->ShowHeadStrings();
        $APPLICATION->ShowMeta('robots');
        $APPLICATION->ShowMeta('keywords');
        $APPLICATION->ShowMeta('description');
    ?>
    <title><?$APPLICATION->ShowTitle();?></title>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1779909142148130');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1779909142148130&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
    <!-- VK Pixel Code -->
    <script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?162",t.onload=function(){VK.Retargeting.Init("VK-RTRG-426897-1QD2A"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-426897-1QD2A" style="position:fixed; left:-999px;" alt=""/></noscript>
    <!-- End VK Pixel Code -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KZ5MWKB');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
    <div class="l-page <?=$additionalGlobalClass?>">
        <header class="header">
            <div class="header__wrap">
                <a href="/" class="header__logo"></a>
                <div class="menu js-menu">
                    <div class="menu__button js-menu-btn" tabindex="0">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </div>
                    <div class="menu__content js-menu-content" id="nav-content" tabindex="0">
                        <ul class="menu__list">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "top",
                                Array(
                                    "ROOT_MENU_TYPE" => "top",
                                    "MAX_LEVEL" => "1",
                                    "CHILD_MENU_TYPE" => "top",
                                    "USE_EXT" => "N",
                                    "DELAY" => "A",
                                    "ALLOW_MULTI_SELECT" => "Y",
                                    "MENU_CACHE_TYPE" => "Y",
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "MENU_CACHE_GET_VARS" => "",
                                )
                            );?>
                            <?php if ($USER->IsAuthorized()) { ?>
                                <li><a href="/lk/" class="menu__link">Личный кабинет</a></li>
                            <?php } else { ?>
                                <li><a href="javascript:void(0);" class="menu__link" data-remodal-target="login_register">Личный кабинет</a></li>
                            <?php } ?>
                            <li class="menu__contacts">
                                <?$APPLICATION->IncludeComponent('mxm:site.info','menu',[])?>
                            </li>
                            <li class="menu__nav">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:menu",
                                    "bottom",
                                    Array(
                                        "ROOT_MENU_TYPE" => "bottom",
                                        "MAX_LEVEL" => "1",
                                        "CHILD_MENU_TYPE" => "bottom",
                                        "USE_EXT" => "N",
                                        "DELAY" => "A",
                                        "ALLOW_MULTI_SELECT" => "Y",
                                        "MENU_CACHE_TYPE" => "Y",
                                        "MENU_CACHE_TIME" => "3600",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "MENU_CACHE_GET_VARS" => "",
                                    )
                                );?>
                            </li>
                            <li class="menu__social social">
                                <?$APPLICATION->IncludeComponent("mxm:social.list", "footer", []);?>
                            </li>
                        </ul>

                    </div>
                </div>
                <?$APPLICATION->IncludeComponent(
                    "mxm:search.form",
                    "",
                    array(
                    )

                );?>
                <?$APPLICATION->IncludeComponent(
                    "mxm:user.profile.header",
                    "",
                    Array(
                    )
                );?>
            </div>
        </header>
        <?php if(!PageHelper::isCartPage()) { ?>
            <? $APPLICATION->IncludeComponent(
                "mxm:basket.list.header",
                "",
                Array(
                )
            ); ?>
        <?php } ?>
        <div class="l-main <?=$additionalMainDivClass?>">
            <?php if ($isShowBreadcrumbs) { ?>
                <div class="g-wrap">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "breadcrumbs",
                        Array(
                            "PATH"       => "",
                            "SITE_ID"    => "s1",
                            "START_FROM" => "0",
                        )
                    );?>
                </div>
            <?php } ?>
            <?php if ($additionalMainDivClass == 'lk') { ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "lk",
                    Array(
                        "ROOT_MENU_TYPE" => "left",
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "N",
                        "DELAY" => "A",
                        "ALLOW_MULTI_SELECT" => "Y",
                        "MENU_CACHE_TYPE" => "Y",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => "",
                    )
                );?>
            <?php } ?>
