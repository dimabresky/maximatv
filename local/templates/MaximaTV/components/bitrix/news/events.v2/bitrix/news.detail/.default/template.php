<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Maxima\Helpers\FavoritesHelper;
use Maxima\Helpers\VideoQualityHelper;

$this->setFrameMode(true);

$this->addExternalCss("/local/templates/MaximaTV/css/videojs/video-js.css");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/video.js");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/maxima-video-quality.js");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/videojs.hotkeys.min.js");
?>
<?php
    global $USER;

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

    $fvHelper = new FavoritesHelper();
    $curUser = $USER->GetId();
?>
<style>
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
            <div class="event__col event__col_wide">
                <div class="event__row event__row_header">
                    <div class="event__header">
                        <p class="event__date"><? if (!empty($arResult['PROPERTIES']['DATE_FROM']['VALUE']) && !empty($arResult['PROPERTIES']['DATE_TO']['VALUE'])): ?>
                                <?= $arResult['PROPERTIES']['DATE_FROM']['VALUE'] . ' - ' . $arResult['PROPERTIES']['DATE_TO']['VALUE'] ?>
                            <? endif ?></p>
                        <h1 class="event__title"><?=$arResult['NAME']?></h1>
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
                    <div class="event__video">
                        <?php if ($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'] !== '') { ?>
                            <?/*video
                                    src="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE']?>"
                                    poster="<?=$preview['src']?>"
                                    controls="controls"
                                    autoplay="autoplay"
                                    width="100%" height="100%"
                            */?>
                            <?php
                            $arVideoSources = VideoQualityHelper::getSourcesForWebPath(
                                (string)$arResult['PROPERTIES']['VIDEO_FILE']['VALUE']
                            );
                            $previewSrc = $preview['src'];
                            include $_SERVER['DOCUMENT_ROOT'] . '/local/templates/MaximaTV/include/maxima_video_player.php';
                            ?>
                        <?php } else { ?>
                            <div class="event__video-preview" style="background-image:url(<?=$preview['src']?>)"></div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="event__video">
                        <div class="event__video-preview event__video-preview_disabled" style="background-image:url(<?=$preview['src']?>)">
                            <span class="event__video-message">Просмотр записей мероприятий не включен в ваш тариф.<br>Для просмотра, подключите тариф с доступом к архиву.</span>
                        </div>
                    </div>
                <?php } ?>
                <div class="event__row event__row_info">
                    <div class="event__col event__info">
                        <p class="event__info-address"><?=$arResult['PROPERTIES']['PLACE']['VALUE']?></p>
                        <p class="event__info-type">дисциплина:</p>
                        <p><?=$arResult['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                    </div>
                </div>
            </div>
            <div class="event__row event__row_footer">
                <a href="<?=$arResult['LIST_PAGE_URL']?>" class="i-button">В список событий</a>
            </div>
        </div>
    </div>
</section>
<?php
global $arrEventsDetailList;
$arrEventsDetailList['!ID'] = $arResult['ID'];
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
    "FILTER_NAME" => 'arrEventsDetailList',
    "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
    "CHECK_DATES" => 'Y',
), $component);?>


<?$APPLICATION->IncludeComponent(
    "mxm:photo.list",
    "",
    array(
        'FILE_PATH' => $arResult['PROPERTIES']['PATH_TO_PHOTO']['VALUE'],
        'SECTION_TITLE' => 'Фото события',
        'TYPE' => 'e',
        'ID' => $arResult['ID']
    )

);?>
