<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class maxima_search extends CModule
{
	public function __construct()
	{
		$arModuleVersion = array();

		include __DIR__ . '/version.php';
		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->MODULE_ID = 'maxima.search';
		$this->MODULE_NAME = Loc::getMessage('MXM_SEARCH_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('MXM_SEARCH_MODULE_DESCRIPTION');
		$this->MODULE_GROUP_RIGHTS = 'N';
	}

	public function doInstall()
	{
        $this->InstallDB();
		ModuleManager::registerModule($this->MODULE_ID);
	}

	public function doUninstall()
	{
        $this->UnInstallDB();
		ModuleManager::unRegisterModule($this->MODULE_ID);
	}
}