<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$arDisciplines = [];

$dbRes = CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => ['dressage', 'jumping', 'triathlon']]);
while ($discipline = $dbRes->GetNext()) {
    $arDisciplines[$discipline['ID']] = $discipline['VALUE'];
}

$arResult['DISCIPLINES'] = $arDisciplines;
