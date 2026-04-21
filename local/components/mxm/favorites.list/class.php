<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Localization\Loc;
use Maxima\Helpers\FavoritesHelper;

class FavoritesList extends \CBitrixComponent
{

	/**
	 * подключает языковые файлы
	 */
	public function onIncludeComponentLang()
	{
		$this->includeComponentLang(basename(__FILE__));
		Loc::loadMessages(__FILE__);
	}

	/**
	 * подготавливает входные параметры
	 * @param array $arParams
	 * @return array
	 */
	public function onPrepareComponentParams($params)
	{
		return $params;
	}

	/**
	 * выполняет логику работы компонента
	 */
	public function executeComponent()
	{
	    global $USER;

	    $fvHelper = new FavoritesHelper();
	    $arItems = [];
	    $arSections = [];
        $arSectionIds = [];

	    $dbRes = CIBlockElement::GetList(
	        ['IBLOCK_ID' => 'DESC', 'ID' => 'DESC'],
            ['IBLOCK_ID' => [4,5,6], 'ID' => $fvHelper->getUserFavoriteItems($USER->GetID()), 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'],
            false,
            false,
            ['ID', 'IBLOCK_ID', '*']
        );
	    while ($element = $dbRes->GetNextElement()) {
            $fields = $element->GetFields();
            $properties = $element->GetProperties();
            $arItems[] = [
                'FIELDS' => $fields,
                'PROPERTIES' => $properties,
            ];
            if ($fields['IBLOCK_ID'] == 6) {
                $arSectionIds[] = $fields['IBLOCK_SECTION_ID'];
            }
        }

        if (count($arSectionIds) > 0) {
            $arSectionIds = array_unique($arSectionIds);
            $dbRes = CIBlockSection::GetList([], ['IBLOCK_ID' => 6, 'ID' => $arSectionIds], false, ['ID', 'IBLOCK_ID', 'NAME', 'PICTURE', 'UF_DATE', 'UF_PLACE', 'UF_DISCIPLINE']);
            while ($item = $dbRes->GetNext()) {
                $arSections[(int)$item['ID']] = $item;
            }
        }

        foreach ($arItems as $element) {
	        $item = [
	            'ID' => $element['FIELDS']['ID'],
                'NAME' => $element['FIELDS']['NAME']
            ];
	        switch ((int)$element['FIELDS']['IBLOCK_ID']) {
                case 4:
                    $item['DATE'] = explode(' ', $element['PROPERTIES']['LIVE_FROM'])[0];
                    $item['PLACE'] = $element['PROPERTIES']['PLACE']['VALUE'];
                    $item['DISCIPLINE'] = $element['PROPERTIES']['DISCIPLINE']['VALUE'];
                    $disciplineCode = $element['PROPERTIES']['DISCIPLINE']['VALUE_XML_ID'];
                    if ($disciplineCode !== 'jumping') {
                        $disciplineCode = 'dressage';
                    }
                    if ($element['FIELDS']['PREVIEW_PICTURE']) {
                        $preview = \CFile::ResizeImageGet(
                            $element['FIELDS']['PREVIEW_PICTURE'],
                            ['width' => 483, 'height' => 241],
                            BX_RESIZE_IMAGE_EXACT
                        );
                    } else {
                        $preview = [
                            'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'broadcast.' . $disciplineCode . '.jpg'
                        ];
                    }
                    $item['PICTURE'] = $preview['src'];
                    $item['LINK'] = $element['FIELDS']['DETAIL_PAGE_URL'];
                    break;
                case 5:
                    $item['DATE'] = $element['PROPERTIES']['DATE_INTERVAL']['VALUE'];
                    $item['PLACE'] = $element['PROPERTIES']['PLACE']['VALUE'];
                    $item['DISCIPLINE'] = $element['PROPERTIES']['DISCIPLINE']['VALUE'];
                    if ($element['FIELDS']['PREVIEW_PICTURE']) {
                        $preview = \CFile::ResizeImageGet(
                            $element['FIELDS']['PREVIEW_PICTURE'],
                            ['width' => 483, 'height' => 241],
                            BX_RESIZE_IMAGE_EXACT
                        );
                    } else {
                        $preview = [
                            'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'event.jpg'
                        ];
                    }
                    $item['PICTURE'] = $preview['src'];
                    $item['LINK'] = $element['FIELDS']['DETAIL_PAGE_URL'];
                    break;
                case 6:
                    $section = $arSections[(int)$element['FIELDS']['IBLOCK_SECTION_ID']];
                    $item['NAME'] = $section['NAME'] . ': ' . $item['NAME'];
                    $item['DATE'] = $section['UF_DATE'];
                    $item['PLACE'] = $section['UF_PLACE'];
                    $disciplineCode = 'dressage';
                    switch ((int)$section['UF_DISCIPLINE']) {
                        case 1:
                            $item['DISCIPLINE'] = 'Выездка';
                            break;
                        case 2:
                            $item['DISCIPLINE'] = 'Конкур';
                            $disciplineCode = 'jumping';
                            break;
                        case 3:
                            $item['DISCIPLINE'] = 'Троеборье';
                            break;
                    };
                    if ($section['PICTURE']['ID']) {
                        $preview = \CFile::ResizeImageGet(
                            $section['PICTURE']['ID'],
                            ['width' => 483, 'height' => 241],
                            BX_RESIZE_IMAGE_EXACT
                        );
                    } else {
                        $preview = [
                            'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg'
                        ];
                    }
                    $item['PICTURE'] = $preview['src'];
                    $item['LINK'] = $element['FIELDS']['DETAIL_PAGE_URL'];
                    break;
            }
	        $this->arResult['ITEMS'][] = $item;
        }

		$this->IncludeComponentTemplate();
	}


}