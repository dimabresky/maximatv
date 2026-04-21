<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;
use Maxima\Options\Options;
use Maxima\Helpers;
use Bitrix\Main\Application;

class BasketListHeader extends \CBitrixComponent
{

    /*
     * @var Maxima\Helpers\BasketHelper $basket
     */
    protected $basket;

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
            $this->basket = new Helpers\BasketHelper();
            $this->processRequest();
            $this->getBasket();
            $templateName = 'main_template';
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

	protected function processRequest()
    {
         $request = Application::getInstance()->getContext()->getRequest();
         $this->arResult['IS_AJAX'] = $request->isAjaxRequest() ? true: false;

         if($request->get('action_type') !== 'basket'){
             return;
         }
         $action = $request->get('action');
         $id = $request->get('id');
         if($action == 'add'){
            $type = $request->get('type');
            $this->basket->addItem($id, $type);
         }
        if($action == 'remove'){
            $this->basket->removeItem($id);
        }
    }

	protected function getBasket()
    {
        $items = $this->basket->getItems();
        $sum = $this->basket->getSum();
        $count  = $this->basket->getCount();
        $this->arResult['BASKET'] = [
            'ITEMS' => $items,
            'SUM' => $sum,
            'COUNT' => $count
        ];
    }



}