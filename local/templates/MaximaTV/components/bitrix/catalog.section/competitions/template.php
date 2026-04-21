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

global $APPLICATION, $USER;

$this->setFrameMode(true);

$this->addExternalCss("/local/templates/MaximaTV/css/videojs/video-js.css");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/video.js");
$this->addExternalJS("/local/templates/MaximaTV/js/videojs/videojs.hotkeys.min.js");

if (count($arResult['ITEMS']) > 0) {
    if (isset($_REQUEST['ID']) && (int)$_REQUEST['ID'] > 0) {
        $mainItemId = (int)$_REQUEST['ID'];
        foreach ($arResult['ITEMS'] as $item) {
            if ($item['ID'] == $mainItemId) {
                $mainItem = $item;
                break;
            }
        }
    } else {
        $mainItem = $arResult['ITEMS'][0];
    }

    $arFiles = [];
    $videoFile = '';
    if (!empty($mainItem['PROPERTIES']['PATH_TO_VIDEO']['VALUE'])) {
        $currentVideoIndex = (int)Application::getInstance()->getContext()->getRequest()->get('VNUM');
        $videoPath = /*'/upload/yadisk' . */$mainItem['PROPERTIES']['PATH_TO_VIDEO']['VALUE'];
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
    switch ($arResult['UF_DISCIPLINE']) {
        case '1':
            $disciplineCode = 'dressage';
            break;
        case '2':
            $disciplineCode = 'jumping';
            break;
        default:
            $disciplineCode = 'dressage';
            break;
    }
    if ($mainItem['PREVIEW_PICTURE']) {
        $preview = \CFile::ResizeImageGet(
            $mainItem['PREVIEW_PICTURE'],
            ['width' => 761, 'height' => 553],
            BX_RESIZE_IMAGE_EXACT
        );
    } else {
        $preview = [
            'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg'
        ];
    }
    if (!isset($arResult['PICTURE']['SRC'])) {
        $arResult['PICTURE'] = [
            'SRC' => PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg'
        ];
    }
    if (empty($mainItem['PROPERTIES']['DATE_INTERVAL']['VALUE'])) {
        $mainItem['PROPERTIES']['DATE_INTERVAL'] = [
            'VALUE' => $arResult['UF_DATE']
        ];
    }
    if (empty($mainItem['PROPERTIES']['PLACE']['VALUE'])) {
        $mainItem['PROPERTIES']['PLACE'] = [
            'VALUE' => $arResult['UF_PLACE']
        ];
    }
    $this->__component->arResult['MAIN_ITEM'] = $mainItem;;
    $this->__component->SetResultCacheKeys(['MAIN_ITEM']);

    $fvHelper = new FavoritesHelper();
    $curUser = $USER->GetId();
?>
    <style>
        .capitalized {text-transform: capitalize;}
        <?php if ($mainItem['ID'] != 9781) { ?>
            .event__video .event__video-preview video {transform: none;}
            .event__video-preview .video-js {position: static;}
            .event__video-preview .video-js .vjs-big-play-button {
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: -25px;
                margin-left: -45px;
            }
        <?php } ?>
    </style>
    <section class="i-section event__main">
        <div class="g-wrap">
            <div class="event__main-wrap">
                <div class="event__col">
                    <div class="event__row event__row_header">
                        <div class="event__header">
                            <p class="event__date"><?=$mainItem['PROPERTIES']['DATE_INTERVAL']['VALUE']?></p>
                            <h1 class="event__title"><?=$mainItem['NAME']?></h1>
                            <p class="event__stage"><?/*1 этап*/?></p>
                            <?/*a href="javascript:alert('доступно только для зарегистрированных пользователей')" class="event__link event__link_download" <?//data-remodal-target="download"?>>Скачать</a*/?>
                            <?php if ($curUser !== null) { ?>
                                <button class="event__favorite js-favorite <?=$fvHelper->isItemExist($curUser, $mainItem['ID']) ? 'is-active' : ''?>" data-eid="<?=$mainItem['ID']?>" data-uid="<?=$curUser?>">Избранное</button>
                            <?php } ?>
                            <div class="favorite-mess js-favorite-mess">
                                <p class="favorite-mess__text js-delete">Видео удалено из раздела "Избранное"</p>
                                <p class="favorite-mess__text js-add">Видео добавлено в раздел "Избранное"</p>
                            </div>
                        </div>
                    </div>
                    <?php if (in_array($mainItem['PROPERTIES']['ACCESS']['VALUE_ENUM_ID'], $arParams['ACCESS_FILTER'])) { ?>
                        <div class="event__video js-event-slider" data-item-count="3">
                            <?php if ($videoFile !== '') { ?>
                            <?php if ($mainItem['ID'] == 9781) { ?>
                                <div class="event__video-preview">
                                    <video
                                            src="<?=$videoFile?>"
                                            controls="controls"
                                            autoplay="autoplay"
                                            width="100%"
                                    >
                                </div>
                                <?php } else { ?>
                                    <div class="event__video-preview">
                                        <video
                                            id="MaximaTV-video"
                                            class="video-js"
                                            controls
                                            preload="auto"
                                            poster="<?=$preview['src']?>"
                                            data-setup='{"fluid": true}'
                                        >
                                            <source src="<?=$videoFile?>" />
                                            <p class="vjs-no-js">
                                                To view this video please enable JavaScript, and consider upgrading to a
                                                web browser that supports HTML5 video
                                            </p>
                                        </video>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            videojs('MaximaTV-video').ready(function() {
                                                this.hotkeys({
                                                    volumeStep: 0.1,
                                                    seekStep: 5,
                                                    enableModifiersForNumbers: false
                                                });
                                            });
                                            $('.event__video video').on('contextmenu', function() {
                                                return false;
                                            });
                                        });
                                    </script>
                                <?php } ?>
                            <?php } else { ?>
                                <?php
                                $preview = \CFile::ResizeImageGet(
                                    $mainItem['PREVIEW_PICTURE'],
                                    ['width' => 761, 'height' => 553],
                                    BX_RESIZE_IMAGE_EXACT
                                );
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
                                    Просмотр записей соревнований не включен в ваш тариф. Для просмотра, подключите тариф с доступом к архиву.
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
                            <p class="event__info-address"><?=$mainItem['PROPERTIES']['PLACE']['VALUE']?></p>
                            <p class="event__info-type">дисциплина:</p>
                            <p><?=$arResult['DISCIPLINES'][$arResult['UF_DISCIPLINE']]?></p>
                            <?/*a href="javascript:void(0)" class="event__link event__link_result" data-remodal-target="results">Результаты</a*/?>
                        </div>
                    </div>
                </div>
                <div class="event__col event__col_slim">
                    <div class="event__row event__row_header event__row_flex">
                        <h2 class="event__col-title">Видео соревнований</h2>
                    </div>
                    <?php
                        global $arrCompetitionsDetailList;
                        $arrCompetitionsDetailList = $GLOBALS[$arParams["FILTER_NAME"]];
                        $arrCompetitionsDetailList['IBLOCK_SECTION_ID'] = $mainItem['IBLOCK_SECTION_ID'];
                        //$arrCompetitionsDetailList['!ID'] = $mainItem['ID'];
                    ?>
                    <!-- блок со скроллом -->
                    <?$APPLICATION->IncludeComponent("bitrix:news.list", "similar_videos_scroll", Array(
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
                        "FILTER_NAME" => 'arrCompetitionsDetailList',
                        "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                        "CHECK_DATES" => 'N',
                        'FOTO_PREVIEW' => $arResult['PICTURE']['SRC'],
                        'CURRENT_ID' => $mainItem['ID'],
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
                        "FILTER_NAME" => 'arrCompetitionsDetailList',
                        "HIDE_LINK_WHEN_NO_DETAIL" => 'N',
                        "CHECK_DATES" => 'N',
                        'FOTO_PREVIEW' => $arResult['PICTURE']['SRC'],
                        'CURRENT_ID' => $mainItem['ID'],
                    ), $component);?>
                </div>
                <div class="event__row event__row_footer">
                    <a href="<?=$arResult['LIST_PAGE_URL']?>" class="i-button">В список соревнований</a>
                </div>
            </div>
        </div>

        <div data-remodal-id="download" data-remodal-options="hashTracking: false" class="remodal modal modal_slim">
            <a data-remodal-action="close" class="modal__close"></a>
            <div class="modal__content modal-confirmation">
                <p class="modal-confirmation__title">Хотите скачать видео?</p>
                <div class="modal-confirmation__row">
                    <a href="javascript:void(0)" class="i-button">Скачать полностью&nbsp;<span class="i-button__text-sub">1,5 Гб</span></a>
                </div>
                <div class="modal-confirmation__row">
                    <a href="javascript:void(0)" class="i-button i-button_light" data-remodal-target="fragment">Скачать фрагмент</a>
                </div>
            </div>
        </div>

        <div data-remodal-id="fragment" data-remodal-options="hashTracking: false" class="remodal modal modal_middle">
            <a data-remodal-action="close" class="modal__close"></a>
            <div class="modal__content modal-confirmation">
                <p class="modal-confirmation__title">Выберите фрагмент видео</p>
                <div class="event-fragment js-scroll">
                    <div class="event-fragment__wrap">
                        <div class="event-fragment__item">
                            <img src="images/competitions/event-fragment1.jpg" class="event-fragment__img" alt="Аленин Агафон Пахомович" />
                            <p class="event-fragment__name">Аленин Агафон Пахомович</p>
                            <p class="event-fragment__time">12:45 - 17:04</p>
                            <div class="event-fragment__footer">
                                <a href="javascript:void(0)" class="event__link event__link_download">Скачать</a>
                                <p class="event-fragment__data">MP4, 850 Мб</p>
                            </div>
                        </div>
                        <div class="event-fragment__item">
                            <img src="images/competitions/event-fragment2.jpg" class="event-fragment__img" alt="Аленин Агафон Пахомович" />
                            <p class="event-fragment__name">Кораблин Егор Дмитриевич</p>
                            <p class="event-fragment__time">15:00 - 21:03</p>
                            <div class="event-fragment__footer">
                                <a href="javascript:void(0)" class="event__link event__link_download">Скачать</a>
                                <p class="event-fragment__data">MP4, 850 Мб</p>
                            </div>
                        </div>
                        <div class="event-fragment__item">
                            <img src="images/competitions/event-fragment3.jpg" class="event-fragment__img" alt="Аленин Агафон Пахомович" />
                            <p class="event-fragment__name">Никулаевич Ефим Олегович</p>
                            <p class="event-fragment__time">15:00 - 21:03</p>
                            <div class="event-fragment__footer">
                                <a href="javascript:void(0)" class="event__link event__link_download">Скачать</a>
                                <p class="event-fragment__data">MP4, 850 Мб</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div data-remodal-id="results" data-remodal-options="hashTracking: false" class="remodal modal modal_results">
            <a data-remodal-action="close" class="modal__close"></a>
            <div class="modal__content modal-results">
                <h2 class="modal-results__title">Результаты</h2>
                <div class="modal-results__content js-scroll">
                    <div class="result-item">
                        <div class="result-item__header">
                            <div class="result-item__td result-item__td--place">Место</div>
                            <div class="result-item__td result-item__td--name">Всадник</div>
                            <div class="result-item__td result-item__td--pass">FEI Passport No</div>
                            <div class="result-item__td result-item__td--horse">Лошадь</div>
                            <div class="result-item__td result-item__td--point">Баллы</div>
                        </div>
                        <div class="result-item__row">
                            <div class="result-item__td result-item__td--place">1</div>
                            <div class="result-item__td result-item__td--name">
                                <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                                <b>Яковлева</b> Елизавета / <b>Yakovleva</b> Elizaveta / 2001 г.р. / RUS
                            </div>
                            <div class="result-item__td result-item__td--pass">105OQ89</div>
                            <div class="result-item__td result-item__td--horse">Донт Дрим</div>
                            <div class="result-item__td result-item__td--point">693,0</div>
                        </div>
                    </div>
                    <div class="result-item">
                        <div class="result-item__header">
                            <div class="result-item__td result-item__td--place">Место</div>
                            <div class="result-item__td result-item__td--name">Всадник</div>
                            <div class="result-item__td result-item__td--pass">FEI Passport No</div>
                            <div class="result-item__td result-item__td--horse">Лошадь</div>
                            <div class="result-item__td result-item__td--point">Баллы</div>
                        </div>
                        <div class="result-item__row">
                            <div class="result-item__td result-item__td--place">2</div>
                            <div class="result-item__td result-item__td--name">
                                <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                                <b>Красильникова</b> Юлиса / <b>Krasilnikova</b> Yulisa / 2003 г.р. / RUS
                            </div>
                            <div class="result-item__td result-item__td--pass">106GZ61</div>
                            <div class="result-item__td result-item__td--horse">Эдвард Сир</div>
                            <div class="result-item__td result-item__td--point">674,0</div>
                        </div>
                    </div>
                    <div class="result-item">
                        <div class="result-item__header">
                            <div class="result-item__td result-item__td--place">Место</div>
                            <div class="result-item__td result-item__td--name">Всадник</div>
                            <div class="result-item__td result-item__td--pass">FEI Passport No</div>
                            <div class="result-item__td result-item__td--horse">Лошадь</div>
                            <div class="result-item__td result-item__td--point">Баллы</div>
                        </div>
                        <div class="result-item__row">
                            <div class="result-item__td result-item__td--place">3</div>
                            <div class="result-item__td result-item__td--name">
                                <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                                <b>Иванова</b> Александра / <b>Ivanova</b> Alexandra / 2001 г.р. / RUS
                            </div>
                            <div class="result-item__td result-item__td--pass">106GZ61</div>
                            <div class="result-item__td result-item__td--horse">Эдвард Сир</div>
                            <div class="result-item__td result-item__td--point">666,5</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } else { /*count($arResult['ITEMS']) == 0*/ ?>
<section class="i-section event__main">
    <div class="g-wrap">
        <div class="event__main-wrap">
            <div class="event__col">
                <div class="event__row event__row_header">
                    <div class="event__header">
                        <p class="event__stage">Видео для этого соревновательного дня нет или они недоступны для Вашего тарифа</p>
                    </div>
                </div>
            </div>
            <div class="event__row event__row_footer">
                <a href="<?=$arResult['LIST_PAGE_URL']?>" class="i-button">В список соревнований</a>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<?php if (!empty($mainItem['PROPERTIES']['PATH_TO_PHOTO']['VALUE'])) { ?>
    <?$APPLICATION->IncludeComponent(
        "mxm:photo.list",
        "",
        array(
            'FILE_PATH' => $mainItem['PROPERTIES']['PATH_TO_PHOTO']['VALUE'],
            'SECTION_TITLE' => 'Фото соревнования: ' . $mainItem['NAME'],
            'TYPE' => 'e',
            'ID' => $mainItem['ID']

        )
    );?>
<?php } ?>
<!-- Другие этапы соревнования -->
<section class="i-section event__others">
    <div class="g-wrap">
        <h2>Другие соревнования этого месяца</h2>
        <?php
            global $arrCompetitionsDetailListSimilar;
            $arrCompetitionsDetailListSimilar['!ID'] = $arResult['ID'];
        ?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "competitions_list_video_others_adaptive",
            array(
                "IBLOCK_TYPE" => 'main_content',
                "IBLOCK_ID" => 6,
                "CACHE_TYPE" => 'A',
                "CACHE_TIME" => '36000000',
                "CACHE_FILTER" => 'N',
                "CACHE_GROUPS" => 'N',
                "COUNT_ELEMENTS" => "Y",
                "TOP_DEPTH" => 2,
                "SECTION_ID" => $arResult['IBLOCK_SECTION_ID'],
                "SECTION_URL" => '/competitions/#SECTION_CODE_PATH#/',
                "VIEW_MODE" => 'LIST',
                "SHOW_PARENT_NAME" => 'N',
                "HIDE_SECTION_NAME" => 'Y',
                "ADD_SECTIONS_CHAIN" => 'N',
                "SECTION_USER_FIELDS" => array("UF_DATE_INTERVAL", "UF_DISCIPLINE", "UF_PLACE", "UF_FOTO", ""),
                "FILTER_NAME" => 'arrCompetitionsDetailListSimilar',
            ),
            $component
        );?>
    </div>
</section>

