<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;
use Maxima\Options\Options;

class UserProfileHeader extends \CBitrixComponent
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
        global $USER;

        $templateName = 'guest';
        if ($USER->IsAuthorized()) {
            $templateName = 'user';
            $this->arResult['USER'] = CUser::GetByID($USER->GetID())->Fetch();
            if ($this->arResult['USER']['PERSONAL_PHOTO'] != '') {
                $arPhoto = CFile::ResizeImageGet(
                    $this->arResult['USER']['PERSONAL_PHOTO'],
                    ['width' => 50, 'height' => 50],
                    BX_RESIZE_IMAGE_EXACT
                );
                $this->arResult['USER']['PHOTO'] = $arPhoto['src'];
            } else {
                $this->arResult['USER']['PHOTO'] = SITE_TEMPLATE_PATH . '/images/profile-nofoto.jpg';
            }
        }

        $this->includeComponentTemplate($templateName);
	}



}