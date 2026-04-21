<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Maxima\Helpers\PhotoHelper;


class MXMPhotoList extends \CBitrixComponent
{
    protected $mode = 'slide';
    protected $path;
    protected $dirPath;
    protected $relativeDirPath;
    protected $pageSize = 7;
    protected $slidePageSize = 12;
    protected $pageNumber = 1;
    protected $allPageCount = 1;
    protected $basketPhotoPathList = [];

    public function onPrepareComponentParams($arParams)
    {
       if(!empty($arParams['SHOW_MORE']) && ($arParams['SHOW_MORE'] == 'Y')){
           $this->mode = 'more';
       }
        if(!empty($arParams['FULL_PAGE']) && ($arParams['FULL_PAGE'] == 'Y')){
            $this->mode = 'full';
        }
        return parent::onPrepareComponentParams($arParams);

    }

    public function executeComponent()
    {
        global $APPLICATION;
        Loader::includeModule("iblock");
        if($this->mode == 'slide'){
          $this->pageSize = $this->slidePageSize;
        }

        $this->getPhotoPath();
        if(!$this->path){
            return;
        }

        $basket = new \Maxima\Helpers\BasketHelper();
        $this->basketPhotoPathList = $basket->getOriginalPhotoPathList();

        if(!empty($_REQUEST['photo_page'])){
            $this->pageNumber = (int) $_REQUEST['photo_page'];
        }

        $this->getFileList();
        $this->arResult['NEXT_PAGE'] = $this->pageNumber == $this->allPageCount
                ? false : $this->pageNumber + 1;
        if($this->mode == 'slide') {
            $this->includeComponentTemplate('slide');
        }
        else{
            if (!$this->arParams['SHOW_MORE']) {
                $APPLICATION->SetPageProperty('title', 'Архив фото ' . $this->arResult['ITEM']['NAME']);
            }
            $this->includeComponentTemplate();
        }

    }

   protected function getPhotoPath()
    {
        if($this->mode == 'slide' && !empty($this->arParams['FILE_PATH'])){
            $this->path = $this->arParams['FILE_PATH'];
        }


        if($this->mode != 'slide' && !empty($this->arParams['TYPE']) && !empty($this->arParams['ID'])){

            if($this->mode == 'full') {
                $this->getInformation();
            }

            if($this->arParams['TYPE'] == 'e'){
                 $arFilter = [
                     'ID' => $this->arParams['ID']
                 ];
                 $el = \CIBlockElement::GetList([], $arFilter, false, false, ['IBLOCK_ID'])->fetch();
                 if(!$el){
                     return;
                 }
                 $arFilter['IBLOCK_ID'] = $el['IBLOCK_ID'];
                 $el = \CIBlockElement::GetList([], $arFilter, false, false, ['PROPERTY_PATH_TO_PHOTO'])->fetch();
                 if(!empty($el['PROPERTY_PATH_TO_PHOTO_VALUE'])){
                     $this->path = $el['PROPERTY_PATH_TO_PHOTO_VALUE'];
                 }
            }
            if($this->arParams['TYPE'] == 's'){
                $arFilter = [
                    'ID' => $this->arParams['ID']
                ];
                $section = \CIBlockSection::GetList([], $arFilter, false, ['IBLOCK_ID'], false)->fetch();
                if(!$section){
                    return;
                }
                $arFilter['IBLOCK_ID'] = $section['IBLOCK_ID'];
                $section = \CIBlockSection::GetList([], $arFilter, false, ['UF_PATH_TO_PHOTO'], false)->fetch();
                if(!empty($section['UF_PATH_TO_PHOTO'])){
                    $this->path = $section['UF_PATH_TO_PHOTO'];
                }
            }
        }
    }


    protected function getFileList()
    {
        $this->arResult['PHOTO_LIST'] = [];
        $this->dirPath = dirname(dirname(dirname(dirname(__DIR__)))) . $this->path;
        $arFileNames = scandir($this->dirPath, SCANDIR_SORT_ASCENDING);

        if ($arFileNames !== false) {
            $tmpFileNames = [];
            foreach ($arFileNames as  $k => $fileName) {
                if (!in_array($fileName, ['.', '..']) && !is_dir($this->dirPath . $fileName)){
                  //  $this->addPicture($fileName);
                    $tmpFileNames[] = $fileName;
                }
            }
            $this->allPageCount = ceil(count($tmpFileNames)/ $this->pageSize);
            $this->arResult['ALL_COUNT_PHOTO'] = count($tmpFileNames);

            if($this->pageNumber > $this->allPageCount){
                return;
            }
            $startIndex = ($this->pageNumber - 1) * $this->pageSize;
            $endIndex = $this->pageNumber * $this->pageSize - 1 ;
            foreach($tmpFileNames as $k => $fileName){
                if($k >= $startIndex && $k <= $endIndex){
                    $this->addPicture($fileName, $k);
                }
            }

        }
    }

    protected function getInformation()
    {
        global $APPLICATION;
        $this->arResult['CUR_DIR'] = $APPLICATION->GetCurDir();
        $arSelect = ['IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID'];
        $element = CIBlockElement::GetList([], ['ID' => $this->arParams['ID']], false, ['nTopCount' => 1], $arSelect)->Fetch();
        if(!$element){
            return;
        }
        switch ($element['IBLOCK_ID']) {
            case IBLOCK_EVENTS_ID:
                $arSelect = ['NAME', 'PROPERTY_DISCIPLINE', 'PROPERTY_DATE_FROM', 'PROPERTY_DATE_TO', 'PROPERTY_PLACE'];
                $arFilter = ['ID' => $this->arParams['ID'], 'IBLOCK_ID' => IBLOCK_EVENTS_ID];
                $element = CIBlockElement::GetList([], $arFilter, false, ['nTopCount' => 1], $arSelect)->Fetch();

                $this->arResult['ITEM'] = [
                    'NAME' => $element['NAME'],
                    'DISCIPLINE' => $element['PROPERTY_DISCIPLINE_VALUE'],
                    'DATE' => $element['PROPERTY_DATE_FROM_VALUE'] . ' - ' . $element['PROPERTY_DATE_TO_VALUE'],
                    'PLACE' => $element['PROPERTY_PLACE_VALUE']
                ];
                break;
            case IBLOCK_COMPETITIONS_ID:
            case IBLOCK_PHOTOS_ID:
                $arFilter = [
                    'ID' => $element['IBLOCK_SECTION_ID'],
                    'IBLOCK_ID' => $element['IBLOCK_ID'],
                ];
                $arSelect = ['NAME', 'UF_DATE', 'UF_PLACE', 'UF_DISCIPLINE'];
                $section = \CIBlockSection::GetList([], $arFilter, false, $arSelect, ['nTopCount' => 1])->Fetch();

                $this->arResult['ITEM'] = [
                    'NAME' => $section['NAME'] . ' &mdash; ' . $element['NAME'],
                    'DATE' => $section['UF_DATE'],
                    'PLACE' => $section['UF_PLACE']
                ];

                if ($section['UF_DISCIPLINE']) {
                    $uF = \CUserFieldEnum::GetList([], ['ID' => $section['UF_DISCIPLINE']])->Fetch();
                    if (!empty($uF['VALUE'])) {
                        $this->arResult['ITEM']['DISCIPLINE'] = $uF['VALUE'];
                    }

                }

                //Вытащим соседние элементы
                $this->arResult['OTHERS'] = [];
                $arSelect[] = 'PROPERTY_PATH_TO_PHOTO';
                $dbRes = CIBlockElement::GetList(
                    ['ACTIVE_FROM' => 'DESC', 'ID' => 'DESC'],
                    ['IBLOCK_ID' => $element['IBLOCK_ID'], 'SECTION_ID' => $element['IBLOCK_SECTION_ID'], '!ID' => $this->arParams['ID']],
                    false,
                    false,
                    $arSelect
                );
                while ($item = $dbRes->GetNext()) {
                    $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $item['PROPERTY_PATH_TO_PHOTO_VALUE'];
                    $arFileNames = scandir($absolutePath, SCANDIR_SORT_ASCENDING);
                    if ($arFileNames !== false) {
                        $arFileNames = array_diff($arFileNames, ['.', '..']);
                        $fileName = reset($arFileNames);
                        $arPhoto = $this->preparePicture($absolutePath, $item['PROPERTY_PATH_TO_PHOTO_VALUE'], $fileName, $item['ID']);
                        $arPhoto['NAME'] = $item['NAME'];
                        $arPhoto['COUNT'] = count($arFileNames);
                        $this->arResult['OTHERS'][] = $arPhoto;
                    }
                }
                break;
        }
    }

    protected function addPicture($fileName, $k, $watermark = true)
    {
        $this->arResult['PHOTO_LIST'][] = $this->preparePicture($this->dirPath, $this->path, $fileName, $k, $watermark);
    }

    protected function preparePicture($absolutePath, $relativePath, $fileName, $k, $watermark = true)
    {

        $filePath = $absolutePath . '/' . $fileName;
        $filter = false;
        if($watermark){
            $filter = [
                'small' => [
                    [
                        "name" => "watermark",
                        "position" => "center", // Положение
                        "type" => "image",
                        "size" => "real",
                        "file" => $_SERVER["DOCUMENT_ROOT"].'/local/images/Maxima-logo-2.png',
                        "fill" => "exact",
                    ]
                ],
                'big' => [
                    [
                        "name" => "watermark",
                        "position" => "center", // Положение
                        "type" => "image",
                        "size" => "real",
                        "file" => $_SERVER["DOCUMENT_ROOT"].'/local/images/maxima-logo-750.png',
                        "fill" => "exact",
                    ]
                ]
            ];
        }

        $relativePath = $relativePath  . $fileName;
        return [
            'FILE_PATH' => $filePath,
            'RELATIVE_FILE_PATH' => $relativePath,
            'RESIZE_ARRAY' => PhotoHelper::getResizeArray($filePath),
            'FILTER' => $filter,
            'INDEX' => $k,
            'IN_BASKET' => in_array($relativePath, $this->basketPhotoPathList)
        ];
    }

}