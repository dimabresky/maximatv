<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle('Главная');
$APPLICATION->SetPageProperty('title', 'Maxima TV - ваш проводник в мир конного спорта');

global $USER;

CModule::IncludeModule('iblock');
$eventsCount = CIBlockElement::GetList([], ['IBLOCK_ID' => 5, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], []);
$competitionsCount = CIBlockElement::GetList([], ['IBLOCK_ID' => 6, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'], []);
?>
<?php
    global $arrBroadcastsBannerList;
    $arrBroadcastsBannerList = [
        '!PROPERTY_SHOW_ON_MAIN' => false,
    ];
?>
<?$APPLICATION->IncludeComponent("bitrix:news.list", "broadcasts_banner", Array(
    "IBLOCK_TYPE" => 'main_content',
    "IBLOCK_ID" => 4,
    "NEWS_COUNT" => 99,
    "SORT_BY1" => 'PROPERTY_SORT_ON_MAIN',
    "SORT_ORDER1" => 'ASC',
    "SORT_BY2" => 'PROPERTY_LIVE_FROM',
    "SORT_ORDER2" => 'ASC',
    "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
    "PROPERTY_CODE" => ['DISCIPLINE', 'STATUS', 'LIVE_FROM', 'LIVE_TO', 'SHOW_ON_MAIN', 'SORT_ON_MAIN', ''],
    "DETAIL_URL" => '/broadcasts/#ELEMENT_CODE#/',
    "SECTION_URL" => '/broadcasts/',
    "IBLOCK_URL" => '/broadcasts/',
    "DISPLAY_PANEL" => NULL,
    "SET_TITLE" => 'N',
    "SET_LAST_MODIFIED" => 'N',
    "MESSAGE_404" => '',
    "SET_STATUS_404" => 'N',
    "SHOW_404" => 'N',
    "FILE_404" => '',
    "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
    "CACHE_TYPE" => 'A',
    "CACHE_TIME" => '300',
    "CACHE_FILTER" => 'N',
    "CACHE_GROUPS" => 'N',
    "DISPLAY_TOP_PAGER" => 'N',
    "DISPLAY_BOTTOM_PAGER" => 'N',
    "PAGER_TITLE" => 'Новости',
    "PAGER_TEMPLATE" => '.default',
    "PAGER_SHOW_ALWAYS" => 'N',
    "PAGER_DESC_NUMBERING" => 'N',
    "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
    "PAGER_SHOW_ALL" => 'N',
    "PAGER_BASE_LINK_ENABLE" => 'N',
    "PAGER_BASE_LINK" => NULL,
    "PAGER_PARAMS_NAME" => NULL,
    "DISPLAY_DATE" => 'N',
    "DISPLAY_NAME" => "Y",
    "DISPLAY_PICTURE" => 'N',
    "DISPLAY_PREVIEW_TEXT" => 'N',
    "PREVIEW_TRUNCATE_LEN" => '',
    "ACTIVE_DATE_FORMAT" => 'd.m.Y',
    "USE_PERMISSIONS" => 'N',
    "GROUP_PERMISSIONS" => NULL,
    "FILTER_NAME" => 'arrBroadcastsBannerList',
    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
    "CHECK_DATES" => 'Y',
));?>

<section class="i-section">
    <div class="g-wrap">
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.filter",
            "items_list",
            Array(
                "IBLOCK_TYPE" => 'main_content',
                "IBLOCK_ID" => 4,
                "FILTER_NAME" => 'arrBroadcastsListMain',
                "FIELD_CODE" => ['', ''],
                "PROPERTY_CODE" => ['DISCIPLINE', 'STATUS', ''],
                "CACHE_TYPE" => 'A',
                "CACHE_TIME" => '36000000',
                "CACHE_GROUPS" => 'N',
                "PAGER_PARAMS_NAME" => NULL,
                "FILTER_TITLE" => 'Трансляции',
            )
        );
        ?>
        <div id="news_list-container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "broadcasts",
                Array(
                    "IBLOCK_TYPE" => 'main_content',
                    "IBLOCK_ID" => 4,
                    "NEWS_COUNT" => 3,
                    "SORT_BY1" => 'PROPERTY_LIVE_FROM',
                    "SORT_ORDER1" => 'ASC',
                    "SORT_BY2" => 'ID',
                    "SORT_ORDER2" => 'DESC',
                    "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
                    "PROPERTY_CODE" => ['DISCIPLINE', 'STATUS', 'LIVE_FROM', 'LIVE_TO', ''],
                    "DETAIL_URL" => '/broadcasts/#ELEMENT_CODE#/',
                    "SECTION_URL" => '/broadcasts/',
                    "IBLOCK_URL" => '/broadcasts/',
                    "DISPLAY_PANEL" => NULL,
                    "SET_TITLE" => 'N',
                    "SET_LAST_MODIFIED" => 'N',
                    "MESSAGE_404" => '',
                    "SET_STATUS_404" => 'N',
                    "SHOW_404" => 'N',
                    "FILE_404" => '',
                    "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
                    "CACHE_TYPE" => 'A',
                    "CACHE_TIME" => '300',
                    "CACHE_FILTER" => 'N',
                    "CACHE_GROUPS" => 'N',
                    "DISPLAY_TOP_PAGER" => 'N',
                    "DISPLAY_BOTTOM_PAGER" => 'N',
                    "PAGER_TITLE" => 'Новости',
                    "PAGER_TEMPLATE" => '.default',
                    "PAGER_SHOW_ALWAYS" => 'N',
                    "PAGER_DESC_NUMBERING" => 'N',
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
                    "PAGER_SHOW_ALL" => 'N',
                    "PAGER_BASE_LINK_ENABLE" => 'N',
                    "PAGER_BASE_LINK" => NULL,
                    "PAGER_PARAMS_NAME" => NULL,
                    "DISPLAY_DATE" => 'N',
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => 'N',
                    "DISPLAY_PREVIEW_TEXT" => 'N',
                    "PREVIEW_TRUNCATE_LEN" => '',
                    "ACTIVE_DATE_FORMAT" => 'd.m.Y',
                    "USE_PERMISSIONS" => 'N',
                    "GROUP_PERMISSIONS" => NULL,
                    "FILTER_NAME" => 'arrBroadcastsMainList',
                    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                    "CHECK_DATES" => 'Y',
                    "SHOW_ALL_BTN" => 'Y',
                )
            );?>
        </div>
    </div>
</section>

<!-- Подписка -->
<a name="subscribe"></a>
<section class="i-section i-section_subscription" id="subscription_main_page_block">
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "tariffs",
        Array(
            "IBLOCK_TYPE" => 'pay_content',
            "IBLOCK_ID" => 8,
            "NEWS_COUNT" => 3,
            "SORT_BY1" => 'SORT',
            "SORT_ORDER1" => 'ASC',
            "SORT_BY2" => 'ID',
            "SORT_ORDER2" => 'ASC',
            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', ''],
            "PROPERTY_CODE" => ['BUY_BUTTON_TEXT', 'CSS_CLASS', ''],
            "DETAIL_URL" => '/lk/buy-tariff/#ELEMENT_CODE#/',
            "SECTION_URL" => '',
            "IBLOCK_URL" => '',
            "DISPLAY_PANEL" => NULL,
            "SET_TITLE" => 'N',
            "SET_LAST_MODIFIED" => 'N',
            "MESSAGE_404" => '',
            "SET_STATUS_404" => 'N',
            "SHOW_404" => 'N',
            "FILE_404" => '',
            "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
            "CACHE_TYPE" => 'A',
            "CACHE_TIME" => '36000000',
            "CACHE_FILTER" => 'N',
            "CACHE_GROUPS" => 'N',
            "DISPLAY_TOP_PAGER" => 'N',
            "DISPLAY_BOTTOM_PAGER" => 'N',
            "PAGER_TITLE" => 'Новости',
            "PAGER_TEMPLATE" => '.default',
            "PAGER_SHOW_ALWAYS" => 'N',
            "PAGER_DESC_NUMBERING" => 'N',
            "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
            "PAGER_SHOW_ALL" => 'N',
            "PAGER_BASE_LINK_ENABLE" => 'N',
            "PAGER_BASE_LINK" => NULL,
            "PAGER_PARAMS_NAME" => NULL,
            "DISPLAY_DATE" => 'N',
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => 'N',
            "DISPLAY_PREVIEW_TEXT" => 'N',
            "PREVIEW_TRUNCATE_LEN" => '',
            "ACTIVE_DATE_FORMAT" => 'd.m.Y',
            "USE_PERMISSIONS" => 'N',
            "GROUP_PERMISSIONS" => NULL,
            "FILTER_NAME" => '',
            "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
            "CHECK_DATES" => 'Y',
            "IS_AUTH" => $USER->IsAuthorized(),
        )
    );?>
</section>

<?php if ($competitionsCount > 0) { ?>
<!-- Соревнования -->
<section class="i-section i-section_slider i-section_bg-grey js-tabs">
    <div class="g-wrap">
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.filter",
            "competitions_list_main",
            Array(
                "IBLOCK_TYPE" => 'main_content',
                "IBLOCK_ID" => 6,
                "FILTER_NAME" => 'arrCompetitionsListMain',
                "FIELD_CODE" => ['', ''],
                "PROPERTY_CODE" => ['DISCIPLINE', ''],
                "CACHE_TYPE" => 'A',
                "CACHE_TIME" => '36000000',
                "CACHE_GROUPS" => 'N',
                "PAGER_PARAMS_NAME" => NULL,
                "FILTER_TITLE" => 'Соревнования',
            )
        );
        ?>
        <div class="i-section__content">
            <div id="competitions_list-container">
                <div class="tabs__content js-tabs-content is-active" data-group="video">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "competitions_main_list_video",
                        array(
                            "IBLOCK_TYPE" => "main_content",
                            "IBLOCK_ID" => "6",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_GROUPS" => "N",
                            "COUNT_ELEMENTS" => "Y",
                            "TOP_DEPTH" => "2",
                            "SECTION_URL" => "/competitions/#SECTION_CODE_PATH#/",
                            "VIEW_MODE" => "LIST",
                            "SHOW_PARENT_NAME" => "Y",
                            "HIDE_SECTION_NAME" => "N",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "SECTION_USER_FIELDS" => array("UF_DATE", "UF_DISCIPLINE", "UF_PLACE", "UF_FOTO", ""),
                        ),
                        false
                    );?>
                </div>
                <div class="tabs__content js-tabs-content" data-group="photo">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "competitions_main_list_foto",
                        array(
                            "IBLOCK_TYPE" => "main_content",
                            "IBLOCK_ID" => "6",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_GROUPS" => "N",
                            "COUNT_ELEMENTS" => "Y",
                            "TOP_DEPTH" => "2",
                            "SECTION_URL" => "/competitions/#SECTION_CODE_PATH#/",
                            "VIEW_MODE" => "LIST",
                            "SHOW_PARENT_NAME" => "Y",
                            "HIDE_SECTION_NAME" => "N",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "SECTION_USER_FIELDS" => array("UF_DATE", "UF_DISCIPLINE", "UF_PLACE", "UF_FOTO", ""),
                        ),
                        false
                    );?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php if ($eventsCount > 0) { ?>
<!-- События -->
<section class="i-section js-tabs">
    <div class="g-wrap">
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.filter",
            "events_list_main",
            Array(
                "IBLOCK_TYPE" => 'main_content',
                "IBLOCK_ID" => 5,
                "FILTER_NAME" => 'arrEventsListMain',
                "FIELD_CODE" => ['', ''],
                "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', ''],
                "CACHE_TYPE" => 'A',
                "CACHE_TIME" => '36000000',
                "CACHE_GROUPS" => 'N',
                "PAGER_PARAMS_NAME" => NULL,
                "FILTER_TITLE" => 'События'
            )
        );
        ?>
        <div class="i-section__content">
            <div id="events_list-container">
                <div class="tabs__content js-tabs-content is-active" data-group="video">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "events_main_video",
                        Array(
                            "IBLOCK_TYPE" => 'main_content',
                            "IBLOCK_ID" => 5,
                            "NEWS_COUNT" => 8,
                            "SORT_BY1" => 'ACTIVE_FROM',
                            "SORT_ORDER1" => 'ASC',
                            "SORT_BY2" => 'ID',
                            "SORT_ORDER2" => 'DESC',
                            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
                            "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'WIDGET', 'FOTO', ''],
                            "DETAIL_URL" => '/events/#ELEMENT_CODE#/',
                            "SECTION_URL" => '/events/',
                            "IBLOCK_URL" => '/events/',
                            "DISPLAY_PANEL" => NULL,
                            "SET_TITLE" => 'N',
                            "SET_LAST_MODIFIED" => 'N',
                            "MESSAGE_404" => '',
                            "SET_STATUS_404" => 'N',
                            "SHOW_404" => 'N',
                            "FILE_404" => '',
                            "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
                            "CACHE_TYPE" => 'A',
                            "CACHE_TIME" => '36000000',
                            "CACHE_FILTER" => 'N',
                            "CACHE_GROUPS" => 'N',
                            "DISPLAY_TOP_PAGER" => 'N',
                            "DISPLAY_BOTTOM_PAGER" => 'N',
                            "PAGER_TITLE" => 'Новости',
                            "PAGER_TEMPLATE" => '.default',
                            "PAGER_SHOW_ALWAYS" => 'N',
                            "PAGER_DESC_NUMBERING" => 'N',
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
                            "PAGER_SHOW_ALL" => 'N',
                            "PAGER_BASE_LINK_ENABLE" => 'N',
                            "PAGER_BASE_LINK" => NULL,
                            "PAGER_PARAMS_NAME" => NULL,
                            "DISPLAY_DATE" => 'N',
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => 'N',
                            "DISPLAY_PREVIEW_TEXT" => 'N',
                            "PREVIEW_TRUNCATE_LEN" => '',
                            "ACTIVE_DATE_FORMAT" => 'd.m.Y',
                            "USE_PERMISSIONS" => 'N',
                            "GROUP_PERMISSIONS" => NULL,
                            "FILTER_NAME" => '',
                            "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                            "CHECK_DATES" => 'Y',
                        )
                    );?>
                </div>
                <div class="tabs__content js-tabs-content" data-group="photo">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "events_main_foto",
                        Array(
                            "IBLOCK_TYPE" => 'main_content',
                            "IBLOCK_ID" => 5,
                            "NEWS_COUNT" => 9,
                            "SORT_BY1" => 'ACTIVE_FROM',
                            "SORT_ORDER1" => 'ASC',
                            "SORT_BY2" => 'ID',
                            "SORT_ORDER2" => 'DESC',
                            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
                            "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'WIDGET', 'FOTO', ''],
                            "DETAIL_URL" => '/events/#ELEMENT_CODE#/',
                            "SECTION_URL" => '/events/',
                            "IBLOCK_URL" => '/events/',
                            "DISPLAY_PANEL" => NULL,
                            "SET_TITLE" => 'N',
                            "SET_LAST_MODIFIED" => 'N',
                            "MESSAGE_404" => '',
                            "SET_STATUS_404" => 'N',
                            "SHOW_404" => 'N',
                            "FILE_404" => '',
                            "INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
                            "CACHE_TYPE" => 'A',
                            "CACHE_TIME" => '36000000',
                            "CACHE_FILTER" => 'N',
                            "CACHE_GROUPS" => 'N',
                            "DISPLAY_TOP_PAGER" => 'N',
                            "DISPLAY_BOTTOM_PAGER" => 'N',
                            "PAGER_TITLE" => 'Новости',
                            "PAGER_TEMPLATE" => '.default',
                            "PAGER_SHOW_ALWAYS" => 'N',
                            "PAGER_DESC_NUMBERING" => 'N',
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
                            "PAGER_SHOW_ALL" => 'N',
                            "PAGER_BASE_LINK_ENABLE" => 'N',
                            "PAGER_BASE_LINK" => NULL,
                            "PAGER_PARAMS_NAME" => NULL,
                            "DISPLAY_DATE" => 'N',
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => 'N',
                            "DISPLAY_PREVIEW_TEXT" => 'N',
                            "PREVIEW_TRUNCATE_LEN" => '',
                            "ACTIVE_DATE_FORMAT" => 'd.m.Y',
                            "USE_PERMISSIONS" => 'N',
                            "GROUP_PERMISSIONS" => NULL,
                            "FILTER_NAME" => '',
                            "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                            "CHECK_DATES" => 'Y',
                        )
                    );?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
