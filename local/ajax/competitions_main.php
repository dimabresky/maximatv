<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $arrCompetitionsListMainAjax;

$filterName = htmlspecialchars($_REQUEST['FILTER_NAME']);
//$arrCompetitionsListMainAjax['PROPERTY'] = array_map('intval', array_filter($_REQUEST[$filterName . '_pf']));
$arrCompetitionsListMainAjax['UF_DISCIPLINE'] = (int)$_REQUEST[$filterName . '_pf']['DISCIPLINE'];
?>
<div class="tabs__content js-tabs-content is-active" data-group="video">
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "competitions_main_list_video",
        array(
            "IBLOCK_TYPE" => "main_content",
            "IBLOCK_ID" => "6",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "COUNT_ELEMENTS" => "Y",
            "TOP_DEPTH" => "2",
            "SECTION_URL" => "/competitions/#SECTION_CODE_PATH#/",
            "VIEW_MODE" => "LIST",
            "SHOW_PARENT_NAME" => "Y",
            "HIDE_SECTION_NAME" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "SECTION_USER_FIELDS" => array("UF_DATE", "UF_DISCIPLINE", "UF_PLACE", "UF_FOTO", ""),
            "FILTER_NAME" => 'arrCompetitionsListMainAjax',
        ),
        false
    );?>
    <?/*$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "competitions_main_video",
        Array(
            "IBLOCK_TYPE" => 'main_content',
            "IBLOCK_ID" => 6,
            "NEWS_COUNT" => 9,
            "SORT_BY1" => 'ACTIVE_FROM',
            "SORT_ORDER1" => 'DESC',
            "SORT_BY2" => 'ID',
            "SORT_ORDER2" => 'DESC',
            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
            "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'WIDGET', 'FOTO', ''],
            "DETAIL_URL" => '/competitions/#ELEMENT_CODE#/',
            "SECTION_URL" => '/competitions/',
            "IBLOCK_URL" => '/competitions/',
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
            "FILTER_NAME" => 'arrCompetitionsListMainAjax',
            "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
            "CHECK_DATES" => 'N',
        )
    );*/?>
</div>
<div class="tabs__content js-tabs-content" data-group="photo">
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "competitions_main_list_foto",
        array(
            "IBLOCK_TYPE" => "main_content",
            "IBLOCK_ID" => "6",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "COUNT_ELEMENTS" => "Y",
            "TOP_DEPTH" => "2",
            "SECTION_URL" => "/competitions/#SECTION_CODE_PATH#/",
            "VIEW_MODE" => "LIST",
            "SHOW_PARENT_NAME" => "Y",
            "HIDE_SECTION_NAME" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "SECTION_USER_FIELDS" => array("UF_DATE", "UF_DISCIPLINE", "UF_PLACE", "UF_FOTO", ""),
            "FILTER_NAME" => 'arrCompetitionsListMainAjax',
        ),
        false
    );?>
    <?/*$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "competitions_main_foto",
        Array(
            "IBLOCK_TYPE" => 'main_content',
            "IBLOCK_ID" => 6,
            "NEWS_COUNT" => 9,
            "SORT_BY1" => 'ACTIVE_FROM',
            "SORT_ORDER1" => 'DESC',
            "SORT_BY2" => 'ID',
            "SORT_ORDER2" => 'DESC',
            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
            "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'WIDGET', 'FOTO', ''],
            "DETAIL_URL" => '/competitions/#ELEMENT_CODE#/',
            "SECTION_URL" => '/competitions/',
            "IBLOCK_URL" => '/competitions/',
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
            "FILTER_NAME" => 'arrCompetitionsListMainAjax',
            "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
            "CHECK_DATES" => 'N',
        )
    );*/?>
</div>