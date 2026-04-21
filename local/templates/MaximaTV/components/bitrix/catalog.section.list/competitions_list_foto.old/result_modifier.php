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
$arDisciplines = [];

foreach ($arResult['SECTIONS'] as $item) {
    if ($item['DEPTH_LEVEL'] == 1) {
        $arSections[$item['ID']] = [
            'NAME' => $item['NAME'],
            'DATE_INTERVAL' => $item['UF_DATE_INTERVAL'],
        ];
    }
    else {
        if (count($item['UF_FOTO']) === 0) {
            continue;
        }
        $arItems[$item['IBLOCK_SECTION_ID']][] = $item;
    }
}

$dbRes = CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => ['dressage', 'jumping', 'triathlon']]);
while ($discipline = $dbRes->GetNext()) {
    $arDisciplines[$discipline['ID']] = $discipline['VALUE'];
}

$arResult['ITEMS'] = $arItems;
$arResult['SECTION_INFO'] = $arSections;
$arResult['DISCIPLINES'] = $arDisciplines;