<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<?$APPLICATION->IncludeComponent(
    "mxm:photo.list",
    "",
    array(
        'SHOW_MORE' => 'Y',
        'TYPE' => $_GET['photo_type'],
        'ID' => $_GET['photo_id']
    )

);?>
