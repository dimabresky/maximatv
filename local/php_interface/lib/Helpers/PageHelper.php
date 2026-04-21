<?

namespace Maxima\Helpers;

/**
 * Class PageHelper
 * @package MC\Helpers
 */
class PageHelper
{
    const PAGE_BROADCASTS = "broadcasts";
    const PAGE_EVENTS = "events";
    const PAGE_COMPETITIONS = "competitions";
    const PAGE_PHOTOS = "photo";
    const PAGE_CART = "/lk/cart/";

    const SECTION_LK = "lk";
    const SECTION_AUTH = "auth";
    const SECTION_SEARCH = "search";

    /**
     * @return bool
     */
    public static function isBroadcastDetail()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 1 && reset($arPath) === self::PAGE_BROADCASTS;
    }

    /**
     * @return bool
     */
    public static function isEventDetail()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 1 && reset($arPath) === self::PAGE_EVENTS;
    }

    /**
     * @return bool
     */
    public static function isCompetitionDetail()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 1 && reset($arPath) === self::PAGE_COMPETITIONS;
    }

    /**
     * @return bool
     */
    public static function isPhotoDetail()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 1 && reset($arPath) === self::PAGE_PHOTOS;
    }

    /**
     * @return bool
     */
    public static function isCartPage()
    {
        global $APPLICATION;
        return $APPLICATION->GetCurPage() === self::PAGE_CART;
    }

    /**
     * @return bool
     */
    public static function isLkSection()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 0 && reset($arPath) === self::SECTION_LK;
    }

    /**
     * @return bool
     */
    public static function isAuthSection()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 0 && reset($arPath) === self::SECTION_AUTH;
    }

    /**
     * @return bool
     */
    public static function isSearchSection()
    {
        global $APPLICATION;
        $arPath = array_filter(explode('/', $APPLICATION->GetCurDir()));

        return count($arPath) > 0 && reset($arPath) === self::SECTION_SEARCH;
    }

    /**
     * @return bool
     */
    public static function isPhotoPage(){
        global $APPLICATION;
        $curPage = $APPLICATION->GetCurPage();
        return (self::isCompetitionDetail() || self::isEventDetail()) && (substr($curPage, (strlen($curPage) - 6)) ==  'photo/');
    }
}