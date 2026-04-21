<?php
use Maxima\Helpers\HighloadHelper;
use Bitrix\Main\Type\DateTime;

$_SERVER["DOCUMENT_ROOT"] = dirname(dirname(dirname(__DIR__)));
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_AGENT_STATISTIC", true);
define("NO_KEEP_STATISTIC", true);
define('DS', '/');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);

$days1 = (int)COption::GetOptionString("maxima.options", "mxm_subscription_check_1");
$days2 = (int)COption::GetOptionString("maxima.options", "mxm_subscription_check_2");

$now = new DateTime();
$today = new DateTime($now->format('d.m.Y') . ' 00:00:00');
$period1Start = new DateTime($today->format('d.m.Y'));
$period1Start->add($days1 . ' days');
$period1End = new DateTime($today->format('d.m.Y'));
$period1End->add(($days1 + 1) . ' days');
$period2Start = new DateTime($today->format('d.m.Y'));
$period2Start->add($days2 . ' days');
$period2End = new DateTime($today->format('d.m.Y'));
$period2End->add(($days2 + 1 ) . ' days');

$hlDataClass = HighloadHelper::getUserTariffHlDataClass();
$dbRes = $hlDataClass::getList([
    'select' => ['*'],
    'filter' => ['UF_STATUS' => 'active', '>UF_SUBSCRIPTION_TO' => $now],
    'order' => ['UF_SUBSCRIPTION_TO' => 'ASC']
]);

while ($item = $dbRes->fetch()){
    if ($item['UF_SUBSCRIPTION_TO']->getTimestamp() >= $period1Start->getTimestamp() && $item['UF_SUBSCRIPTION_TO']->getTimestamp() < $period1End->getTimestamp()) {
        $arEventFields = [
            'EMAIL_TO' => CUser::GetByID($item['UF_USER_ID'])->Fetch()['EMAIL']
            //'EMAIL_TO' => 'ivleonov@gmail.com'
        ];
        CEvent::Send("SUBSCRIBE_END_1", 's1', $arEventFields, "N", 33);
    } else if ($item['UF_SUBSCRIPTION_TO'] >= $period2Start && $item['UF_SUBSCRIPTION_TO'] < $period2End) {
        $arEventFields = [
            'EMAIL_TO' => CUser::GetByID($item['UF_USER_ID'])->Fetch()['EMAIL']
        ];
        CEvent::Send("SUBSCRIBE_END_2", 's1', $arEventFields, "N", 34);
    }
}