<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;
use Maxima\Options\Options;
use Maxima\Helpers;
use Bitrix\Main\Application;
use Bitrix\Highloadblock\HighloadBlockTable as Highload;
use Bitrix\Main\Loader;

class BasketPage extends \CBitrixComponent
{
    const PHOTO_PRICE_HIGHLOAD = 6;

    /*
     * @var Maxima\Helpers\BasketHelper $basket
     */
    protected $basket;

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

        if ($USER->IsAuthorized()) {
            $this->basket = new Helpers\BasketHelper();
            $this->processRequest();
            $this->getBasket();
        }
        $this->getPhotoPriceList();
        $this->includeComponentTemplate();
	}

	protected function processRequest()
    {
         $request = Application::getInstance()->getContext()->getRequest();
         $this->arResult['IS_AJAX'] = $request->isAjaxRequest() ? true: false;

         if($request->get('action_type') !== 'basket_page'){
             return;
         }
         $action = $request->get('action');
         if($action == 'clear'){
             $this->basket->clear();
             echo 'true';
             exit;
         }
         $id = $request->get('id');
        if($action == 'remove'){
            $this->basket->removeItem($id);
            $items  = $this->basket->getItems();
            foreach($items as $k => $arItem){
                $items[$k] = [
                    'ID' => $arItem['ID'],
                    'PRICE' => $arItem ['PRICE']
                ];
            }
            $sum = number_format($this->basket->getSum(), 0 , "," , " " );
            echo json_encode([
                'ITEMS' => $items,
                'SUM' => $sum
            ]);
            exit;
        }

    }

	protected function getBasket()
    {
        $items = $this->basket->getItems();
        $sum = $this->basket->getSum();
        $count  = $this->basket->getCount();
        $this->arResult['BASKET'] = [
            'ITEMS' => $items,
            'SUM' => $sum,
            'COUNT' => $count
        ];
        $this->getPhotoInformation();
    }

    protected function getPhotoInformation()
    {
        $photoIdList = [];
        $photoList = [];
        $arResult['PHOTO_LIST'] = [];
        if(empty($this->arResult['BASKET']['ITEMS'])){
            return;
        }
        foreach($this->arResult['BASKET']['ITEMS'] as $k => $arItem){
            if(!empty($arItem['PHOTO'])) {
                $photoIdList[] = $arItem['PHOTO'];
            }
        }

        $res = \CFile::GetList([], ['@ID' => implode(',', $photoIdList)]);
        while($arItem = $res->Fetch()){
            $photoList[$arItem['ID']] = [
                'WIDTH' => $arItem['WIDTH'],
                'HEIGHT' => $arItem['HEIGHT'],
                'CONTENT_TYPE' => $arItem['CONTENT_TYPE'],
                'ID' => $arItem['ID']
            ];
        }

        foreach($this->arResult['BASKET']['ITEMS'] as $k => $arItem){
            if(!empty($photoList[$arItem['PHOTO']])) {
                $this->arResult['BASKET']['ITEMS'][$k]['PHOTO'] =  $photoList[$arItem['PHOTO']];
            }
        }

    }

    protected function getPhotoPriceList()
    {
        Loader::includeModule('highloadblock');
        $this->arResult['PHOTO_PRICE'] = [];
        $photoPriceHl = Highload::getById(self::PHOTO_PRICE_HIGHLOAD)->fetch();
        $photoPriceEntity = Highload::compileEntity($photoPriceHl);
        $photoPriceClass = $photoPriceEntity->getDataClass();
        $results = $photoPriceClass::getList([
            'order' => ['UF_PHOTO_COUNT' => 'asc']
        ]);
        $elements = $results->fetchAll();
        foreach ($elements as $item) {
            $this->arResult['PHOTO_PRICE'][] = $item;
        }
    }

}