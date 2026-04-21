<?php
use Bitrix\Main\Loader;
use Maxima\Search\Reindex;
//use Maxima\Search\Search;


$_SERVER["DOCUMENT_ROOT"] = dirname(dirname(dirname(__DIR__)));
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_AGENT_STATISTIC", true);
define("NO_KEEP_STATISTIC", true);
define('DS', '/');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);

Loader::includeModule('maxima.search');
$reindex = new Reindex();
$reindex->start();
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/cron_stat.log', "end work ". date('d.m.Y H:i:s') . "\n\n", FILE_APPEND);
//$search = new Search();

//echo '<pre>';
// var_dump($search->search('октября', false, false, 1,2));
// echo '</pre>';

