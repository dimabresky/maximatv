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

if ($arParams['FILTER_NAME']) {
    $arFilter = $GLOBALS[$arParams['FILTER_NAME']];
    if ((int)$arFilter['UF_DISCIPLINE'] > 0 || isset($arFilter['>=UF_DATE'])) {
        //В этом случае разделов 1го уровня не будет, выберем их отдельно
        $dbRes = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'DEPTH_LEVEL' => 1]);
        while ($item = $dbRes->GetNext()) {
            $arResult['SECTIONS'][] = $item;
        }
    }
}

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

$dbRes = CUserFieldEnum::GetList(
    ['XML_ID' => 'ASC'],
    [
        'XML_ID' => ['dressage_photo', 'jumping_photo', 'triathlon_photo', 'cynology_photo', 'other_photo'],
    ]);
while ($discipline = $dbRes->GetNext()) {
    $arDisciplines[$discipline['ID']] = $discipline['VALUE'];
}

$arResult['ITEMS'] = $arItems;
$arResult['SECTION_INFO'] = $arSectionsNew;
$arResult['DISCIPLINES'] = $arDisciplines;