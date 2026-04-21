<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Maxima\Helpers\CommonHelper;
use Maxima\Helpers\TariffHelper;

class PaymentPay extends \CBitrixComponent
{
	/**
	 * выполняет логику работы компонента
	 */
	public function executeComponent()
	{
        global $USER;

        Loader::includeModule('iblock');

        $orderId = $_SESSION['ODRER_ID'];
        if ((int)$orderId <= 0) {
            throw new Exception('Invalid order ID!');
        }

        $orderIblock = CommonHelper::getIblock('pay_content', 'orders');
        $orderFilter = ['IBLOCK_ID' => $orderIblock['ID'], 'ID' => $orderId, 'ACTIVE' => 'Y'];
        $order = CIBlockElement::GetList([],
            $orderFilter,
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
                'NAME',
                'PROPERTY_USER_ID',
                'PROPERTY_ORDER_TYPE',
                'PROPERTY_PRICE',
                'PROPERTY_TARIFF_ID',
                'PROPERTY_BROADCAST_ID',
            ]
        )->GetNext();

        if ($order === false) {
            throw new Exception('Invalid order ID!');
        }
        if ($order['PROPERTY_USER_ID_VALUE'] != $USER->GetID()) {
            throw new Exception('Invalid order user!');
        }

        $this->arResult['ORDER_ID'] = $orderId;
        $this->arResult['USER'] = CUser::GetByID($order['PROPERTY_USER_ID_VALUE'])->Fetch();
        $this->arResult['PRICE'] = $order['PROPERTY_PRICE_VALUE'];

        switch ($order['PROPERTY_ORDER_TYPE_VALUE']) {
            case 'Подписка':
                $this->arResult['TARIFF'] = TariffHelper::getTariffById($order['PROPERTY_TARIFF_ID_VALUE']);
                $this->arResult['DESCRIPTION'] = 'Maxima TV: Оплата продления подписки по тарифу ' . $this->arResult['TARIFF']['NAME'];
                $this->arResult['BACKURL'] = '/lk/subscribe/';
                $this->arResult['ORDER_ITEMS']  = [
                    [
                        'LABEL' => "Тариф '" . $this->arResult['TARIFF']['NAME'] . "'",
                        'PRICE' => $this->arResult['PRICE'],
                        'QUANTITY' => 1,
                        'AMOUNT' => $this->arResult['PRICE'],
                        'VAT' => 0,
                        'UNIT' => "шт"
                    ]
                ];
                break;
            case 'Трансляция':
                $this->arResult['BROADCAST'] = CommonHelper::getBroadcastById($order['PROPERTY_BROADCAST_ID_VALUE']);
                $this->arResult['DESCRIPTION'] = 'Maxima TV: Оплата трансляции ' . $this->arResult['BROADCAST']['NAME'];
                $this->arResult['BACKURL'] = '/lk/history/';
                $this->arResult['ORDER_ITEMS']  = [
                    [
                        'LABEL' => 'Трансляция ' . $this->arResult['BROADCAST']['NAME'],
                        'PRICE' => $this->arResult['PRICE'],
                        'QUANTITY' => 1,
                        'AMOUNT' => $this->arResult['PRICE'],
                        'VAT' => 0,
                        'UNIT' => "шт"
                    ]
                ];
                break;
            case 'Фотографии':
                $rs = CIBlockElement::GetList([], $orderFilter, false, false, ['PROPERTY_PHOTO']);
                $photoCount = 0;
                while($arItem  =  $rs->Fetch()){
                    $photoCount++;
                }
                $itemPrice = round($this->arResult['PRICE'] / $photoCount);
                $this->arResult['ORDER_ITEMS']  = [
                    [
                        'LABEL' => 'Фотография',
                        'PRICE' => $itemPrice,
                        'QUANTITY' => $photoCount,
                        'AMOUNT' => $this->arResult['PRICE'],
                        'VAT' => 0,
                        'UNIT' => "шт"
                    ]
                ];
                $this->arResult['DESCRIPTION'] = 'Maxima TV: Покупка фотографий';
                $this->arResult['BACKURL'] = '/lk/history/';
                break;
            default:
                throw new Exception('Invalid order type!');
        }

        $this->includeComponentTemplate();
	}

}