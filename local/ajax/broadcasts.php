<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $arrBroadcastsList;

$filterName = htmlspecialchars($_REQUEST['FILTER_NAME']);
$itemsCount = 9999;
if ($filterName === 'arrBroadcastsListMain') {
    $itemsCount = 3;
}
$arrBroadcastsListAjax['PROPERTY'] = array_map('intval', array_filter($_REQUEST[$filterName . '_pf']));

$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "broadcasts",
    Array(
        "IBLOCK_TYPE" => 'main_content',
        "IBLOCK_ID" => 4,
        "NEWS_COUNT" => $itemsCount,
        "SORT_BY1" => 'ACTIVE_FROM',
        "SORT_ORDER1" => 'DESC',
        "SORT_BY2" => 'ID',
        "SORT_ORDER2" => 'DESC',
        "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
        "PROPERTY_CODE" => ['DISCIPLINE', 'STATUS', ''],
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
        "FILTER_NAME" => 'arrBroadcastsListAjax',
        "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
        "CHECK_DATES" => 'Y',
    )
);