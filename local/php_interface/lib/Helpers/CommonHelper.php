<?php

namespace Maxima\Helpers;

use Bitrix\Main\Entity\DataManager;

class CommonHelper
{
    public static $translit = [
        'щ' => 'shh',
        'ш' => 'sh',
        'ч' => 'ch',
        'ци' => 'ci',
        'це' => 'ce',
        'цы' => 'cy',
        'сй' => 'cj',
        'ц' => 'cz',
        'ё' => 'yo',
        'ю' => 'yu',
        'я' => 'ya',
        'ж' => 'zh',
        'ъ' => '``',
        'ь' => '`',
        'э' => 'e`',
        'ы' => 'y`',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'x',
        /*'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'YO',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'J',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',
        'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',
        'Ь' => '\'',  'Ы' => 'Y\'',   'Ъ' => '\'\'',
        'Э' => 'E\'',   'Ю' => 'YU',  'Я' => 'YA',
        */
    ];

    public static $translitNames = [
        'щ' => 'shch',
        'ш' => 'sh',
        'ч' => 'ch',
        'х' => 'kh',
        'ц' => 'ts',
        'ж' => 'zh',
        //'ё' => 'yo',
        'ю' => 'iu',
        'я' => 'ia',
        'ъ' => 'ie',
        //'ь' => '`',
        //'э' => 'e`',
        'ы' => 'y',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'з' => 'z',
        'и' => 'i',
        //'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
    ];

    public static function translitFileNameBack($str)
    {
        // Для удобства чтения добавим еще пару преобразований
        $modifiedArray = array_merge(array_flip(self::$translit), [
            '-' => ' ',
            '_' => ' - ',
        ]);
        return strtr($str, $modifiedArray);
    }

    public static function getAjaxHandlerByFilterName($filterName)
    {
        $handler = '';

        switch ($filterName) {
            case 'arrBroadcastsList':
            case 'arrBroadcastsListMain':
                $handler = 'broadcasts';
                break;
            case 'arrEventsList':
            case 'arrEventsListMain':
                $handler = 'events';
                break;
            case 'arrCompetitionsList':
                $handler = 'competitions';
                break;
            case 'arrCompetitionsListMain':
                $handler = 'competitions_main';
                break;
            case 'arrCompetitionsListPhoto':
                $handler = 'competitions_photo';
                break;
            case 'arrProgramsList':
                $handler = 'programs';
                break;
            case 'arrMaximarevList':
                $handler = 'maximarev';
                break;
        }

        return '/local/ajax/' . $handler . '.php';
    }

    public static function getIblock($type, $code)
    {
        \CModule::IncludeModule('iblock');
        return \CIBlock::GetList([], ['TYPE' => $type, 'CODE' => $code])->Fetch();
    }

    public static function getBroadcastById($id)
    {
        \CModule::IncludeModule('iblock');
        $element = \CIBlockElement::GetByID($id)->GetNextElement();
        if ($element === false) {
            return null;
        }
        $result = $element->GetFields();
        $result['PROPERTIES'] = $element->GetProperties();

        return $result;
    }

    public static function getBroadcastByCode($code)
    {
        $iblock = self::getIblock('main_content', 'broadcasts');
        if ($iblock === false) {
            return null;
        }

        $element = \CIBlockElement::GetList([], ['IBLOCK_ID' => $iblock['ID'], 'CODE' => $code])->GetNextElement();
        if ($element === false) {
            return null;
        }
        $result = $element->GetFields();
        $result['PROPERTIES'] = $element->GetProperties();

        return $result;
    }

    public static function getBroadcastAccess($userId, $broadacstId)
    {
        $hlDataClass = HighloadHelper::getBroadcastHlDataClass();
        $dbRes = $hlDataClass::getList([
                'select' => ['ID'],
                'filter' => ['UF_USER_ID' => $userId, 'UF_BROADCAST_ID' => $broadacstId]]
        );

        return $dbRes->getSelectedRowsCount() > 0;
    }

    public static function aDump($var, $all = false, $die = false) {
        global $USER;

        if( ($USER->GetID() == 2) || ($all == true)){?><pre><?var_dump($var)?></pre><br><?}

        if($die) die;
    }
}