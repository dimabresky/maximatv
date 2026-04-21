<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$arItems = [];
$arDisciplines = [];
// Разделы 1го уровня нам не нужны - пропускаем их
foreach ($arResult['SECTIONS'] as $item) {
    if ($item['DEPTH_LEVEL'] != 1 && count($item['UF_FOTO']) > 0) {
        $innerIndex = (new \DateTime($item['UF_DATE']))->format('Y.m.d') . '.' . $item['SORT'] . '.' . $item['ID'];
        $arItems[$innerIndex] = $item;
    }
}

// Сортируем
krsort($arItems);
$arItems = array_slice($arItems, 0, 7, true);

$dbRes = CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => ['dressage', 'jumping', 'triathlon']]);
while ($discipline = $dbRes->GetNext()) {
    $arDisciplines[$discipline['ID']] = $discipline['VALUE'];
}

$arResult['ITEMS'] = $arItems;
$arResult['DISCIPLINES'] = $arDisciplines;
