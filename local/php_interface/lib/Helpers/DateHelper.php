<?php

namespace Maxima\Helpers;

class DateHelper
{
    private static $literalMonths = [
        '01' => 'Январь',
        '02' => 'Февраль',
        '03' => 'Март',
        '04' => 'Апрель',
        '05' => 'Май',
        '06' => 'Июнь',
        '07' => 'Июль',
        '08' => 'Август',
        '09' => 'Сентябрь',
        '10' => 'Октябрь',
        '11' => 'Ноябрь',
        '12' => 'Декабрь',
    ];
    private static $literalMonthsDeclination = [
        '01' => 'января',
        '02' => 'февраля',
        '03' => 'марта',
        '04' => 'апреля',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'августа',
        '09' => 'сентября',
        '10' => 'октября',
        '11' => 'ноября',
        '12' => 'декабря',
    ];

    //======= Public methods ========
    public static function getMonthByIndex($index)
    {
        return self::$literalMonths[$index];
    }

    public static function getAllLteralMonths()
    {
        return self::$literalMonths;
    }

    public static function getFullRuDate($timestamp)
    {
        $arDate = explode(' ', date('j m Y', $timestamp));

        return $arDate[0] . ' ' . self::$literalMonthsDeclination[$arDate[1]] . ' ' . $arDate[2];
    }
}