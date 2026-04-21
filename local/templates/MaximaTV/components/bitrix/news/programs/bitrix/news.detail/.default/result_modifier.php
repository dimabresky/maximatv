<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$arItems = [];
if (count($arResult['PROPERTIES']['ROUTE']['VALUE']) > 0) {
    $dbRes = CIBlockElement::GetList(
        ['ACTIVE_FROM' => 'DESC', 'ID' => 'DESC'],
        ['ID' => $arResult['PROPERTIES']['ROUTE']['VALUE'], 'IBLOCK_ID' => 6],
        false,
        false,
        ['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_DATE_INTERVAL'/*, 'PROPERTY_WIDGET'*/, 'PROPERTY_PATH_TO_VIDEO']
    );
    while ($item = $dbRes->GetNext()) {
        $arItems[$item['ID']] = $item;
    }
}

$arResult['ITEMS'] = $arItems;


$arItems = [];
if (count($arResult['PROPERTIES']['EVENTS']['VALUE']) > 0) {
    $dbRes = CIBlockElement::GetList(
        ['ACTIVE_FROM' => 'DESC', 'ID' => 'DESC'],
        ['ID' => $arResult['PROPERTIES']['EVENTS']['VALUE'], 'IBLOCK_ID' => 5],
        false,
        false,
        ['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PAGE_URL',  'PROPERTY_PLACE', 'PROPERTY_DATE_FROM', 'PROPERTY_DATE_TO',
            'PROPERTY_VIDEO_FILE' ]
    );
    while ($item = $dbRes->GetNext()) {
        $arItems[$item['ID']] = $item;
    }
}

$arResult['EVENT_LIST'] = $arItems;