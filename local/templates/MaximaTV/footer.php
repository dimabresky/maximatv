<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use Maxima\Helpers\PageHelper;
use Maxima\Helpers\TariffHelper;
?>
        <?
        global $USER;
        if ($_COOKIE['_maxima_wait_info_banner'] != "Y"):

            $showBanner = true;
            $hlTariff = TariffHelper::getUserCurrentTariff($USER->GetID());
            if(!empty($hlTariff['UF_TARIFF_ID']) && $USER->IsAuthorized()) {
                $currentTariff = TariffHelper::getTariffById($hlTariff['UF_TARIFF_ID']);
                if(((int)$currentTariff['PROPERTY_PRICE_VALUE']) > 0) {
                    $showBanner = false;
                }
            }
            ?>
            <? if($showBanner): ?>
                <div class="info-banner js-info-banner" data-show-after="1500">
                    <form class="g-wrap">
                        <p>Купи подписку и смотри записи выступлений</p>
                        <a href="javascript:void(0)"  class="i-button i-button_light i-button_white js-later">Позже</a>
                        <a href="/#subscription_main_page_block" type="button" class="i-button i-button_red js-buy">Купить</a>
                    </form>
                    <a href="javascript:void(0)" class="info-banner__close js-close"></a>
                </div>
            <? endif ?>
        <?  endif; ?>
    </div>
    <footer class="footer">
        <div class="footer__row footer__row_top">
            <div class="g-wrap">
                <div class="footer__nav">
                    <div class="footer__nav-wrap">

                        <div class="footer__nav-item footer__nav-item--main"><a href="#" class="footer__nav-link footer__nav-link--main"></a></div>

                        <div class="footer__nav-item"><a href="https://maximaequisport.ru" class="footer__nav-link">Maxima Equisport</a></div>

                        <div class="footer__nav-item"><a href="https://www.maximavet.ru" class="footer__nav-link">Maxima Vet</a></div>
                        <div class="footer__nav-item"><a href="https://www.maximastables.ru" class="footer__nav-link">Maxima Stables</a></div>

                        <div class="footer__nav-item"></div>
                        <div class="footer__nav-item"><a href="http://maximapark.ru" class="footer__nav-link">Maxima Park</a></div>

                    </div>
                    <div class="footer__social social social_footer">
                        <?$APPLICATION->IncludeComponent("mxm:social.list", "footer", []);?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__row footer__row_bottom">
            <?$APPLICATION->IncludeComponent('mxm:site.info','footer',[])?>
        </div>
    </footer>
    <div class="info-message js-info-message" data-pause="1500">
        Добавлена в корзину
    </div>
</div>

<div data-remodal-id="login_register" data-remodal-options="hashTracking: false" class="remodal modal modal_slim modal_login">
    <a data-remodal-action="close" class="modal__close"></a>
    <div class="modal__content form-login">
        <div class="form-login__wrap tabs tabs_light js-tabs">
            <div class="form-login__header tabs__header">
                <a href="javascript:void(0)" class="tabs__link js-tabs-link" data-group="enter">Вход</a>
                <a href="javascript:void(0)" class="tabs__link js-tabs-link" data-group="registration">Регистрация</a>
            </div>
            <div class="tabs__content js-tabs-content" data-group="enter">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:system.auth.form",
                    "maxima-tv",
                    Array(
                        "REGISTER_URL" => "",
                        "FORGOT_PASSWORD_URL" => "",
                        "PROFILE_URL" => "/lk/subscribe/",
                        "SHOW_ERRORS" => "Y",
                        "AJAX_MODE" => "Y",
                        "backurl" => (PageHelper::isBroadcastDetail() || PageHelper::isPhotoPage() ) ? $APPLICATION->GetCurDir() : "",
                    )
                );?>
            </div>
            <div class="tabs__content js-tabs-content" data-group="registration">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.register",
                    "maxima-tv",
                    Array(
                        "AUTH" => "Y",
                        "REQUIRED_FIELDS" => array("NAME","LAST_NAME"),
                        "SET_TITLE" => "N",
                        "SHOW_FIELDS" => array("NAME","LAST_NAME"),
                        "SUCCESS_PAGE" => "",
                        "USER_PROPERTY" => array(),
                        "USER_PROPERTY_NAME" => "",
                        "USE_BACKURL" => "N",
                        "AJAX_MODE" => "Y",
                        "backurl" => PageHelper::isBroadcastDetail() ? $APPLICATION->GetCurDir() : "/lk/",
                    )
                );?>
            </div>
        </div>
    </div>
</div>

<?php
global $APPLICATION;
$asset = Bitrix\Main\Page\Asset::getInstance();

$asset->addJs(ASSETS_PATH_LIBS_JS . 'jquery-validate/jquery.validate.1.15.0.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'inputmask/jquery.inputmask.3.3.7.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'jquery.fancybox/jquery.fancybox.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'chosen/chosen.jquery.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'jquery.nicescroll.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'slick-slider/slick.min.1.8.1.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'swiper/swiper.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'air-datepicker/datepicker.min.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'remodal/remodal.min.1.1.1.js');
$asset->addJs(ASSETS_PATH_LIBS_JS . 'mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js');
$asset->addJs(ASSETS_PATH_JS  . 'main.js');
$asset->addJs(SITE_TEMPLATE_PATH . '/js/custom.js');

$APPLICATION->ShowHeadScripts();
?>
<!-- check auntification -->
<div id="warning"></div>
<style>
	#warning {
    background-color: black;
	opacity:0.8;
	top:0;
	left:0;
	right:0;
	bottom:0;
    display: none;
    position: fixed;
	z-index: 10000;}
</style>

<script>
setInterval(check, 30000);
check();
function check() {
	$.get('/auth/check.php', function(data){
		if (data=="EXIT") {
			if ($("#MaximaTV-video_html5_api").length>0){      //если на странице плеер
				$("#MaximaTV-video_html5_api").get(0).pause(); //останавливаем его 
				$("#MaximaTV-video_html5_api").fadeOut();		//и скрываем
			};
			$("#warning").fadeIn();
			setTimeout('alert("Выполнен вход в систему с другого устройства, Не допускается одновременная авторизация более чем с одного устройства");',500);
			window.setTimeout("document.location = 'https://maximatv.ru/';", 550);
		};
	});
};
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(53936563, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KZ5MWKB"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php include_once("cookie-warning.php"); ?>
</body>
</html>