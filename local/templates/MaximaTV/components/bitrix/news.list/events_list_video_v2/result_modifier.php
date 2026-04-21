<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

use Maxima\Helpers\DateHelper;

$arItems = [];
$arSections = [];
$arSectionsNew = [];
$arDisciplines = [];

$dbRes = CIBlockSection::GetList(['NAME' => 'DESC'], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'DEPTH_LEVEL' => 1]);
while ($item = $dbRes->GetNext()) {
    $arName = explode('.', $item['NAME']);
    $item['SCREEN_NAME'] = DateHelper::getMonthByIndex($arName[1]) . ' ' . $arName[0];
    $arResult['SECTIONS'][$item['NAME']] = $item;
}

foreach ($arResult['ITEMS'] as $item) {
    $arDate = explode('.', $item['PROPERTIES']['DATE_FROM']['VALUE']);
    $index = $arDate[2] . '.' . $arDate[1];
    //\Maxima\Helpers\CommonHelper::aDump($index);
    if ($index !== '.') {
        $arItems[$index][] = $item;
    }
}

//\Maxima\Helpers\CommonHelper::aDump($arResult['SECTIONS']);
/*
// Сначала вытащим данные о разделах 1 уровня
foreach ($arResult['SECTIONS'] as $item) {
    if ($item['DEPTH_LEVEL'] == 1) {
        $arSections[$item['ID']] = [
            'NAME' => $item['NAME'],
            'DATE_INTERVAL' => $item['UF_DATE_INTERVAL'],
        ];
        $arName = explode('.', $item['NAME']);
        $arSectionsNew[$item['NAME']] = [
            'NAME' => DateHelper::getMonthByIndex($arName[1]) . ' ' . $arName[0],
            'DATE_INTERVAL' => $item['UF_DATE_INTERVAL'],
        ];
    }
}

// Теперь распределим подразделы по разделам
foreach ($arResult['SECTIONS'] as $item) {
    if ($item['DEPTH_LEVEL'] != 1) {
        $topIndex = $arSections[$item['IBLOCK_SECTION_ID']]['NAME'];
        $innerIndex = (new \DateTime($item['UF_DATE']))->format('Y.m.d') . '.' . $item['SORT'] . '.' . $item['ID'];
        $arItems[$topIndex][$innerIndex] = $item;
    }
}

// Сортируем месяцы
krsort($arItems);
foreach ($arItems as $index => &$item) {
    krsort($item);
}
unset($item);

$dbRes = CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => ['dressage', 'jumping', 'triathlon', 'cynology', 'other']]);
while ($discipline = $dbRes->GetNext()) {
    $arDisciplines[$discipline['ID']] = $discipline['VALUE'];
}
*/
$arResult['ITEMS'] = $arItems;
//\Maxima\Helpers\CommonHelper::aDump($arItems);
/*$arResult['SECTION_INFO'] = $arSectionsNew;
$arResult['DISCIPLINES'] = $arDisciplines;
*/