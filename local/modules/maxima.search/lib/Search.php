<?php
namespace Maxima\Search;

use \Bitrix\Main\Application;
use \Maxima\Search\Tables\SearchContentTable;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity\ExpressionField;



class Search
{

    const MAX_RESULT_COUNT = 500;
    const MIN_WORD_LENGTH = 2;
    public $error = false;
    public $nav = false;
    protected $wordConditions = [];
    protected $filter = false;
    protected $searchPhrase = [];
    protected $connection;
    protected $sqlHelper;


    protected $searchResult = [];

    public function __construct()
    {
       $this->connection = Application::getConnection();
        $this->sqlHelper = $this->connection->getSqlHelper();
    }


    public function getNav($arFilter, $allResult = false, $pageSize = 10, $pageParam = 'search_page')
    {
        $searchWord = $arFilter['SEARCH_WORD'] ? $arFilter['SEARCH_WORD'] : '';
        $this->getWordCondition($searchWord);
        $this->getPhraseCondition($searchWord);
        $this->filter = ['%=SEARCH_CONTENT' =>  $this->wordConditions];

        if(!empty($arFilter['ACTIVE_FROM'])){
            $this->filter['>=DATE'] = $arFilter['ACTIVE_FROM'];
        }
        if(!empty($arFilter['ACTIVE_TO'])){
            $this->filter['<=DATE'] = $arFilter['ACTIVE_TO'];
        }
        if(!empty($arFilter['DISCIPLINE'])){
            $this->filter['%=DISCIPLINE'] = '%' . $arFilter['DISCIPLINE'] . '%';
        }
        $arSelect = ['ID'];

        $rs = SearchContentTable::getList([
            'select' => $arSelect,
            'filter' => $this->filter,
        ]);
        $searchResult =  $rs->fetchAll();

        $navResult = new \Bitrix\Main\UI\PageNavigation($pageParam);
        $navResult->setRecordCount(count($searchResult))
            ->setPageSize($pageSize)
            ->allowAllRecords($allResult)
            ->initFromUri();
        $this->nav = $navResult;
        return $navResult;

    }

    public function search($arSort = false)
    {
        $arSelect = ['ID', 'ITEM_ID', 'CONTENT_TYPE', 'TITLE', 'URL', 'SEARCH_CONTENT', 'DATE', 'DISCIPLINE', 'FILE_NAME'];
        $arSelect = array_merge($arSelect, $this->getRelevantSelect());
        if(!$arSort){
            $arSort = ['TITLE' => 'desc'];
        }
        if(!empty($this->searchPhrase)){
           $arSort = array_merge(['relevant' => 'desc'], $arSort);
        }
        foreach ($arSort as $field => $sort){
            $arSort[$field] = $field . ' ' . $sort;
        }

        $strSort = implode(', ', $arSort);



        $arFilter =  [];

        if(!empty($this->filter['%=SEARCH_CONTENT'])){
            $arFilterWord = [];
            foreach($this->filter['%=SEARCH_CONTENT'] as $word){
                $arFilterWord[] = '`SEARCH_CONTENT` LIKE "'. $this->sqlHelper->forSql(mb_strtoupper($word)) . '"';
            }
            $arFilter[] = '( ' . implode(' OR ', $arFilterWord) . ')';
        }

        if(!empty($this->filter['>=DATE'])){
            $arFilter[] = '`DATE` >= "' . date('Y-m-d', strtotime($this->filter['>=DATE'])) .  '"';
        }

        if(!empty($this->filter['<=DATE'])){
            $arFilter[] = '`DATE` <= "' . date('Y-m-d', strtotime($this->filter['<=DATE'])) .  '"';
        }
        if(!empty($this->filter['%=DISCIPLINE'])){
            $arFilter[] = '`DISCIPLINE` LIKE "'. $this->sqlHelper->forSql($this->filter['%=DISCIPLINE']) . '"';
        }

        $strFilter = implode(' AND ', $arFilter);


        $sql ='SELECT  ' . implode(', ', $arSelect) .' FROM `maxima_search_content`  WHERE '  .  $strFilter .
                ' ORDER BY ' . $strSort . ' LIMIT '  .$this->nav->getOffset() . ', ' .  $this->nav->getLimit() ;
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/search_sql_stat.log', date('d.m.Y H:i:s')."\n".$sql."\n\n", FILE_APPEND);
       // echo $sql;
        $searchResult = [];
        $rs = $this->connection->query($sql);
        while ($arItem = $rs->fetch()) {
            if(!empty($arItem['DATE'])) {
                $arItem['DATE'] = $arItem['DATE']->format('d.m.Y');
            }
            $searchResult[] = $arItem;
        }

        return $searchResult;

    }

    public static function getDisciplineList()
    {
        $arSelect = [new ExpressionField('DISTINCT_DISCIPLINE', 'DISTINCT DISCIPLINE')];
        $arSort = ['DISCIPLINE' => 'desc'];
        $rs = SearchContentTable::getList([
            'select' => $arSelect,
            'order' => $arSort,
            'filter' => ['!DISCIPLINE' => false]
        ]);
        $disciplineList = [];
        while($arItem = $rs->fetch()){
            $disciplineList = array_merge($disciplineList, explode(' / ', $arItem['DISTINCT_DISCIPLINE']));
        }
        $disciplineList = array_unique($disciplineList);
        return $disciplineList;
    }

    public static function getMinMaxDate()
    {
        $arSelect = [
            new ExpressionField('MIN_DATE', 'MIN(DATE)'),
            new ExpressionField('MAX_DATE', 'MAX(DATE)')
        ];
        $rs = SearchContentTable::getList([
            'select' => $arSelect,
            'filter' => ['!DATE' => false]
        ]);
        $result = $rs->fetch();
        return [
            'MIN_DATE' => $result['MIN_DATE']->format('d.m.Y'),
            'MAX_DATE' => $result['MAX_DATE']->format('d.m.Y')
        ];
    }

    protected function getWordCondition($searchWord)
    {
        if(!is_array($searchWord)){
            $searchWord = explode(' ', $searchWord);
        }

        foreach ($searchWord as $k => $word){
            if(strlen($word) <  self::MIN_WORD_LENGTH){
                unset($searchWord[$k]);
            }
            else {
                $searchWord[$k] = '%' . $word . '%';
            }
        }

        if(empty($searchWord)){
            $this->error = Loc::getMessage("MXM_SEARCH_EMPTY_QUERY");
            return;
        }
        $searchWord = array_unique($searchWord);
        $this->wordConditions = $searchWord;
    }

    protected function getPhraseCondition($searchWord)
    {
        $this->searchPhrase = [
            '%' . $searchWord . '%',
        ];

    }

    protected function  getRelevantSelect()
    {
        $phraseCoef  = 50;
        $wordCoef = round(($phraseCoef/count($this->wordConditions)),2);
        $newExpression = [];
        foreach ($this->searchPhrase as $phrase){
            $newExpression[]= "IF (`SEARCH_CONTENT` LIKE '" . $this->sqlHelper->forSql(mb_strtoupper($phrase)) . "', "  . $phraseCoef . ", 0)";
        }
        foreach ($this->wordConditions as $word){
            $newExpression[] = "IF (`SEARCH_CONTENT` LIKE '" . $this->sqlHelper->forSql(mb_strtoupper($word)) . "', "  . $wordCoef . ", 0)";
        }
        $newExpression = '(' . implode( ' + ' , $newExpression) . ')  as `relevant`';

        return [$newExpression];
    }
}