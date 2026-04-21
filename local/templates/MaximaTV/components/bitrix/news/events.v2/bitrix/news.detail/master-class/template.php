<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Maxima\Helpers\CommonHelper;
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

use Maxima\Helpers\FavoritesHelper;
use Maxima\Helpers\VideoQualityHelper;

global $APPLICATION, $USER;

$this->setFrameMode(true);

$this->addExternalCss("/local/templates/MaximaTV/css/videojs/video-js.css");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/video.js");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/maxima-video-quality.js");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/videojs.hotkeys.min.js");

$liveFrom = $arResult['PROPERTIES']['LIVE_FROM']['VALUE'];
$liveTo = $arResult['PROPERTIES']['LIVE_TO']['VALUE'];
$isLive = false;
if ($liveFrom !== '' && $liveTo !== '') {
    $liveFrom = strtotime($liveFrom);
    $liveTo = strtotime($liveTo);
    $now = time();
    $isLive = $now >= $liveFrom && $now <= $liveTo;
}

if ($arResult['PREVIEW_PICTURE']) {
    $preview = \CFile::ResizeImageGet(
        $arResult['PREVIEW_PICTURE'],
        ['width' => 1096, 'height' => 553],
        BX_RESIZE_IMAGE_EXACT
    );
} else {
    $preview = [
        'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'event.jpg'
    ];
}

$arFiles = [];
$videoFile = '';
if (!empty($arResult['PROPERTIES']['PATH_TO_VIDEO']['VALUE'])) {
    $currentVideoIndex = (int)Application::getInstance()->getContext()->getRequest()->get('VNUM');
    $videoPath = $arResult['PROPERTIES']['PATH_TO_VIDEO']['VALUE'];
    $arFileNames = scandir($_SERVER['DOCUMENT_ROOT'] . $videoPath, SCANDIR_SORT_ASCENDING);
    if ($arFileNames !== false) {
        foreach ($arFileNames as $fileName) {
            if (in_array($fileName, ['.', '..'])) {
                continue;
            }
            $arFiles[] = $videoPath . $fileName;
        }
        $videoFile = $arFiles[$currentVideoIndex];
    }
}

/*if (!isset($arResult['PICTURE']['SRC'])) {
    $arResult['PICTURE'] = [
        'SRC' => PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg'
    ];
}
if (empty($arResult['PROPERTIES']['DATE_INTERVAL']['VALUE'])) {
    $arResult['PROPERTIES']['DATE_INTERVAL'] = [
        'VALUE' => $arResult['UF_DATE']
    ];
}
*/

$fvHelper = new FavoritesHelper();
$curUser = $USER->GetId();
?>
<style>
    .capitalized {text-transform: capitalize;}
    .event__video .event__video-preview video {transform: none;}
    .event__video-preview .video-js {position: static;}
    .event__video-preview .video-js .vjs-big-play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -25px;
        margin-left: -45px;
    }
</style>
<section class="i-section event__main">
    <div class="g-wrap">
        <div class="event__main-wrap">
            <div class="event__col">
                <div class="event__row event__row_header">
                    <div class="event__header">
                        <p class="event__date"><? if (!empty($arResult['PROPERTIES']['DATE_FROM']['VALUE']) && !empty($arResult['PROPERTIES']['DATE_TO']['VALUE'])): ?>
                                <?= $arResult['PROPERTIES']['DATE_FROM']['VALUE'] . ' - ' . $arResult['PROPERTIES']['DATE_TO']['VALUE'] ?>
                            <? endif ?></p>
                        <h1 class="event__title"><?=$arResult['NAME']?></h1>
                        <p class="event__stage"><?/*1 этап*/?></p>
                        <?php if ($curUser !== null) { ?>
                            <button class="event__favorite js-favorite <?=$fvHelper->isItemExist($curUser, $arResult['ID']) ? 'is-active' : ''?>" data-eid="<?=$arResult['ID']?>" data-uid="<?=$curUser?>">Избранное</button>
                        <?php } ?>
                        <div class="favorite-mess js-favorite-mess">
                            <p class="favorite-mess__text js-delete">Видео удалено из раздела "Избранное"</p>
                            <p class="favorite-mess__text js-add">Видео добавлено в раздел "Избранное"</p>
                        </div>
                    </div>
                </div>
                <?php if (in_array($arResult['PROPERTIES']['ACCESS']['VALUE_ENUM_ID'], $arParams['ACCESS_FILTER'])) { ?>
                    <div class="event__video js-event-slider" data-item-count="3">
                        <?php if ($videoFile !== '') { ?>
                            <?php if ($arResult['ID'] == 9781) { ?>
                            <div class="event__video-preview">
                                <video
                                        src="<?=$videoFile?>"
                                        controls="controls"
                                        autoplay="autoplay"
                                        width="100%"
                                >
                            </div>
                        <?php } else { ?>
                            <?php
                            $arVideoSources = VideoQualityHelper::getSourcesForWebPath((string)$videoFile);
                            $previewSrc = $preview['src'];
                            include $_SERVER['DOCUMENT_ROOT'] . '/local/templates/MaximaTV/include/maxima_video_player.php';
                            ?>
                        <?php } ?>
                        <?php } else { ?>
                        <?php
                        /*$preview = \CFile::ResizeImageGet(
                            $arResult['PREVIEW_PICTURE'],
                            ['width' => 761, 'height' => 553],
                            BX_RESIZE_IMAGE_EXACT
                        );*/
                        ?>
                            <div class="event__video-preview" style="background-image:url(<?=$preview['src']?>)"></div>
                        <?php } ?>
                        <?php if (count($arFiles) > 1) { ?>
                            <div class="event-slider js-slider-wrap">
                                <div class="event-slider__content event-slider__content_slim">
                                    <div class="swiper-container js-slider">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($arFiles as $index => $file) { ?>
                                                <a href="<?=$APPLICATION->GetCurPageParam('VNUM=' . $index, ['VNUM'])?>" class="swiper-slide event-slider__item <?=($index == $currentVideoIndex) ? 'selected-slide' : ''?>">
                                                    <img src="/local/markup/dist/images/competitions/event-slider1.jpg" class="event-slider__preview" alt=""/>
                                                    <p class="event-slider__info capitalized"><?=CommonHelper::translitFileNameBack(pathinfo($file)['filename'])?></p>
                                                    <p class="event-slider__info"></p>
                                                    <span class="event-slider__border"></span>
                                                </a>
                                            <?php } ?>
                                        </div>
                                        <div class="event-slider__nav event-slider__next"></div>
                                        <div class="event-slider__nav event-slider__prev"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="event-slider__btn">
                                <a href="javascript:void(0);" class="event-slider__trig js-bnt-toggle">Выступления участников</a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="event__video js-event-slider" data-item-count="3">
                        <div class="event__video-preview event__video-preview_disabled" style="background-image:url(<?=$arResult['PICTURE']['SRC']?>)">
                            <span class="event__video-message">
                                Просмотр записей мероприятий не включен в ваш тариф. Для просмотра, подключите тариф с доступом к архиву.
                                <br><br>
                                <?php if ($arParams['IS_AUTH']) { ?>
                                    <a href="/lk/subscribe/" class="i-button">Купить подписку</a>
                                <?php } else { ?>
                                    <a href="/#subscribe" class="i-button">Купить подписку</a>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
                <div class="event__row event__row_info">
                    <div class="event__info">
                        <p class="event__info-address"><?=$arResult['PROPERTIES']['PLACE']['VALUE']?></p>
                        <p class="event__info-type">дисциплина:</p>
                        <p><?=$arResult['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                    </div>
                </div>
            </div>
            <div class="event__col event__col_slim">
                <div class="event__row event__row_header event__row_flex">
                    <h2 class="event__col-title">Видео событий</h2>
                </div>
                <?php
                global $arrEventsDetailList;
                $arrEventsDetailList = $GLOBALS[$arParams["FILTER_NAME"]];
                $arrEventsDetailList['IBLOCK_SECTION_ID'] = $arResult['IBLOCK_SECTION_ID'];
                $arrEventsDetailList['!ID'] = $arResult['ID'];
                ?>
                <!-- блок со скроллом -->
                <?$APPLICATION->IncludeComponent("bitrix:news.list", "similar_videos_scroll", Array(
                    "IBLOCK_TYPE" => 'main_content',
                    "IBLOCK_ID" => 5,
                    "NEWS_COUNT" => 99,
                    "SORT_BY1" => 'ACTIVE_FROM',
                    "SORT_ORDER1" => 'DESC',
                    "SORT_BY2" => 'ID',
                    "SORT_ORDER2" => 'DESC',
                    "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
                    "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'DATE_INTERVAL', ''],
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
                    "FILTER_NAME" => 'arrEventsDetailList',
                    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                    "CHECK_DATES" => 'N',
                    'FOTO_PREVIEW' => $arResult['PICTURE']['SRC'],
                    'CURRENT_ID' => $arResult['ID'],
                ), $component);?>
                <!-- блок со слайдером -->
                <?$APPLICATION->IncludeComponent("bitrix:news.list", "similar_videos_slider", Array(
                    "IBLOCK_TYPE" => 'main_content',
                    "IBLOCK_ID" => 6,
                    "NEWS_COUNT" => 99,
                    "SORT_BY1" => 'ACTIVE_FROM',
                    "SORT_ORDER1" => 'DESC',
                    "SORT_BY2" => 'ID',
                    "SORT_ORDER2" => 'DESC',
                    "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
                    "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'DATE_INTERVAL', ''],
                    "DETAIL_URL" => '/competitions/#SECTION_CODE_PATH#/?ID=#ELEMENT_ID#',
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
                    "FILTER_NAME" => 'arrEventsDetailList',
                    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                    "CHECK_DATES" => 'N',
                    'FOTO_PREVIEW' => $arResult['PICTURE']['SRC'],
                    'CURRENT_ID' => $arResult['ID'],
                ), $component);?>
            </div>
            <div class="event__row event__row_footer">
                <a href="<?=$arResult['LIST_PAGE_URL']?>" class="i-button">В список событий</a>
            </div>
        </div>
    </div>
</section>
<?php
global $arrEventsDetailListMore;
$arrEventsDetailListMore['!ID'] = $arResult['ID'];
?>
<?$APPLICATION->IncludeComponent("bitrix:news.list", "similar_videos_slider_new", Array(
    "IBLOCK_TYPE" => 'main_content',
    "IBLOCK_ID" => 5,
    "NEWS_COUNT" => 9,
    "SORT_BY1" => 'ACTIVE_FROM',
    "SORT_ORDER1" => 'DESC',
    "SORT_BY2" => 'ID',
    "SORT_ORDER2" => 'DESC',
    "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
    "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'DATE_INTERVAL', ''],
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
    "FILTER_NAME" => 'arrEventsDetailListMore',
    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
    "CHECK_DATES" => 'Y',
), $component);?>

