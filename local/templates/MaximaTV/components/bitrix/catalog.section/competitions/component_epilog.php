<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (isset($arResult['MAIN_ITEM'])) {
    $routeName = ' - ' . $arResult['MAIN_ITEM']['NAME'];
} else {
    $routeName = '';
}

$APPLICATION->SetPageProperty('title', 'Архив видео ' . $arResult['NAME'] . $routeName);
/*
$section = CIBlockSection::GetByID($arResult['ID'])->GetNext();

global $APPLICATION;
$APPLICATION->AddChainItem($section['NAME'], $section['SECTION_PAGE_URL']);*/