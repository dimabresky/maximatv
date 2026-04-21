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

        $tariffCode = Application::getInstance()->getContext()->getRequest()->get('tariff');
        if (empty($tariffCode)) {
            throw new Exception('Invalid code!');
        }

        $this->arResult['USER'] = CUser::GetByID($USER->GetID())->Fetch();
        $this->arResult['TARIFF'] = TariffHelper::getTariffByCode($tariffCode);

        if (!TariffHelper::isTariffAvailable($this->arResult['USER']['ID'], $this->arResult['TARIFF']['ID'])) {
            $this->arResult['MESSAGE'] = 'Tariff is not available!';
            $this->includeComponentTemplate();
            return;
        }

        if (!TariffHelper::isTariffUpgradeAvailable($this->arResult['USER']['ID'], $this->arResult['TARIFF']['ID'])) {
            $this->arResult['MESSAGE'] = 'Вы уже используете тариф с расширенным доступом';
            $this->includeComponentTemplate();
            return;
        }

        if ($this->arResult['TARIFF']['PROPERTY_PRICE_VALUE'] == 0) {
            TariffHelper::applyTariff($this->arResult['USER']['ID'], $this->arResult['TARIFF']['ID']);
            $this->arResult['MESSAGE'] = 'Ура! Тариф "' . $this->arResult['TARIFF']['NAME'] . '" активирован. Приятного просмотра!';
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
            'ORDER_TYPE' => $orderTypes['tariff']['ID'],
            'PRICE' => $this->arResult['TARIFF']['PROPERTY_PRICE_VALUE'],
            'TARIFF_ID' => $this->arResult['TARIFF']['ID'],
            'STATUS' => $orderStatuses['CREATED']['ID'],
        ];

        $element = new CIBlockElement();
        $orderId = $element->Add([
            'IBLOCK_ID' => $orderIblock['ID'],
            'ACTIVE' => 'Y',
            'NAME' => $this->arResult['USER']['ID'] . ' - Оплата подписки по тарифу ' . $this->arResult['TARIFF']['NAME'] . ' - ' . date('d.m.Y H:i:s'),
            'PROPERTY_VALUES' => $orderData,
        ]);

        if ($orderId === false) {
            throw new Exception('Внутренняя ошибка. Заказ не может быть создан! ' . $element->LAST_ERROR . var_export($orderIblock));
        }

        $_SESSION['ODRER_ID'] = $orderId;

        LocalRedirect('/lk/pay/');
	}
}