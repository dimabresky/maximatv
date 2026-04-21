<?php

namespace Maxima\Helpers;

use Bitrix\Main\Type\DateTime;

class TariffHelper
{
    private static $arTariffs = [];
    private static $arUsers = [];
    private static $tariffProperties = ['ID', 'IBLOCK_ID', 'ACTIVE', 'NAME', 'CODE', 'SORT', 'PREVIEW_TEXT', 'DETAIL_TEXT',
        'PROPERTY_PRICE', 'PROPERTY_PERIOD', 'PROPERTY_ONLY_ONE', 'PROPERTY_CSS_CLASS', 'PROPERTY_PRIORITY',
        'PROPERTY_BROADCASTS_ACCESS', 'PROPERTY_EVENTS_ACCESS', 'PROPERTY_COMPETITIONS_ACCESS'];

    //======= Public methods ========
    public static function getTariffById($tariffId)
    {
        \CModule::IncludeModule('iblock');
        if (!isset(self::$arTariffs[$tariffId])) {
            $tariffIBlock = CommonHelper::getIblock('pay_content', 'tariffs');
            $tariff = \CIBlockElement::GetList(
                [],
                ['IBLOCK_ID' => $tariffIBlock['ID'], 'ID' => $tariffId],
                false,
                false,
                self::$tariffProperties
            )->GetNext();

            if ($tariff !== false) {
                self::$arTariffs[$tariffId] = $tariff;
            }
        }

        return self::$arTariffs[$tariffId];
    }

    public static function getTariffByCode($tariffCode)
    {
        foreach (self::$arTariffs as $item) {
            if ($item['CODE'] === $tariffCode) {
                return $item;
            }
        }

        $tariffIBlock = CommonHelper::getIblock('pay_content', 'tariffs');
        $tariff = \CIBlockElement::GetList(
            [],
            ['IBLOCK_ID' => $tariffIBlock['ID'], 'CODE' => $tariffCode],
            false,
            false,
            self::$tariffProperties
        )->GetNext();
        if ($tariff !== false && !isset(self::$arTariffs[$tariff['ID']])) {
            self::$arTariffs[$tariff['ID']] = $tariff;
        }

        return $tariff;
    }

    public static function getTariffsAvailable()
    {
        $result = [];

        $tariffIBlock = CommonHelper::getIblock('pay_content', 'tariffs');
        $dbRes = \CIBlockElement::GetList(
            ['PROPERTY_PRIORITY' => 'ASC'],
            ['IBLOCK_ID' => $tariffIBlock['ID'], 'ACTIVE' => 'Y'],
            false,
            false,
            self::$tariffProperties
        );
        while ($tariff = $dbRes->GetNext()) {
            $result[] = $tariff;
            self::$arTariffs[$tariff['ID']] = $tariff;
        }

        return $result;
    }

    public static function getUserById($userId)
    {
        if (!isset(self::$arUsers[$userId])) {
            $user = \CUser::GetByID($userId)->Fetch();
            self::$arUsers[$userId] = $user;
        }

        return self::$arUsers[$userId];
    }

    public static function isPaidUser($userId)
    {
        $user = self::getUserById($userId);

        if (!empty($user['UF_TARIFF_ACTIVE_TO'])) {
            $timeActive = new \DateTime($user['UF_TARIFF_ACTIVE_TO']);

            return ($timeActive->getTimestamp() > time());
        }

        return false;
    }

    public static function applyTariff($userId, $tariffId)
    {
        $tariff = self::getTariffById($tariffId);
        self::addPaidTime($userId, $tariff);
        $user = self::getUserById($userId);
        self::logToHL([
            'UF_USER_ID' => $user['ID'],
            'UF_TARIFF_ID' => $tariff['ID'],
            'UF_PERIOD' => $tariff['PROPERTY_PERIOD_VALUE'],
            'UF_SUBSCRIPTION_TO' => $user['UF_TARIFF_ACTIVE_TO'],
            'UF_CREATED_AT' => (new \DateTime())->format('d.m.Y H:i:s'),
        ]);
    }

    public static function addPaidTime($userId, $tariff)
    {
        $activeTariff = self::getUserCurrentTariff($userId);

        if (is_array($activeTariff) && $activeTariff['UF_TARIFF_ID'] === $tariff['ID']) {
            $timeActiveTo = $activeTariff['UF_SUBSCRIPTION_TO'];
            $timeActiveTo->add(strtoupper($tariff['PROPERTY_PERIOD_VALUE']));
            self::updateUserTariff($activeTariff['ID'], [
                'UF_SUBSCRIPTION_TO' => $timeActiveTo,
            ]);

            return;
        }

        if ($activeTariff !== null) {
            self::freezeUserTariff($activeTariff);
        }
        $timeActiveTo = new DateTime();
        $timeActiveTo->add(strtoupper($tariff['PROPERTY_PERIOD_VALUE']));
        self::addUserTariff([
            'UF_USER_ID' => $userId,
            'UF_TARIFF_ID' =>  $tariff['ID'],
            'UF_STATUS' => 'active',
            'UF_SUBSCRIPTION_TO' => $timeActiveTo,
            'UF_REMINDER' => ''
        ]);
    }

    public static function isTariffAvailable($userId, $tariffId)
    {
        $tariff = self::getTariffById($tariffId);
        if ($tariff['ACTIVE'] !== 'Y') {
            return false;
        }
        if ($tariff['PROPERTY_ONLY_ONE_VALUE'] !== 'Y') {
            return true;
        }

        return self::getTariffUsedCount($userId, $tariffId) === 0;
    }

    public static function isTariffUpgradeAvailable($userId, $tariffId)
    {
        $activeTariff = self::getUserCurrentTariff($userId);
        // Если нет активного тарифа - разрешаем апгрейд
        if ($activeTariff === null) {
            return true;
        }

        // Если у нового тарифа индекс сортировки меньше (он более крутой) - разрешаем апгрейд
        $allTariffs = self::getTariffsAvailable();
        foreach ($allTariffs as $tariff) {
            if ($tariff['ID'] == $tariffId) {
                return true;
            }
            if ($tariff['ID'] == $activeTariff['UF_TARIFF_ID']) {
                return false;
            }
        }

        // Если попали сюда, значит нового тарифа нет в списке - хрень какая-то
        return false;
    }

    public static function getTariffUsedCount($userId, $tariffId)
    {
        $hlDataClass = HighloadHelper::getTariffPurchasesHlDataClass();
        $dbRes = $hlDataClass::getList([
            'select' => ['ID'],
            'filter' => ['UF_USER_ID' => $userId, 'UF_TARIFF_ID' => $tariffId]]
        );

        return $dbRes->getSelectedRowsCount();
    }

    public static function getUserCurrentTariff($userId)
    {
        $activeTariff = null;
        $allTariffs = [];

        $hlDataClass = HighloadHelper::getUserTariffHlDataClass();
        $dbRes = $hlDataClass::getList([
            'select' => ['*'],
            'filter' => ['UF_USER_ID' => $userId]
        ]);

        while ($item = $dbRes->fetch()){
//            $allTariffs[(int)$item['UF_TARIFF_ID']] = $item;
            $allTariffs[] = $item;
        }

        if (count($allTariffs) === 0) {

            return null;
        }

        $activeTariff = self::recalculateUserTariffs($allTariffs);

        return $activeTariff;
    }

    public static function getUserCurrentTariffFilter($userId, $itemType)
    {
        \CModule::IncludeModule('iblock');
        $result = [];

        $iblockId = \CIBlock::GetList([], ['CODE' => $itemType])->Fetch()['ID'];
        if ($iblockId == null) {

            return null;
        }

        $accessEnumItems = [];;
        $dbRes = \CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $iblockId, 'CODE' => 'ACCESS']);
        while ($item = $dbRes->Fetch()) {
            $accessEnumItems[$item['XML_ID']] = $item['ID'];
        }
        $registeredUsersAccessId = $accessEnumItems['registered'];

        if (empty($userId)) {
            $result['PROPERTY_ACCESS'] = $accessEnumItems['free'];

            return $result;
        }

        $activeTariff = self::getUserCurrentTariff($userId);

        if ($activeTariff === null) {
            $result['PROPERTY_ACCESS'] = $accessEnumItems['free'];

            return $result;
        }
        $tariff = self::getTariffById($activeTariff['UF_TARIFF_ID']);

        $itemAccessId = $tariff['PROPERTY_' . strtoupper($itemType) . '_ACCESS_ENUM_ID'];
        $tariffEnumItem = \CIBlockPropertyEnum::GetList([], [
            'IBLOCK_ID' => $tariff['IBLOCK_ID'],
            'CODE' => strtoupper($itemType) . '_ACCESS',
            'ID' => $itemAccessId
        ])->Fetch();

        // Теперь оставим текущий режим доступа и более "младшие"
        foreach ($accessEnumItems as $xmlId => $itemId) {
            if ($xmlId == $tariffEnumItem['XML_ID']) {
                break;
            }
            unset($accessEnumItems[$xmlId]);
        }
        $result = $accessEnumItems;

        //Если пользователь авторизован, добавим соответствующий вид доступа
        if ($userId !== null) {
            $result['registered'] = $registeredUsersAccessId;
        }

        return $result;
    }

    //======= Private methods ========

    private static function logToHL($arData)
    {
        $hlDataClass = HighloadHelper::getTariffPurchasesHlDataClass();
        $hlDataClass::add($arData);
    }

    private static function deleteUserTariff($id)
    {
        $hlDataClass = HighloadHelper::getUserTariffHlDataClass();
        $hlDataClass::delete($id);
    }

    private static function updateUserTariff($id, $data)
    {
        $hlDataClass = HighloadHelper::getUserTariffHlDataClass();
        $hlDataClass::update($id, $data);
    }

    private static function addUserTariff($data)
    {
        $hlDataClass = HighloadHelper::getUserTariffHlDataClass();
        $hlDataClass::add($data);
    }

    private static function freezeUserTariff($activeTariff)
    {
        $timeActiveTo = new \DateTime($activeTariff['UF_SUBSCRIPTION_TO']->format('Y-m-d H:i:s'));
        $now = new \DateTime();
        $diff = $timeActiveTo->diff($now)->format('%a') . 'd';
        self::updateUserTariff($activeTariff['ID'], [
            'UF_STATUS' => 'frozen',
            'UF_SUBSCRIPTION_TO' => '',
            'UF_REMINDER' => $diff
        ]);
    }

    private static function recalculateUserTariffs($userTariffs)
    {
        $now = new DateTime();
        $tariffs = [];
        $activeTariff = null;

        foreach ($userTariffs as $item) {
            if ($item['UF_STATUS'] === 'active') {
                $activeTariff = $item;
                if ($activeTariff['UF_SUBSCRIPTION_TO']->getTimestamp() > $now->getTimestamp()) {

                    return $activeTariff;
                }
            } else {
                $tariff = self::getTariffById($item['UF_TARIFF_ID']);
                $tariffs[(int)$tariff['PROPERTY_PRIORITY_VALUE']] = $tariff;
            }
        }

        if (count($tariffs) === 0) {

            return null;
        }
        self::deleteUserTariff($activeTariff['ID']);

        ksort($tariffs);
        $timeActiveTo = $activeTariff['UF_SUBSCRIPTION_TO'];
        while (count($tariffs) > 0) {
            $tariff = array_shift($tariffs);
            $userTariff = self::getUserTariffByTariffId($userTariffs, $tariff['ID']);
            $timeActiveTo->add(strtoupper($userTariff['UF_REMINDER']));
            if ($timeActiveTo > $now) {
                self::updateUserTariff($userTariff['ID'], [
                    'UF_USER_ID' => $userTariff['UF_USER_ID'],
                    'UF_TARIFF_ID' =>  $userTariff['UF_TARIFF_ID'],
                    'UF_STATUS' => 'active',
                    'UF_SUBSCRIPTION_TO' => $timeActiveTo,
                    'UF_REMINDER' => ''
                ]);

                return $tariff;
            }
            self::deleteUserTariff($userTariff['ID']);
        }

        return null;
    }

    private static function getUserTariffByTariffId($userTariffs, $tariffId)
    {
        $tariffId = (int)$tariffId;
        foreach ($userTariffs as $item) {
            if ((int)$item['UF_TARIFF_ID'] == $tariffId) {

                return $item;
            }
        }

        return null;
    }
}