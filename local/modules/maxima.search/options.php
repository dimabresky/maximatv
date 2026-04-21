<?
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

$sModuleId = "maxima.search";
CModule::IncludeModule($sModuleId);

Loc::loadMessages(__FILE__);
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");

