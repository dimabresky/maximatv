<?php
use Maxima\Helpers\FavoritesHelper;

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$userId = (int)$_REQUEST['uid'];
$elementId = $_REQUEST['eid'];
$action = htmlspecialchars($_REQUEST['a']);
$fvHelper = new FavoritesHelper();

switch ($action) {
    case 'add':
        $fvHelper->addFavoriteItem($userId, $elementId);
        break;
    case 'del':
        if (is_array($elementId)) {
            foreach ($elementId as $id) {
                $fvHelper->deleteFavoriteItem($userId, (int)$id);
            }
        } else {
            $fvHelper->deleteFavoriteItem($userId, (int)$elementId);
        }
        break;
    case 'clear':
        $fvHelper->clearUserFavoriteItems($userId);
        break;
}
?>

