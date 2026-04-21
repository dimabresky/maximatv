<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Maxima\Search\Search;

class MXMSearchPageComponent extends \CBitrixComponent
{

    protected $q;
    protected $year;
    protected $discipline;
    protected $disciplineList = [];
    protected $sort;

    public function onPrepareComponentParams($arParams)
    {
        $arParams["PAGE_RESULT_COUNT"] = intval($arParams["PAGE_RESULT_COUNT"]);
        if($arParams["PAGE_RESULT_COUNT"]<=0) {
            $arParams["PAGE_RESULT_COUNT"] = 50;
        }
        $arParams['SHOW_MORE_MODE'] = !empty($arParams['SHOW_MORE_MODE']) && ($arParams['SHOW_MORE_MODE'] == 'Y')
                ? 'Y' : 'N';
        return parent::onPrepareComponentParams($arParams);

    }

    public function executeComponent()
    {
        $this->setFrameMode(false);
        if(! Loader::includeModule('maxima.search')){
            echo Loc::getMessage("MXM_SEARCH_MODULE");
            return;
        }
        $this->arResult = [];
        if($this->arParams['SHOW_MORE_MODE'] == 'N') {
            $this->arResult['DISCIPLINE'] = Search::getDisciplineList();
            $this->getYearList();
        }
        $this->getQuery();
        $templatePage = "";
        if($this->InitComponentTemplate($templatePage)) {
            if (!empty($this->q)) {
                $this->search();
            }
            $this->ShowComponentTemplate();
        }
        else
        {
            $this->__ShowError(str_replace("#PAGE#", $templatePage, str_replace("#NAME#", $this->__templateName, "Can not find '#NAME#' template with page '#PAGE#'")));
        }
    }

    protected function getQuery()
    {
        global $APPLICATION;
        $this->q =  isset($_REQUEST["q"]) ? trim($_REQUEST["q"]) : false;
        $this->arResult["REQUEST"]["~QUERY"] = $this->q;
        $this->arResult["REQUEST"]["QUERY"] = $this->q !== false ? htmlspecialcharsex($this->q) : false;

        $this->year = isset($_REQUEST['year']) ? trim($_REQUEST['year']) : false;
        $this->arResult["REQUEST"]["~YEAR"] = $this->year;
        $this->arResult["REQUEST"]["YEAR"] = $this->year !== false ? htmlspecialcharsex($this->year) : false;

        $this->discipline = isset($_REQUEST['discipline']) ? trim($_REQUEST['discipline']) : false;
        $this->arResult["REQUEST"]["~DISCIPLINE"] = $this->discipline;
        $this->arResult["REQUEST"]["DISCIPLINE"] = $this->discipline !== false ? htmlspecialcharsex($this->discipline) : false;

        $this->sort = $_REQUEST['sort'] == 'asc' ? 'asc' : 'desc';
        $this->arResult["REQUEST"]["SORT"] = $this->sort;


        $this->arResult["URL"] = $APPLICATION->GetCurPage() . "?q=".urlencode($this->q);
        if($this->year){
            $this->arResult["URL"] = '&year=' . urlencode($this->year);
        }
        if($this->discipline){
            $this->arResult["URL"] = '&discipline=' . urlencode($this->year);
        }
        $this->arResult["URL"] .= '&sort=' . $this->sort;
    }

    protected function search()
    {
        $search = new Search();
        $arFilter = [
            'SEARCH_WORD' => $this->q,
        ];
        if($this->year){
            $arFilter['ACTIVE_FROM'] = '01.01.' . $this->year;
            $arFilter['ACTIVE_TO'] = '31.12.' . $this->year;
        }
        if($this->discipline){
            $arFilter['DISCIPLINE'] = $this->discipline;
        }

        $this->arResult['NAV'] = $search->getNav($arFilter,  false,  $this->arParams["PAGE_RESULT_COUNT"]);
        $this->arResult['SEARCH'] = $search->search(['DATE' => $this->sort]);
        $this->arResult['ERROR_TEXT'] = $search->error;
        $this->getAdditionalFields();
    }

    protected function getAdditionalFields()
    {

        if(empty($this->arResult['SEARCH'])){
            return;
        }
        Loader::includeModule('iblock');


        $this->getIblockCodeItem();

        foreach($this->arResult['SEARCH'] as $key => $arItem){
            if($arItem['CONTENT_TYPE'] =='element') {
                switch ($arItem['IBLOCK_CODE']):
                    case 'broadcasts' :
                        $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS'] = $this->getBroadcastFields($arItem);
                        break;
                    case 'programs' :
                        $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS'] = $this->getProgramsFields($arItem);
                        break;
                    case 'events' :
                        $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS'] = $this->getEventsFields($arItem);
                        break;
                endswitch;
            }
            if($arItem['CONTENT_TYPE'] =='section') {
                switch ($arItem['IBLOCK_CODE']):
                    case 'competitions' :
                        $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS'] = $this->getCompetitionSectionFields($arItem);
                        break;
                endswitch;
            }
            if($arItem['CONTENT_TYPE'] =='video') {
                switch ($arItem['IBLOCK_CODE']):
                    case 'competitions' :
                        $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS'] = $this->getCompetitionVideoFields($arItem);
                        $this->arResult['SEARCH'][$key]['TITLE'] =
                            $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS']['COMPETITION'];
                        $this->arResult['SEARCH'][$key]['ADDITIONAL_FIELDS']['COMPETITION'] = $arItem['TITLE'];
                        break;
                endswitch;
            }
        }
    }


    protected function getIblockCodeItem()
    {
        $itemIdList = [];
        foreach($this->arResult['SEARCH'] as $arItem){
            if($arItem['CONTENT_TYPE'] == 'element' || $arItem['CONTENT_TYPE'] == 'video') {
                $itemIdList[] = $arItem['ITEM_ID'];
            }
        }
        $itemIblockCodeList = [];
        $rs =\CIBlockElement::GetList([], ['ID' => $itemIdList], false, false, ['ID', 'IBLOCK_CODE', 'IBLOCK_ID']);
        while($arItem = $rs->Fetch()){
            $itemIblockCodeList[$arItem['ID']] = $arItem;
        }
        foreach($this->arResult['SEARCH'] as $key => $arItem) {
            if($arItem['CONTENT_TYPE'] == 'element' || $arItem['CONTENT_TYPE'] == 'video') {
                $this->arResult['SEARCH'][$key]['IBLOCK_CODE'] = $itemIblockCodeList[$arItem['ITEM_ID']]['IBLOCK_CODE'];
                $this->arResult['SEARCH'][$key]['IBLOCK_ID'] = $itemIblockCodeList[$arItem['ITEM_ID']]['IBLOCK_ID'];
            }
        };

        $sectionIdList = [];
        foreach($this->arResult['SEARCH'] as $arItem){
            if($arItem['CONTENT_TYPE'] == 'section') {
                $sectionIdList[] = $arItem['ITEM_ID'];
            }
        }
        $sectionIblockCodeList = [];

        $rs =\CIBlockSection::GetList([], ['ID' => $sectionIdList], false, ['ID', 'IBLOCK_ID', 'IBLOCK_CODE'], false);
        while($arItem = $rs->Fetch()){
            $sectionIblockCodeList[$arItem['ID']] = $arItem;
        }

        foreach($this->arResult['SEARCH'] as $key => $arItem) {
            if($arItem['CONTENT_TYPE'] == 'section') {
                $this->arResult['SEARCH'][$key]['IBLOCK_CODE'] = $sectionIblockCodeList[$arItem['ITEM_ID']]['IBLOCK_CODE'];
                $this->arResult['SEARCH'][$key]['IBLOCK_ID'] = $sectionIblockCodeList[$arItem['ITEM_ID']]['IBLOCK_ID'];
            }
        };
    }


    protected function getBroadcastFields($arItem)
    {

        $arSelect = ['PREVIEW_PICTURE', 'PROPERTY_LIVE_FROM', 'PROPERTY_LIVE_TO', 'PROPERTY_PLACE', 'PROPERTY_DISCIPLINE'];
        $arFilter = ['ID' => $arItem['ITEM_ID'], 'IBLOCK_ID' => $arItem['IBLOCK_ID']];
        $el = CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->GetNext();

        $liveFrom = $el['PROPERTY_LIVE_FROM_VALUE'];
        $liveTo = $el['PROPERTY_LIVE_TO_VALUE'];
        $isLive = false;
        if ($liveFrom !== '' && $liveTo !== '') {
            $liveFrom = strtotime($liveFrom);
            $liveTo = strtotime($liveTo);
            $now = time();
            $isLive = $now >= $liveFrom && $now <= $liveTo;
        }

        $defaultPicture = false;
        if(empty($el['PREVIEW_PICTURE'])){
            $enum = CIBlockPropertyEnum::GetList([], ['ID' => $el['PROPERTY_DISCIPLINE_ENUM_ID']])->Fetch();
            $disciplineCode = $enum['XML_ID'];
            if (!in_array($disciplineCode, ['dressage', 'jumping'])) {
                $disciplineCode = 'dressage';
            }
            $defaultPicture = PREVIEW_IMAGES_DEFAULT_PATH . 'broadcast.' . $disciplineCode . '.jpg';
        }

        return [
            'PICTURE' => $el['PREVIEW_PICTURE'] ,
            'PLACE' => $el['PROPERTY_PLACE_VALUE'],
            'DEFAULT_PICTURE' => $defaultPicture,
            'IS_LIVE' => $isLive,
        ];
    }

    protected function getProgramsFields($arItem)
    {
        $arSelect = ['PREVIEW_PICTURE', 'ACTIVE_FROM', 'ACTIVE_TO', 'PROPERTY_PLACE'];
        $arFilter = ['ID' => $arItem['ITEM_ID'], 'IBLOCK_ID' => $arItem['IBLOCK_ID']];
        $propList = CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->GetNext();
        return [
            'PICTURE' => $propList['PREVIEW_PICTURE'] ,
            'DATE_INTERVAL' =>$propList['ACTIVE_FROM'] . '-' . $propList['ACTIVE_TO'],
            'PLACE' => $propList['PROPERTY_PLACE_VALUE'],

        ];
    }

    protected function getEventsFields($arItem)
    {
        $arSelect = ['PREVIEW_PICTURE', 'PROPERTY_DATE_INTERVAL',  'PROPERTY_PLACE'];
        $arFilter = ['ID' => $arItem['ITEM_ID'], 'IBLOCK_ID' => $arItem['IBLOCK_ID']];
        $propList = CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->GetNext();
        return [
            'PICTURE' => $propList['PREVIEW_PICTURE'] ,
            'DATE_INTERVAL' => $propList['PROPERTY_DATE_INTERVAL_VALUE'],
            'PLACE' => $propList['PROPERTY_PLACE_VALUE'],
            'DEFAULT_PICTURE' => PREVIEW_IMAGES_DEFAULT_PATH . 'event.jpg',

        ];
    }

    protected function getCompetitionSectionFields($arItem)
    {
        $arFilter = [
            'ID' => $arItem['ITEM_ID'],
            'IBLOCK_ID' => $arItem['IBLOCK_ID']
        ];
        $arSelect = ['PICTURE', 'UF_DATE_INTERVAL', 'UF_PLACE', 'UF_DISCIPLINE'];
        $section = \CIBlockSection::GetList([], $arFilter, false, $arSelect, false)->GetNext();
        return [
            'PICTURE' => $section['PICTURE'],
            'DATE_INTERVAL' => $section['UF_DATE_INTERVAL'],
            'PLACE' => $section['UF_PLACE'],
            'DEFAULT_PICTURE' => $this->getSectionDefaultImgPath($section['UF_DISCIPLINE'])
        ];

    }

    protected function getCompetitionVideoFields($arItem)
    {
        $arSelect = ['PREVIEW_PICTURE', 'IBLOCK_SECTION_ID', 'PROPERTY_DATE_INTERVAL'];
        $arFilter = ['ID' => $arItem['ITEM_ID'], 'IBLOCK_ID' => $arItem['IBLOCK_ID']];
        $el = CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->GetNext();
        $sectionFilter =[
            'ID' => $el['IBLOCK_SECTION_ID'],
            'IBLOCK_ID' => $arItem['IBLOCK_ID']
        ] ;
        $section = \CIBlockSection::GetList([], $sectionFilter, false, ['NAME','PICTURE', 'UF_DISCIPLINE'], false)->Fetch();
        if(empty($el['PREVIEW_PICTURE'])){
            $el['PREVIEW_PICTURE'] = $section['PICTURE'];
        }
        return [
            'PICTURE' => $el['PREVIEW_PICTURE'],
            'DATE_INTERVAL' => $el['PROPERTY_DATE_INTERVAL_VALUE'],
            'COMPETITION' => $section['NAME'],
            'DEFAULT_PICTURE' => $this->getSectionDefaultImgPath($section['UF_DISCIPLINE'])
        ];
    }


    protected function getDisciplineList()
    {
        if(empty($this->disciplineList)){
            $dbRes = \CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => ['dressage', 'jumping', 'triathlon']]);
            while ($discipline = $dbRes->GetNext()) {
                $this->disciplineList[$discipline['ID']] = $discipline['VALUE'];
            }
        }
        return $this->disciplineList;
    }

    protected function getYearList()
    {
        $arDate = Search::getMinMaxDate();
        $minYear = (int) date('Y' ,strtotime($arDate['MIN_DATE']));
        $maxYear = (int) date('Y' ,strtotime($arDate['MAX_DATE']));
        $this->arResult['YEAR_LIST'] = [];
        for ($i = $minYear; $i <= $maxYear; $i++){
            $this->arResult['YEAR_LIST'][] = $i;
        }

    }

    protected function getSectionDefaultImgPath($ufDiscipline)
    {
        switch ($ufDiscipline) {
            case '1':
                $disciplineCode = 'dressage';
                break;
            case '2':
                $disciplineCode = 'jumping';
                break;
            default:
                $disciplineCode = 'dressage';
                break;
        }
         return PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg';
    }
}

























