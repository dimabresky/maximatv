<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$arItems = [];
$arSections = [];

foreach ($arResult['ITEMS'] as $item) {
    $arItems[$item['IBLOCK_SECTION_ID']][] = $item;
}
$arResult['ITEMS'] = $arItems;

$dbRes = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => array_keys($arItems)], false, ['ID', 'NAME', 'UF_DATE_INTERVAL']);
while ($section = $dbRes->GetNext()) {
    $arSections[$section['ID']] = [
        'NAME' => $section['NAME'],
        'DATE_INTERVAL' => $section['UF_DATE_INTERVAL'],
    ];
}
$arResult['SECTION_INFO'] = $arSections;