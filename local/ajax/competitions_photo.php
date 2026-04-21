<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

global $APPLICATION;
global $arrCompetitionsListPhotoAjax;

$filterName = htmlspecialchars($_REQUEST['FILTER_NAME']);
$arrCompetitionsListPhotoAjax = [];

$disciplineId = (int)$_REQUEST[$filterName . '_pf']['DISCIPLINE'];
$dates = htmlspecialchars($_REQUEST[$filterName . '_pf']['DATES']);
if ($disciplineId > 0) {
    $xmlId = CIBlockPropertyEnum::GetByID($disciplineId)['XML_ID'];
    $valueId = -1;
    $dbRes = CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => $xmlId . '_photo']);
    if ($discipline = $dbRes->GetNext()) {
        $valueId = $discipline['ID'];
    }

    $arrCompetitionsListPhotoAjax['UF_DISCIPLINE'] = $valueId;
}
if ($dates != '') {
    if (mb_strpos($dates, ' - ') !== false) {
        $arDates = explode(' - ', $dates);

        $dateFrom = $arDates[0];
        $dateTo = $arDates[1];
    } else {
        $dateFrom = $dateTo = trim($dates);
    }

    $arrCompetitionsListPhotoAjax['>=UF_DATE'] = $dateFrom;
    $arrCompetitionsListPhotoAjax['<=UF_DATE'] = $dateTo . ' 23:59:59';
}
?>
<div class="tabs__content js-tabs-content is-active" data-group="photo">
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "competitions_list_photo",
        array(
            "IBLOCK_TYPE" => 'main_content',
            "IBLOCK_ID" => 14,
            "CACHE_TYPE" => 'A',
            "CACHE_TIME" => '36000000',
            "CACHE_FILTER" => 'N',
            "CACHE_GROUPS" => 'N',
            "COUNT_ELEMENTS" => "Y",
            "TOP_DEPTH" => 2,
            "SECTION_URL" => '/photo/#SECTION_CODE_PATH#/',
            "VIEW_MODE" => 'LIST',
            "SHOW_PARENT_NAME" => 'N',
            "HIDE_SECTION_NAME" => 'Y',
            "ADD_SECTIONS_CHAIN" => 'N',
            "SECTION_USER_FIELDS" => array("UF_DATE_INTERVAL", "UF_DISCIPLINE", "UF_PLACE", "UF_FOTO", ""),
            "FILTER_NAME" => 'arrCompetitionsListPhotoAjax',
        ),
        $component
    );?>
</div>
