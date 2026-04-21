<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Maxima\Helpers\CommonHelper;
use Maxima\Helpers\TariffHelper;

class TariffPaymentCreate extends \CBitrixComponent
{
	/**
	 * выполняет логику работы компонента
	 */
	public function executeComponent()
	{
        global $USER;
        Loader::includeModule('iblock');

        $broadcastId = Application::getInstance()->getContext()->getRequest()->get('broadcast');
        if (empty($broadcastId)) {
            throw new Exception('Invalid code!');
        }

        $this->arResult['USER'] = CUser::GetByID($USER->GetID())->Fetch();
        $this->arResult['BROADCAST'] = CommonHelper::getBroadcastById($broadcastId);

        if ($this->arResult['BROADCAST'] === null) {
            $this->arResult['MESSAGE'] = 'Трансляция недоступна!';
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

        $orderData = [
            'USER_ID' => $this->arResult['USER']['ID'],
            'ORDER_TYPE' => $orderTypes['broadcast']['ID'],
            'PRICE' => (int)$this->arResult['BROADCAST']['PROPERTIES']['PRICE']['VALUE'],
            'BROADCAST_ID' => $this->arResult['BROADCAST']['ID'],
            'STATUS' => $orderStatuses['CREATED']['ID'],
        ];

        $element = new CIBlockElement();
        $orderId = $element->Add([
            'IBLOCK_ID' => $orderIblock['ID'],
            'ACTIVE' => 'Y',
            'NAME' => $this->arResult['USER']['ID'] . ' - Оплата трансляции ' . $this->arResult['BROADCAST']['NAME'] . ' - ' . date('d.m.Y H:i:s'),
            'PROPERTY_VALUES' => $orderData,
        ]);

        if ($orderId === false) {
            throw new Exception('Внутренняя ошибка. Заказ не может быть создан! ' . $element->LAST_ERROR . var_export($orderIblock));
        }

        $_SESSION['ODRER_ID'] = $orderId;

        LocalRedirect('/lk/pay/');
	}
}