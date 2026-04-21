<?php
namespace Maxima\Search\Tables;


use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Entity\DateField;


use Bitrix\Main\Localization\Loc;

/*
 * Поисковый контент
 */
class SearchContentTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'maxima_search_content';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        Loc::loadMessages(__FILE__);
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage("MXM_SEARCH_CONTENT_ID")
            ),
            'ITEM_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage("MXM_SEARCH_CONTENT_ITEM_ID")
            ),
            'CONTENT_TYPE' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage("MXM_SEARCH_CONTENT_TYPE")
            ),
            'TITLE' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage("MXM_SEARCH_CONTENT_TITLE")
            ),
            'URL' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage("MXM_SEARCH_CONTENT_URL")
            ),
            'SEARCH_CONTENT' => array(
                'data_type' => 'text',
                'required' => true,
                'title' => Loc::getMessage("MXM_SEARCH_CONTENT_TEXT")
            ),
            'DATE' => array(
                'data_type' => 'date',
                'required' => false,
                'title' => Loc::getMessage("MXM_SEARCH_DATE")
            ),
            'DISCIPLINE' => array(
                'data_type' => 'string',
                'required' => false,
                'title' => Loc::getMessage("MXM_SEARCH_DISCIPLINE")
            ),
            'FILE_NAME' => array(
                'data_type' => 'string',
                'required' => false,
            ),
        );
    }

    public static function truncate()
    {
        $tableName = self::getTableName();
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->truncateTable($tableName);
    }

}