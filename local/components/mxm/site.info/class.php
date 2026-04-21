<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;
use Maxima\Options\Options;

class SiteInfo extends \CBitrixComponent
{

	/**
	 * подключает языковые файлы
	 */
	public function onIncludeComponentLang()
	{
		$this->includeComponentLang(basename(__FILE__));
		Loc::loadMessages(__FILE__);
	}

	/**
	 * подготавливает входные параметры
	 * @param array $arParams
	 * @return array
	 */
	public function onPrepareComponentParams($params)
	{
		return $params;
	}
	/**
	 * выполняет логику работы компонента
	 */
	public function executeComponent()
	{
		\Bitrix\Main\Loader::includeModule('maxima.options');

		$this->arResult = Options::getInfo();
		$this->IncludeComponentTemplate();
	}



}