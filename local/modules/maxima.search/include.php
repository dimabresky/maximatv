<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('maxima.search', array(
        '\Maxima\Search\Reindex' => 'lib/Reindex.php',
        '\Maxima\Search\Tables\SearchContentTable' => 'lib/tables/SearchContentTable.php',
        '\Maxima\Search\Search' => 'lib/Search.php'
    )
);