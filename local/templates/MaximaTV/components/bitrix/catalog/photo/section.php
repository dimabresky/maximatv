<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

\Bitrix\Main\Loader::includeModule('iblock');

/*$sectionCodePath = str_replace('/competitions/', '', str_replace('/photo/' , '', $curPage));
$sectionId = \CIBlockFindTools::GetSectionIDByCodePath(6, $sectionCodePath);
*/
$sectionId = $arResult['VARIABLES']['SECTION_ID'];
$arFilter = [
    'SECTION_ID' => $sectionId,
    'ACTIVE' => 'Y'
];
if(!empty($_GET['ID'])){
    $arFilter['ID'] = $_GET['ID'];
}

$element  = \CIBlockElement::GetList(['id' => 'desc', 'sort' => 'asc'], $arFilter, false, ['nTopCount' => 1], ['ID'])->Fetch();

$APPLICATION->IncludeComponent(
    "mxm:photo.list",
    "",
    array(
        'SECTION_TITLE' => 'Фото соревнования',
        'TYPE' => 'e',
        'FULL_PAGE' => 'Y',
        'ID' => $element['ID']

    )
);

