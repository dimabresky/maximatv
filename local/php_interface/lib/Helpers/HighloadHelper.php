<?php

namespace Maxima\Helpers;

use Bitrix\Highloadblock\HighloadBlockTable as Highload;

class HighloadHelper
{
    private static $tariffPurchasesHlId = 1;
    private static $transactionsHlId = 2;
    private static $userTariffsHlId = 3;
    private static $broadcastsHlId = 4;
    private static $favoritesHlId = 5;

    public static function getTariffPurchasesHlDataClass()
    {
        \CModule::IncludeModule('highloadblock');
        $hlBlock = Highload::getById(self::$tariffPurchasesHlId)->fetch();
        $hlEntity = Highload::compileEntity($hlBlock);

        return $hlEntity->getDataClass();
    }

    public static function getTransactionHlDataClass()
    {
        \CModule::IncludeModule('highloadblock');
        $hlBlock = Highload::getById(self::$transactionsHlId)->fetch();
        $hlEntity = Highload::compileEntity($hlBlock);

        return $hlEntity->getDataClass();
    }

    public static function getUserTariffHlDataClass()
    {
        \CModule::IncludeModule('highloadblock');
        $hlBlock = Highload::getById(self::$userTariffsHlId)->fetch();
        $hlEntity = Highload::compileEntity($hlBlock);

        return $hlEntity->getDataClass();
    }

    public static function getBroadcastHlDataClass()
    {
        \CModule::IncludeModule('highloadblock');
        $hlBlock = Highload::getById(self::$broadcastsHlId)->fetch();
        $hlEntity = Highload::compileEntity($hlBlock);

        return $hlEntity->getDataClass();
    }

    public static function getFavoritesHlDataClass()
    {
        \CModule::IncludeModule('highloadblock');
        $hlBlock = Highload::getById(self::$favoritesHlId)->fetch();
        $hlEntity = Highload::compileEntity($hlBlock);

        return $hlEntity->getDataClass();
    }
}