<?

namespace Maxima\Options;

use Bitrix\Main\Config\Option;

/**
 * Class Options
 * @package Maxima\Options
 */
class Options
{
    private static $moduleID = "maxima.options";

    private static $arInfo = [
		'GENERAL_PHONE' => [
			'NAME' => "mxm_info_general_phone",
		],
		'COPYRIGHT' => [
			'NAME' => "mxm_info_copyright",
		],
		'COPYRIGHT_LOGO_LINK' => [
			'NAME' => "mxm_info_copyright_logo_link",
		],
		'LICENSE' => [
			'NAME' => "mxm_info_license",
		],
		'SITE_DEVELOPER' => [
			'NAME' => "mxm_info_developer",
		],
		'ADDRESS' => [
			'NAME' => "mxm_info_general_address",
		],
		'EMAIL' => [
			'NAME' => "mxm_info_general_email",
		],
	];
	private static $arSocial = [
		[
			'NAME' => "mxm_social_yt",
			'CODE' => 'yt',
			'SVG' => '<svg class="i-svg" viewBox="0 0 20 14">
                            <path d="M19,2.5c0.1,0.6,0.3,1.6,0.3,2.8l0,1.7l0,1.7c-0.1,1.3-0.2,2.2-0.3,2.8c-0.1,0.4-0.3,0.7-0.6,1
                                    c-0.3,0.3-0.7,0.5-1.1,0.6c-0.6,0.2-2,0.3-4.2,0.3l-3.2,0l-3.2,0c-2.2-0.1-3.6-0.2-4.2-0.3c-0.4-0.1-0.8-0.3-1.1-0.6
                                    c-0.3-0.3-0.5-0.6-0.6-1C0.7,10.9,0.6,10,0.5,8.7l0-1.7c0-0.5,0-1,0-1.7c0.1-1.2,0.2-2.2,0.3-2.8c0.1-0.4,0.3-0.7,0.6-1
                                    C1.8,1.2,2.2,1,2.6,0.9c0.6-0.1,2-0.3,4.2-0.3l3.2,0l3.2,0c2.2,0.1,3.6,0.2,4.2,0.3c0.4,0.1,0.8,0.3,1.1,0.6
                                    C18.7,1.8,18.9,2.1,19,2.5z M8,9.8L13,7L8,4.3V9.8z"></path>
                        </svg>'
		],
		[
			'NAME' => "mxm_social_fb",
			'CODE' => 'fb',
			'SVG' => '<svg class="i-svg" viewBox="0 0 20 14">
                            <path d="M17,0.8c0-0.2-0.1-0.4-0.3-0.6C16.6,0.1,16.4,0,16.2,0H3.8C3.5,0,3.3,0.1,3.2,0.2C3.1,0.4,3,0.6,3,0.8v12.4
	c0,0.2,0.1,0.4,0.2,0.6C3.3,13.9,3.5,14,3.8,14h6.7V8.6H8.7V6.5h1.8V4.9c0-0.9,0.3-1.6,0.8-2.1c0.5-0.5,1.2-0.7,2-0.7
	c0.6,0,1.2,0,1.6,0.1v1.9h-1.1c-0.4,0-0.7,0.1-0.8,0.3c-0.1,0.2-0.2,0.4-0.2,0.8v1.3h2.1l-0.3,2.1h-1.8V14h3.6
	c0.2,0,0.4-0.1,0.6-0.2c0.1-0.2,0.2-0.3,0.2-0.6V0.8z"></path>
                        </svg>'
		],
		[
			'NAME' => "mxm_social_vk",
			'CODE' => 'vk',
			'SVG' => '<svg class="i-svg" viewBox="0 0 20 14">
                            <path  d="M18.6,2.3c-0.1,0.3-0.3,0.9-0.8,1.7c-0.3,0.6-0.7,1.2-1.2,2C16.2,6.6,16,6.9,16,6.9c-0.1,0.2-0.2,0.4-0.2,0.5
                                        c0,0.1,0.1,0.3,0.2,0.4l0.3,0.3c1.6,1.7,2.5,2.9,2.7,3.5c0.1,0.3,0,0.5-0.1,0.7c-0.1,0.1-0.3,0.2-0.6,0.2h-2c-0.3,0-0.5-0.1-0.7-0.2
                                        c-0.1-0.1-0.4-0.3-0.7-0.7c-0.3-0.4-0.6-0.8-0.9-1c-0.9-0.9-1.6-1.3-2-1.3c-0.2,0-0.3,0-0.4,0.1c-0.1,0.1-0.1,0.2-0.1,0.4
                                        c0,0.2,0,0.5,0,1.1v0.9c0,0.3-0.1,0.5-0.2,0.6c-0.2,0.1-0.6,0.2-1.2,0.2c-1.1,0-2.1-0.3-3.2-1c-1.1-0.6-2-1.5-2.8-2.8
                                        c-0.8-1-1.4-2.1-1.9-3.2C1.8,4.8,1.5,4,1.2,3.3C1.1,2.7,1,2.3,1,2.1c0-0.4,0.2-0.6,0.7-0.6h2c0.2,0,0.4,0.1,0.5,0.2
                                        C4.3,1.8,4.4,2,4.5,2.3C4.8,3.2,5.2,4.1,5.7,5c0.4,0.8,0.8,1.5,1.2,2c0.4,0.5,0.7,0.8,0.9,0.8c0.1,0,0.2,0,0.3-0.1
                                        c0.1-0.1,0.1-0.3,0.1-0.6v-3c0-0.3-0.1-0.7-0.2-1C7.9,2.8,7.7,2.7,7.6,2.5C7.4,2.3,7.4,2.1,7.4,2s0-0.2,0.1-0.3
                                        c0.1-0.1,0.2-0.1,0.4-0.1H11c0.2,0,0.3,0.1,0.4,0.2c0.1,0.1,0.1,0.3,0.1,0.6v4c0,0.2,0,0.4,0.1,0.4c0.1,0.1,0.1,0.1,0.2,0.1
                                        c0.1,0,0.2,0,0.4-0.1c0.1-0.1,0.3-0.2,0.5-0.5c0.4-0.5,0.8-1.1,1.3-1.8c0.3-0.5,0.6-1,0.8-1.6l0.3-0.7c0.1-0.4,0.4-0.6,0.8-0.6h2
                                        C18.5,1.5,18.7,1.8,18.6,2.3z"></path>
                        </svg>'
		],
		[
			'NAME' => "mxm_social_ig",
			'CODE' => 'ig',
			'SVG' => '<svg class="i-svg" viewBox="0 0 20 14">
                            <path d="M10,3.4c0.6,0,1.2,0.2,1.8,0.5s1,0.8,1.3,1.3c0.3,0.6,0.5,1.2,0.5,1.8c0,0.7-0.2,1.3-0.5,1.8
                                    c-0.3,0.6-0.8,1-1.3,1.3s-1.2,0.5-1.8,0.5c-0.7,0-1.3-0.2-1.8-0.5s-1-0.8-1.3-1.3S6.4,7.7,6.4,7c0-0.6,0.2-1.2,0.5-1.8
                                    s0.8-1,1.3-1.3S9.3,3.4,10,3.4z M10,9.3c0.6,0,1.2-0.2,1.7-0.7c0.4-0.4,0.7-1,0.7-1.7c0-0.6-0.3-1.2-0.7-1.7c-0.5-0.4-1-0.7-1.7-0.7
                                    c-0.7,0-1.2,0.3-1.7,0.7C7.9,5.8,7.7,6.4,7.7,7c0,0.7,0.2,1.2,0.7,1.7C8.8,9.1,9.3,9.3,10,9.3z M14.6,3.3c0-0.2-0.1-0.4-0.3-0.6
                                    c-0.2-0.2-0.4-0.3-0.6-0.3c-0.3,0-0.4,0.1-0.6,0.3C13,2.8,12.9,3,12.9,3.3c0,0.3,0.1,0.4,0.2,0.6c0.2,0.2,0.3,0.3,0.6,0.3
                                    c0.2,0,0.4-0.1,0.6-0.3C14.5,3.7,14.6,3.5,14.6,3.3z M17,4.1c0,0.6,0,1.6,0,2.9c0,1.4,0,2.3-0.1,2.9c0,0.6-0.1,1.1-0.3,1.5
                                    c-0.2,0.5-0.5,1-0.9,1.4s-0.8,0.7-1.3,0.8c-0.4,0.2-1,0.3-1.6,0.3c-0.6,0-1.6,0-2.9,0c-1.4,0-2.3,0-2.9,0c-0.6,0-1.1-0.1-1.5-0.3
                                    c-0.5-0.2-1-0.4-1.4-0.8S3.5,12,3.3,11.5C3.2,11,3.1,10.5,3,9.9C3,9.3,3,8.4,3,7c0-1.3,0-2.3,0-2.9S3.2,3,3.3,2.5
                                    c0.2-0.5,0.5-1,0.8-1.3S5,0.5,5.5,0.3C6,0.2,6.5,0.1,7.1,0.1S8.6,0,10,0c1.3,0,2.3,0,2.9,0.1s1.1,0.1,1.6,0.3c0.5,0.2,1,0.5,1.3,0.9
                                    s0.7,0.8,0.9,1.3C16.8,3,16.9,3.5,17,4.1z M15.5,11.1c0.1-0.3,0.2-0.9,0.3-1.7c0-0.4,0-1.1,0-1.9v-1c0-0.8,0-1.5,0-1.9
                                    c-0.1-0.8-0.1-1.3-0.3-1.7c-0.3-0.6-0.7-1.1-1.3-1.3c-0.3-0.1-0.9-0.2-1.7-0.3c-0.5,0-1.1,0-1.9,0h-1c-0.8,0-1.5,0-1.9,0
                                    C6.8,1.3,6.2,1.4,5.9,1.5C5.2,1.8,4.8,2.3,4.5,2.9C4.4,3.2,4.3,3.8,4.3,4.6c0,0.5,0,1.1,0,1.9v1c0,0.8,0,1.5,0,1.9
                                    c0,0.8,0.1,1.3,0.3,1.7c0.3,0.7,0.7,1.1,1.3,1.3c0.3,0.1,0.9,0.2,1.7,0.3c0.4,0,1.1,0,1.9,0h1c0.8,0,1.5,0,1.9,0
                                    c0.8,0,1.3-0.1,1.7-0.3C14.8,12.2,15.2,11.8,15.5,11.1z"></path>
                        </svg>'
		],
	];
	private static $arSocialSort = [
		[
			'NAME' => "mxm_social_sort_yt",
			'CODE' => 'yt'
		],
		[
			'NAME' => "mxm_social_sort_fb",
			'CODE' => 'fb',
		],
		[
			'NAME' => "mxm_social_sort_vk",
			'CODE' => 'vk'
		],
		[
			'NAME' => "mxm_social_sort_ig",
			'CODE' => 'ig'
		],
	];

    private static function getStructure(array &$arStructure)
    {
        foreach ($arStructure as $key => &$arItem) {
            if(is_array($arItem['NAME'])){
                foreach ($arItem['NAME'] as $type => $name){
                    $arItem['VALUE'][$type] = Option::get(static::$moduleID, $name);
                }
            }else{
                $arItem['VALUE'] = Option::get(static::$moduleID, $arItem['NAME']);
            }

        }
        unset($arItem);
        return $arStructure;
    }

    /**
     * @param $sName string Название опции
     * @param $sDefaultValue mixed Значение по умолчанию
     * @return string
     */
    public static function getOption($sName, $sDefaultValue = '')
    {
        return Option::get(static::$moduleID, $sName, $sDefaultValue);
    }

    /* Ссылки соц сетей
     * @return array
     */
    public static function getSocial()
    {
        $arResult = [];
        $arSocialSort =  static::getStructure(self::$arSocialSort);
        $arSocialInfo = static::getStructure(self::$arSocial);
        foreach ($arSocialInfo as $arInfo){
            foreach ($arSocialSort as $arSort){
                if($arInfo['CODE'] === $arSort['CODE']){
                    $arResult[$arSort['VALUE']] = $arInfo;
                }

            }
        }
        ksort($arResult);
        return $arResult;
    }
	/*
   * Общая информация
   * @return array
   */
	public static function getInfo()
	{
		$arInfo = self::getStructure(self::$arInfo);
		$arInfo['GENERAL_PHONE']['FORMATTED'] = strtr($arInfo['GENERAL_PHONE']['VALUE'], array(' ' => '', "-" => '', '(' => '',')' => ''));
		return $arInfo;
	}

}