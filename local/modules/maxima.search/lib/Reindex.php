<?php

namespace Maxima\Search;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Maxima\Search\Tables\SearchContentTable;
use Maxima\Helpers\CommonHelper;
use Maxima\Helpers\VideoQualityHelper;
use Bitrix\Main\Type;



/*
 * Обновление поисковой таблицы
 */
class Reindex
{
    const IBLOCK_ID_ELEMENT = [4, 5, 7];
    const SECTION_IBLOCK_ID = 6;
    const VIDEO_IBLOCK_ID = 6;
    const VIDEO_PROPERTY_CODE = 'PATH_TO_VIDEO';

    protected $newResultList = [];
    protected $disciplineList = [];


    public function start()
    {
        if(! Loader::includeModule('iblock')){
           echo Loc::getMessage("MXM_SEARCH_IBLOCK_MODULE");
           return;
        }

        $this->getIBlockElements();
        $this->getDisciplineList();
        $this->getIBlockSections();
        $this->getVideo();
        SearchContentTable::truncate();
        $this->addSearchContent();
    }

    protected function getIBlockElements()
    {
        global $DB;

        $arIblock = [
            [
                'IBLOCK_CODE' => 'broadcasts',
                'SELECT' => 'PROPERTY_LIVE_FROM',
                'VALUE' => 'PROPERTY_LIVE_FROM_VALUE',
            ],
            [
                'IBLOCK_CODE' => 'programs',
                'SELECT' => 'DATE_ACTIVE_FROM',
                'VALUE' => 'DATE_ACTIVE_FROM',
            ],
            [
                'IBLOCK_CODE' => 'events',
                'SELECT' => 'PROPERTY_DATE_FROM',
                'VALUE' => 'PROPERTY_DATE_FROM_VALUE',
            ],
        ];

        $itemList = [];

        foreach($arIblock as $iblock) {

            $arFilter = [
                'IBLOCK_ID' => self::IBLOCK_ID_ELEMENT,
                'IBLOCK_CODE' => $iblock['IBLOCK_CODE'],
                'ACTIVE' => 'Y',
                [
                    'LOGIC' => 'OR',
                    ">DATE_ACTIVE_TO" => date($DB->DateFormatToPHP(\CLang::GetDateFormat()), time()),
                    'DATE_ACTIVE_TO' => false
                ]
            ];

            $arSelect = ['ID', 'NAME', 'DETAIL_PAGE_URL', 'IBLOCK_CODE', $iblock['SELECT'], 'PROPERTY_DISCIPLINE_VALUE'];
            $rs = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

            while ($arItem = $rs->GetNext()) {
                $dateFrom = false;
                if ( !empty($arItem[$iblock['VALUE']])) {
                    $dateFrom = date('d.m.Y', strtotime($arItem[$iblock['VALUE']]));
                }
                if(empty($itemList[$arItem['ID']])) {
                    $itemList[$arItem['ID']] = [
                        'ITEM_ID' => $arItem['ID'],
                        'TITLE' => $arItem['NAME'],
                        'URL' => $arItem['DETAIL_PAGE_URL'],
                        'SEARCH_CONTENT' => $arItem['NAME'],
                        'CONTENT_TYPE' => 'element',
                        'DATE' => $dateFrom,
                        'DISCIPLINE' => $arItem['PROPERTY_DISCIPLINE_VALUE_VALUE']
                    ];
                }
                else{
                    $itemList[$arItem['ID']]['DISCIPLINE'] .= ' / '  . $arItem['PROPERTY_DISCIPLINE_VALUE_VALUE'];
                }

            }
        }
        foreach($itemList as $arItem){
            $this->newResultList[] = array_merge($this->newResultList, $arItem);
        }
    }

    protected function getDisciplineList()
    {
        $dbRes = \CUserFieldEnum::GetList(['XML_ID' => 'ASC'], ['XML_ID' => ['dressage', 'jumping', 'triathlon']]);
        while ($discipline = $dbRes->GetNext()) {
            $this->disciplineList[$discipline['ID']] = $discipline['VALUE'];
        }
    }

    protected function getIBlockSections()
    {

        $arFilter = [
            'IBLOCK_ID' => self::SECTION_IBLOCK_ID,
            'ACTIVE' => 'Y',
            '>DEPTH_LEVEL' => 1,
        ];

        $rs = \CIBlockSection::GetList([], $arFilter, false, ['ID', 'NAME', 'SECTION_PAGE_URL', 'UF_DATE', 'UF_DISCIPLINE'], false);

        while($arItem = $rs->GetNext()){

            $dateFrom = false;
            if($arItem['UF_DATE']){
                $dateFrom = $arItem['UF_DATE'];
            }
            $this->newResultList[] = [
                'ITEM_ID' => $arItem['ID'],
                'TITLE' => $arItem['NAME'],
                'URL' => $arItem['SECTION_PAGE_URL'],
                'SEARCH_CONTENT' => $arItem['NAME'],
                'CONTENT_TYPE' => 'section',
                'DATE' => $dateFrom,
                'DISCIPLINE' => $this->disciplineList[$arItem['UF_DISCIPLINE']]
            ];
        }
    }

    protected function getVideo()
    {
        global $DB;
        $arFilter = [
            'IBLOCK_ID' => self::VIDEO_IBLOCK_ID,
            'ACTIVE' => 'Y',
            [
                'LOGIC' => 'OR',
                ">DATE_ACTIVE_TO" => date($DB->DateFormatToPHP(\CLang::GetDateFormat()), time()),
                'DATE_ACTIVE_TO' => false
            ]
        ];

        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PROPERTY_' . self::VIDEO_PROPERTY_CODE];
        $rs = \CIBlockElement::GetList([],$arFilter, false, false, $arSelect);

        $itemsList = [];
        $sectionIdList = [];
        $sectionList = [];

        while ($arItem = $rs->GetNext()){
            $itemsList[] = [
                'ID' => $arItem['ID'],
                'NAME' => $arItem['NAME'],
                'IBLOCK_ID' => $arItem['IBLOCK_ID'],
                'IBLOCK_SECTION_ID' => $arItem['IBLOCK_SECTION_ID'],
                'PATH_TO_VIDEO' => $arItem['PROPERTY_' . self::VIDEO_PROPERTY_CODE . '_VALUE'],
            ];
            $sectionIdList[] = $arItem['IBLOCK_SECTION_ID'];
        }

        $arSectionFilter = ['ID' => $sectionIdList, 'IBLOCK_ID' => self::VIDEO_IBLOCK_ID];
        $rs = \CIBlockSection::GetList([], $arSectionFilter, false, ['ID', 'SECTION_PAGE_URL', 'UF_DATE','UF_DISCIPLINE'], false);
        while($arSection = $rs->GetNext()){
            $sectionList[$arSection['ID']] = [
                'SECTION_PAGE_URL' => $arSection['SECTION_PAGE_URL'],
                'UF_DATE' => $arSection['UF_DATE'],
                'UF_DISCIPLINE' => $arSection['UF_DISCIPLINE']
            ];
        }

        foreach ($itemsList as $key => $arItem){
            $videoPaths = VideoQualityHelper::listRegularFilesInVideoDir($arItem['PATH_TO_VIDEO']);
            if ($videoPaths !== []) {
                $arItem['SECTION_PAGE_URL'] = $sectionList[$arItem['IBLOCK_SECTION_ID']]['SECTION_PAGE_URL'];
                $dateFrom = false;
                if($sectionList[$arItem['IBLOCK_SECTION_ID']]['UF_DATE']){
                    $dateFrom = $sectionList[$arItem['IBLOCK_SECTION_ID']]['UF_DATE'];
                }
                $arItem['FILES'] = [];
                foreach ($videoPaths as $videoWebPath) {
                    $fileName = basename($videoWebPath);
                    $arItem['FILES'][] = [
                        'ORIGIN_NAME' => $fileName,
                        'NAME' => CommonHelper::translitFileNameBack(pathinfo($fileName)['filename'])
                    ];
                }
                foreach ($arItem['FILES'] as $fileIndex => $arFile){
                    $this->newResultList[] = [
                        'ITEM_ID' => $arItem['ID'],
                        'TITLE' => $arItem['NAME'],
                        'URL' => $arItem['SECTION_PAGE_URL'] . '?VNUM=' . $fileIndex . '&ID=' . $arItem['ID'],
                        'SEARCH_CONTENT' => mb_strtoupper($arItem['NAME'] .' ' .$arFile['NAME']),
                        'CONTENT_TYPE' => 'video',
                        'DISCIPLINE' => $this->disciplineList[$sectionList[$arItem['IBLOCK_SECTION_ID']]['UF_DISCIPLINE']],
                        'DATE' => $dateFrom,
                        'FILE_NAME' => mb_substr($arFile['NAME'], 0 , 100),
                    ];
                }
            }
        }
    }


    protected function addSearchContent()
    {
        foreach ($this->newResultList as $arItem){
            $arItem['SEARCH_CONTENT'] = mb_strtoupper($arItem['SEARCH_CONTENT']);
            if($arItem['DATE']){
               $arItem['DATE'] = new Type\Date($arItem['DATE'], 'd.m.Y');
            }
            else{
                unset($arItem['DATE']);
            }
            SearchContentTable::add($arItem);
        }
    }

}