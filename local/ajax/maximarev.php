<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $arrMaximarevMainAjax;

$filterName = htmlspecialchars($_REQUEST['FILTER_NAME']);

$dates = htmlspecialchars($_REQUEST[$filterName . '_pf']['DATES']);
unset($_REQUEST[$filterName . '_pf']['DATES']);

$arrMaximarevMainAjax['PROPERTY'] = array_map('intval', array_filter($_REQUEST[$filterName . '_pf']));
if ($dates != '') {
    if (mb_strpos($dates, ' - ') !== false) {
        $arDates = explode(' - ', $dates);

        $dateFrom = $arDates[0];
        $dateTo = $arDates[1];
    } else {
        $dateFrom = $dateTo = trim($dates);
    }
    $arDates = explode('.', $dateFrom);
    $dateFrom = implode('-', array_reverse($arDates));
    $arDates = explode('.', $dateTo);
    $dateTo = implode('-', array_reverse($arDates));

    $arrMaximarevMainAjax['PROPERTY']['>=DATE_FROM'] = $dateFrom;
    $arrMaximarevMainAjax['PROPERTY']['<=DATE_FROM'] = $dateTo . ' 23:59:59';
}
?>
<div class="tabs__content js-tabs-content is-active" data-group="video">
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "maximarev_main_video",
        Array(
            "IBLOCK_TYPE" => 'main_content',
            "IBLOCK_ID" => 18,
            "NEWS_COUNT" => 8,
            "SORT_BY1" => 'ACTIVE_FROM',
            "SORT_ORDER1" => 'DESC',
            "SORT_BY2" => 'ID',
            "SORT_ORDER2" => 'DESC',
            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
            "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'WIDGET', 'FOTO', ''],
            "DETAIL_URL" => '/events/#ELEMENT_CODE#/',
            "SECTION_URL" => '/programs/',
            "IBLOCK_URL" => '/programs/',
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
            "FILTER_NAME" => 'arrMaximarevMainAjax',
            "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
            "CHECK_DATES" => 'Y',
        )
    );?>
</div>