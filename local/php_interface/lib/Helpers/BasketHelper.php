<?php

namespace Maxima\Helpers;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable as Highload;

class BasketHelper
{
    public $items = [];

    protected $userId;
    protected $photoPriceList = [];
    protected $typeList = [];

    const BASKET_IBLOCK_ID = 10;
    const PHOTO_PRICE_HIGHLOAD = 6;

    public function __construct($userId = false)
    {
        Loader::includeModule('iblock');
        Loader::includeModule('highloadblock');
        $this->getUserId($userId);
        $this->getPhotoPriceList();
        $this->getTypeList();
        $this->loadItems(true);
    }

    public function loadItems()
    {
        $this->items = [];
        $arSort = ['ID' => 'asc'];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => self::BASKET_IBLOCK_ID,
            'PROPERTY_USER_ID' => $this->userId
        ];
        $arSelect = ['ID', 'PREVIEW_PICTURE', 'PROPERTY_TYPE', 'NAME'];
        $rs = \CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while ($arItem = $rs->GetNext()) {
            $this->items[] = [
                'ID' => $arItem['ID'],
                'TYPE' => $this->typeList[$arItem['PROPERTY_TYPE_ENUM_ID']],
                'PHOTO' => $arItem['PREVIEW_PICTURE'],
                'PRICE' => false,
                'NAME' => $arItem['NAME']
            ];
        }
        $this->recalculate();
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /*
     * @return int
     */
    public function getSum()
    {
        $sum = 0;
        foreach($this->items as $arItem){
            $sum += $arItem['PRICE'];
        }
        return $sum;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->items);
    }

    public function addItem($id, $type)
    {
        $type = strtoupper($type);
        if($type == 'PHOTO'){
            $this->addPhotoItem($id);
        }
        $this->loadItems();
    }

    public function removeItem($id)
    {
        $arFilter = [
            'ID' => $id,
            'IBLOCK_ID' => self::BASKET_IBLOCK_ID,
            'PROPERTY_USER_ID' => $this->userId
        ];
        $el = \CIBlockElement::GetList([], $arFilter, false, false, ['ID'])->Fetch();
        if($el){
            \CIBlockElement::Delete($el['ID']);
        }
        $this->loadItems();
    }

    public function clear()
    {
        $arFilter = [
            'IBLOCK_ID' => self::BASKET_IBLOCK_ID,
            'PROPERTY_USER_ID' => $this->userId
        ];
        $rs = \CIBlockElement::GetList([], $arFilter, false, false, ['ID']);
        while($arItem = $rs->fetch()){
            \CIBlockElement::Delete($arItem['ID']);
        }
        $this->items = [];
    }

    public function getOriginalPhotoPathList()
    {

        $photoList = [];
        $arSort = ['ID' => 'asc'];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => self::BASKET_IBLOCK_ID,
            'PROPERTY_USER_ID' => $this->userId
        ];
        $arSelect = ['PROPERTY_PATH_TO_PHOTO'];
        $rs = \CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while ($arItem = $rs->GetNext()) {
            $photoList[] = $arItem['PROPERTY_PATH_TO_PHOTO_VALUE'];
        };
        return $photoList;
    }

    protected function addPhotoItem($id)
    {
        global $USER;
        $id = explode('_', $id);
        $type = $id[0];
        $itemId = $id[1];
        $photoCount = $id[2];
        if(empty($itemId) || $photoCount === '' || !in_array($type, ['s', 'e'])){
            return;
        }
        $path = $this->getPhotoPath($type, $itemId, $photoCount);
        if(empty($path)){
            return;
        }
        $relativePathPhoto = str_replace(dirname(dirname(dirname(dirname(__DIR__)))), '', $path);

        $arFilter = [
            'IBLOCK_ID' => self::BASKET_IBLOCK_ID,
            'PROPERTY_PATH_TO_PHOTO' => $relativePathPhoto,
            'PROPERTY_USER_ID' => $USER->GetID()
        ];

        $el = \CIBlockElement::GetList([], $arFilter, false, false, ['ID'])->Fetch();
        if($el){
            return;
        }

        $el = new \CIBlockElement();

        $photoType = false;
        foreach ($this->typeList as $value => $type){
            if($type == 'PHOTO'){
                $photoType = $value;
            }
        }
        $arFields = [
            'ACTIVE' => 'Y',
            'NAME' => $this->getPhotoName($itemId),
            'IBLOCK_ID' => self::BASKET_IBLOCK_ID,
            'PREVIEW_PICTURE' => \CFile::MakeFileArray($path),
            'PROPERTY_VALUES' => [
                'TYPE' => $photoType,
                'PATH_TO_PHOTO' => $relativePathPhoto,
                'USER_ID' => $USER->GetID()

            ]
        ];
        $el->Add($arFields, false, false);
    }

    protected function recalculate()
    {
        $photoCount = 0;
        $photoPrice = 0;
        foreach ($this->items as $arItem){
            if($arItem['TYPE'] == 'PHOTO'){
                $photoCount++;
            }
        }
        foreach($this->photoPriceList as $count => $price){
            if($count <=$photoCount ){
                $photoPrice = $price;
            }
        }
        foreach($this->items as $k => $arItem){
            if($arItem['TYPE'] == 'PHOTO'){
                $this->items[$k]['PRICE'] = $photoPrice;
            }
        }
    }

    protected function getUserId($userId)
    {
        global $USER;
        if(empty($userId)){
            $this->userId = $USER->GetID();
        }
    }

    protected function getPhotoPriceList()
    {
        $photoPriceHl = Highload::getById(self::PHOTO_PRICE_HIGHLOAD)->fetch();
        $photoPriceEntity = Highload::compileEntity($photoPriceHl);
        $photoPriceClass = $photoPriceEntity->getDataClass();
        $results = $photoPriceClass::getList([
            'order' => ['UF_PHOTO_COUNT' => 'asc']
        ]);
        $elements = $results->fetchAll();
        foreach ($elements as $item) {
            $this->photoPriceList[$item['UF_PHOTO_COUNT']] = (int) $item['UF_PRICE'];
        }
    }

    protected function getTypeList()
    {
        $arFilter = [];
        $rs = \CIBlockPropertyEnum::GetList([], $arFilter);
        while($arItem = $rs->Fetch()){
            $this->typeList[$arItem['ID']] = $arItem['XML_ID'];
        }
    }

    protected function getPhotoPath($type, $itemId, $videoCount)
    {
        $pathToPhoto = false;
        if($type == 's'){
            $section  =
                \CIBlockSection::GetList([], ['ID' => $itemId], false, ['IBLOCK_ID'], false)->Fetch();
            if(!$section){
                return false;
            }
            $arFilter = [
                'ID' => $itemId,
                'IBLOCK_ID' => $section['IBLOCK_ID']
            ];
            $section  =
                \CIBlockSection::GetList([], $arFilter, false, ['UF_PATH_TO_PHOTO'], false)->Fetch();
            $pathToPhoto = $section['UF_PATH_TO_PHOTO'];
        }

        if($type == 'e'){
            $el = \CIBlockElement::GetList([], ['ID' => $itemId], false, false, ['IBLOCK_ID'])->GetNext();
            if(!$el){
                return false;
            }
            $arFilter = [
                'ID' => $itemId,
                'IBLOCK_ID' => $el['IBLOCK_ID']
            ];
            $el = \CIBlockElement::GetList([], $arFilter, false, false, ['PROPERTY_PATH_TO_PHOTO'])->GetNext();
            $pathToPhoto = $el['PROPERTY_PATH_TO_PHOTO_VALUE'];
        }
        if(!$pathToPhoto){
            return false;
        }

        $this->arResult['PHOTO_LIST'] = [];
        $fileList = [];
        $dirPath = dirname(dirname(dirname(dirname(__DIR__)))) . $pathToPhoto;
        $arFileNames = scandir($dirPath, SCANDIR_SORT_ASCENDING);
        if ($arFileNames !== false) {
            foreach ($arFileNames as  $k => $fileName) {
                if (!in_array($fileName, ['.', '..']) && !is_dir($dirPath . $fileName)){
                    $fileList[] = $fileName;
                }
            }
        }
        if(!empty($fileList[$videoCount])){
           return $dirPath . $fileList[$videoCount];
        }
        return false;
    }

    protected function getPhotoName($itemId){
        $name = '';
        $el = \CIBlockElement::GetList([], ['ID' => $itemId], false, false, ['NAME', 'IBLOCK_SECTION_ID', 'IBLOCK_ID'])->Fetch();
        if(!empty($el['NAME'])){
            $name = $el['NAME'];
            if($el['IBLOCK_ID'] == IBLOCK_PHOTOS_ID){
                $section = \CIBlockSection::GetList([], ['ID' => $el['IBLOCK_SECTION_ID']], false, ['NAME'], false)->Fetch();
                if($section['NAME']){
                    $name = $section['NAME'] . '.<br><strong>' . $name . '</strong>';
                }
            }
        }
        return $name;
    }

}