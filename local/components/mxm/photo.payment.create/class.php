<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Maxima\Helpers\CommonHelper;
use Maxima\Helpers\BasketHelper;

class PhotoPaymentCreate extends \CBitrixComponent
{

	public function executeComponent()
	{
        global $USER;
        $userId = $USER->GetID();

        Loader::includeModule('iblock');

        $basket = new BasketHelper();
        $items = $basket->getItems();

        if (empty($items)) {
            $this->arResult['MESSAGE'] = 'Ваша корзина пуста';
            $this->includeComponentTemplate();
            return;
        }


        $orderIblock = CommonHelper::getIblock('pay_content', 'orders');
        $orderTypes = [];
        $dbRes = CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $orderIblock['ID'], 'CODE' => 'ORDER_TYPE']);
        while ($item = $dbRes->GetNext()) {
            $orderTypes[$item['XML_ID']] = $item;
        }
        $orderStatuses = [];
        $dbRes = CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $orderIblock['ID'], 'CODE' => 'STATUS']);
        while ($item = $dbRes->GetNext()) {
            $orderStatuses[$item['XML_ID']] = $item;
        }

        $photoList = [];
        foreach($items as $key => $item){
            $photoList[$key] = [
                'VALUE' => CFile::MakeFileArray(CFile::GetPath($item['PHOTO'])),
                'DESCRIPTION' => 'Фото ' . ($key + 1)
            ];
        }

        $orderData = [
            'USER_ID' => $USER->GetID(),
            'ORDER_TYPE' => $orderTypes['photo']['ID'],
            'PRICE' => $basket->getSum(),
            'STATUS' => $orderStatuses['CREATED']['ID'],
            'PHOTO' => $photoList
        ];

        $element = new CIBlockElement();
        $orderId = $element->Add([
            'IBLOCK_ID' => $orderIblock['ID'],
            'ACTIVE' => 'Y',
            'NAME' => $userId . ' - Оплата фото - ' . date('d.m.Y H:i:s'),
            'PROPERTY_VALUES' => $orderData,
        ]);

        if ($orderId === false) {
            throw new Exception('Внутренняя ошибка. Заказ не может быть создан! ' . $element->LAST_ERROR . var_export($orderIblock));
        }

        $_SESSION['ODRER_ID'] = $orderId;

        LocalRedirect('/lk/pay/');
	}



}