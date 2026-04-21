<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $arrProgramsListAjax;

$filterName = htmlspecialchars($_REQUEST['FILTER_NAME']);
$arrFilter = array_map('htmlspecialchars', array_filter($_REQUEST[$filterName]));
$dateFrom = '01.' . $arrFilter['MONTH'] . '.' . $arrFilter['YEAR'] . ' 00:00:00';
$dateTo = (new DateTime($arrFilter['YEAR'] . '-' . $arrFilter['MONTH'] . '-01 00:00:00'))->modify('+1 month')->modify('-1 second')->format('d.m.Y H:i:s');

$arrProgramsListAjax = [
    [
        "LOGIC" => "OR",
        ["><DATE_ACTIVE_FROM" => [$dateFrom, $dateTo]],
        ["><DATE_ACTIVE_TO" => [$dateFrom, $dateTo]],
        //"><DATE_ACTIVE_FROM" => [$dateFrom, $dateTo],
    ]
];

/*echo '<pre>';
var_dump($arrProgramsListAjax);*/
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "programs",
    Array(
        "IBLOCK_TYPE" => 'main_content',
        "IBLOCK_ID" => 7,
        "NEWS_COUNT" => 99,
        "SORT_BY1" => 'ACTIVE_FROM',
        "SORT_ORDER1" => 'DESC',
        "SORT_BY2" => 'ID',
        "SORT_ORDER2" => 'DESC',
        "FIELD_CODE" => ['ID','CODE','NAME','PREVIEW_PICTURE','ACTIVE_FROM','ACTIVE_TO',''],
        "PROPERTY_CODE" => ['DISCIPLINE', ''],
        "DETAIL_URL" => '/programs/#ELEMENT_CODE#/',
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
        "FILTER_NAME" => 'arrProgramsListAjax',
        "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
        "CHECK_DATES" => 'N',
    )
);