<?php

namespace Maxima\Helpers;

use Bitrix\Main\Loader;

class PaymentsHelper
{
    private $arResult = [];
    private $order = null;

    //======= Public methods ========
    public function __construct($arDetails)
    {
        $this->arResult = $arDetails;
    }

    public function writeTransaction($result)
    {
        $arData = [
            'UF_USER_ID'    => $this->arResult['AccountId'],
            'UF_ORDER_ID'   => $this->arResult['InvoiceId'],
            'UF_RESULT'     => $result,
            'UF_CREATED_AT' => (new \DateTime())->format('d.m.Y H:i:s'),
            'UF_DETAILS'    => json_encode($this->arResult),
        ];

        $this->logToTransactionHL($arData);
    }

    public function updateOrder($statusCode)
    {
        Loader::includeModule('iblock');

        $orderIblock = CommonHelper::getIblock('pay_content', 'orders');

        $orderStatuses = [];
        $dbRes = \CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $orderIblock['ID'], 'CODE' => 'STATUS']);
        while ($item = $dbRes->GetNext()) {
            $orderStatuses[$item['XML_ID']] = $item;
        }
        if (!isset($orderStatuses[$statusCode])) {
            return;
        }

        if ($this->order === null) {
            $this->getOrder();
        }
        if ($this->order === false) {
            return;
        }

        \CIBlockElement::SetPropertyValuesEx($this->order['ID'], $orderIblock['ID'], ['STATUS' => $orderStatuses[$statusCode]]);
    }

    public function getOrderTypeCode()
    {
        if ($this->order === null) {
            $this->getOrder();
        }
        if ($this->order === false) {
            return null;
        }

        $orderIblock = CommonHelper::getIblock('pay_content', 'orders');
        $orderType = \CIBlockPropertyEnum::GetList(
            ['SORT' => 'ASC'],
            ['IBLOCK_ID' => $orderIblock['ID'], 'CODE' => 'ORDER_TYPE', 'ID' => $this->order['PROPERTY_ORDER_TYPE_ENUM_ID']]
        )->GetNext();
        if ($orderType === false) {
            return null;
        }

        return $orderType['XML_ID'];
    }

    public function applyTariff()
    {
        if ($this->order === null) {
            $this->getOrder();
        }
        if ($this->order === false) {
            return;
        }
        if ($this->order['PROPERTY_USER_ID_VALUE'] != $this->arResult['AccountId']) {
            return;
        }

        TariffHelper::applyTariff($this->order['PROPERTY_USER_ID_VALUE'], $this->order['PROPERTY_TARIFF_ID_VALUE']);
    }

    public function setBroadcastAccess()
    {
        if ($this->order === null) {
            $this->getOrder();
        }
        if ($this->order === false) {
            return;
        }
        if ($this->order['PROPERTY_USER_ID_VALUE'] != $this->arResult['AccountId']) {
            return;
        }

        $arData = [
            'UF_USER_ID'    => $this->arResult['AccountId'],
            'UF_BROADCAST_ID'   => $this->order['PROPERTY_BROADCAST_ID_VALUE'],
            'UF_CREATED_AT' => (new \DateTime())->format('d.m.Y H:i:s'),
        ];
        $this->writeBroadcastToHl($arData);
        $this->sendBroadcastNotification();
    }

    //======= Private methods ========
    private function logToTransactionHL($arData)
    {
        $hlDataClass = HighloadHelper::getTransactionHlDataClass();
        $hlDataClass::add($arData);
    }

    private function writeBroadcastToHl($arData)
    {
        $hlDataClass = HighloadHelper::getBroadcastHlDataClass();
        $hlDataClass::add($arData);
    }

    private function getOrder()
    {
        Loader::includeModule('iblock');

        $orderIblock = CommonHelper::getIblock('pay_content', 'orders');
        $this->order = \CIBlockElement::GetList([],
            ['IBLOCK_ID' => $orderIblock['ID'], 'ID' => $this->arResult['InvoiceId'], 'ACTIVE' => 'Y'],
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
    }

    private function sendBroadcastNotification()
    {
        if ($this->order === null) {
            $this->getOrder();
        }
        if ($this->order === false) {
            return;
        }

        $user = \CUser::GetByID($this->order['PROPERTY_USER_ID_VALUE'])->Fetch();
        $broadcast = CommonHelper::getBroadcastById($this->order['PROPERTY_BROADCAST_ID_VALUE']);
        if ($user === false || $broadcast === null) {
            return;
        }

        $arEventFields = [
            'EMAIL_TO' => $user['EMAIL'],
            'BROADCAST_NAME' => $broadcast['NAME'],
            'BROADCAST_LINK' => '/broadcasts/' . $broadcast['CODE'] . '/',
        ];
        \CEvent::Send('BROADCAST_ACCESS_SET', SITE_ID, $arEventFields, "N", 32);
    }
}