<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

CModule::IncludeModule('iblock');

$arIds = [];
$arBroadcasts = [];

foreach ($arResult['ITEMS'] as $item) {
    if(!empty($item['PROPERTIES']['BROADCAST_ID']['VALUE'])) {
        $arIds[] = $item['PROPERTIES']['BROADCAST_ID']['VALUE'];
    }
}

$iblock = \Maxima\Helpers\CommonHelper::getIblock('main_content', 'broadcasts');
$dbRes = CIBlockElement::GetList([], ['IBLOCK_ID' => $iblock['ID'], 'ID' => $arIds], false, false, ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_LIVE_FROM']);
while ($item = $dbRes->GetNext()) {
    $arBroadcasts[$item['ID']] = $item;
}

$arResult['BROADCASTS'] = $arBroadcasts;