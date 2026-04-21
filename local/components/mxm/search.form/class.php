<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;


class MXMSearchFormComponent extends \CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        if(!isset($arParams["PAGE"]) || strlen($arParams["PAGE"])<=0) {
            $arParams["PAGE"] = "#SITE_DIR#search/index.php";
        }

        return parent::onPrepareComponentParams($arParams);
    }

    public function executeComponent()
    {
        if(! Loader::includeModule('maxima.search')){
            echo Loc::getMessage("MXM_SEARCH_MODULE");
            return;
        }

        $arResult["FORM_ACTION"] = htmlspecialcharsbx(str_replace("#SITE_DIR#", SITE_DIR, $this->arParams["PAGE"]));

        $this->includeComponentTemplate();
    }

}