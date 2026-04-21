<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$section = CIBlockSection::GetByID($arResult['ID'])->GetNext();

global $APPLICATION;
$APPLICATION->AddChainItem($section['NAME'], $section['SECTION_PAGE_URL']);