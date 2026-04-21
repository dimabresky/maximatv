<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

global $USER;
?>
<?$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"",
	Array(
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
		"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"USE_SHARE" => $arParams["USE_SHARE"],
		"SHARE_HIDE" => $arParams["SHARE_HIDE"],
		"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
		"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
		"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
		"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
		'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
        "IS_AUTH" => $USER->IsAuthorized(),
        'ACCESS_FILTER' => \Maxima\Helpers\TariffHelper::getUserCurrentTariffFilter($USER->GetID(), 'broadcasts'),
        'BROADCAST_ACCESS' => \Maxima\Helpers\CommonHelper::getBroadcastAccess($USER->GetID(), \Maxima\Helpers\CommonHelper::getBroadcastByCode($arResult['VARIABLES']['ELEMENT_CODE'])['ID']),
	),
	$component
);?>

<?/*!-- Программа / Мероприятие -->
<section class="i-section event__others">
    <div class="g-wrap">
        <div class="i-section__content card-list">
            <div class="card-list__content card-list__content_center">
                <div class="card-list__header">
                    <p class="card-list__header-name">Программа</p>
                    <p class="card-list__header-name">Мероприятие</p>
                </div>
                <div class="card card_shadow card_half">
                    <div class="card__row-top">
                        <div class="card__theme">
                            <p class="card__theme-title">дисциплина:</p>
                            <p class="card__theme-name">Троеборье</p>
                        </div>
                        <p class="card__date">13.11.2018 - 18.11.2018</p>
                    </div>
                    <div class="card__preview">
                        <a href="javascript:void(0)" class="card__preview-link"></a>
                        <img src="/local/markup/dist/images/broadcast/broadcast-event1.png" class="card__img" alt=""/>
                    </div>
                    <div class="card__content">
                        <div class="card__header">
                            <p class="card__address">Россия, Оренбург</p>
                        </div>
                        <a href="javascript:void(0);" class="card__title">6-8 декабря 2018: Кубок Армении: Кубок памяти
                            Шарля Азнавура. CSI-1*\J\Ch. Призовой фонд - 500 000 рублей!</a>
                    </div>
                </div>
                <div class="card card_shadow card_half">
                    <div class="card__row-top">
                        <div class="card__theme">
                            <p class="card__theme-title">дисциплина:</p>
                            <p class="card__theme-name">Конкур</p>
                        </div>
                        <p class="card__date">05.09.2018 - 09.09.2018</p>
                    </div>
                    <div class="card__preview">
                        <a href="javascript:void(0)" class="card__preview-link"></a>
                        <img src="/local/markup/dist/images/broadcast/broadcast-event2.png" class="card__img" alt=""/>
                    </div>
                    <div class="card__content">
                        <div class="card__header">
                            <p class="card__address">Россия, Краснодар</p>
                        </div>
                        <a href="javascript:void(0);" class="card__title">Международные соревнования по конкуру CSI-2*
                            YH/J/Am</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section*/?>

<!-- Другие этапы соревнования -->
<?php
    global $arrBroadcastsDetailList;

    $strToday = date('Y-m-d H:i:s');
    $arrBroadcastsDetailList = [
        '!ID' => $ElementID,
        '<=PROPERTY_LIVE_FROM' => $strToday,
        '>=PROPERTY_LIVE_TO' => $strToday,
    ];
?>

<?$APPLICATION->IncludeComponent("bitrix:news.list", "broadcasts_now_casting", Array(
    "IBLOCK_TYPE" => 'main_content',
    "IBLOCK_ID" => 4,
    "NEWS_COUNT" => 3,
    "SORT_BY1" => 'ACTIVE_FROM',
    "SORT_ORDER1" => 'DESC',
    "SORT_BY2" => 'ID',
    "SORT_ORDER2" => 'DESC',
    "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
    "PROPERTY_CODE" => ['DISCIPLINE', 'STATUS', 'LIVE_FROM', 'LIVE_TO', ''],
    "DETAIL_URL" => '/broadcasts/#ELEMENT_CODE#/',
    "SECTION_URL" => '/broadcasts/',
    "IBLOCK_URL" => '/broadcasts/',
    "DISPLAY_PANEL" => NULL,
    "SET_TITLE" => 'N',
    "SET_LAST_MODIFIED" => 'N',
    "MESSAGE_404" => '',
    "SET_STATUS_404" => 'N',
    "SHOW_404" => 'N',
    "FILE_404" => '',
    "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
    "CACHE_TYPE" => 'A',
    "CACHE_TIME" => '36000000',
    "CACHE_FILTER" => 'N',
    "CACHE_GROUPS" => 'N',
    "DISPLAY_TOP_PAGER" => 'N',
    "DISPLAY_BOTTOM_PAGER" => 'N',
    "PAGER_TITLE" => 'Новости',
    "PAGER_TEMPLATE" => '.default',
    "PAGER_SHOW_ALWAYS" => 'N',
    "PAGER_DESC_NUMBERING" => 'N',
    "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
    "PAGER_SHOW_ALL" => 'N',
    "PAGER_BASE_LINK_ENABLE" => 'N',
    "PAGER_BASE_LINK" => NULL,
    "PAGER_PARAMS_NAME" => NULL,
    "DISPLAY_DATE" => 'N',
    "DISPLAY_NAME" => "Y",
    "DISPLAY_PICTURE" => 'N',
    "DISPLAY_PREVIEW_TEXT" => 'N',
    "PREVIEW_TRUNCATE_LEN" => '',
    "ACTIVE_DATE_FORMAT" => 'd.m.Y',
    "USE_PERMISSIONS" => 'N',
    "GROUP_PERMISSIONS" => NULL,
    "FILTER_NAME" => 'arrBroadcastsDetailList',
    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
    "CHECK_DATES" => 'Y',
));?>
