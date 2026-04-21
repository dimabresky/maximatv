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
$this->setFrameMode(true);
?>
<section class="i-section event__main">
    <div class="g-wrap">
        <div class="event__row event__row_header">
            <div class="event__col event__header">
                <p class="event__date"><?=$arResult['PROPERTIES']['DATE_INTERVAL']['VALUE']?></p>
                <h1 class="event__title"><?=$arResult['NAME']?></h1>
                <p class="event__stage"><?/*1 этап*/?></p>
                <a href="javascript:alert('доступно только для зарегистрированных пользователей')" class="event__link event__link_download" <?//data-remodal-target="download"?>>Скачать</a>
            </div>
            <div class="event__col event__col_slim">
                <h2 class="event__col-title">Видео соревнований</h2>
            </div>
        </div>
        <div class="event__row">
            <div class="event__col event__video js-event-slider" data-item-count="3">
                <?php if ($arResult['PROPERTIES']['WIDGET']['VALUE'] === '') { ?>
                    <?php
                    $preview = \CFile::ResizeImageGet(
                        $arResult['PREVIEW_PICTURE'],
                        ['width' => 761, 'height' => 553],
                        BX_RESIZE_IMAGE_EXACT
                    );
                    ?>
                    <img src="<?=$preview['src']?>" class=""/>
                <?php } else { ?>
                    <?=$arResult['PROPERTIES']['WIDGET']['~VALUE']['TEXT']?>
                <?php } ?>
            </div>
            <?php
                global $arrCompetitionsDetailList;
                $arrCompetitionsDetailList['IBLOCK_SECTION_ID'] = $arResult['IBLOCK_SECTION_ID'];
                $arrCompetitionsDetailList['!ID'] = $arResult['ID'];
            ?>
            <?$APPLICATION->IncludeComponent("bitrix:news.list", "similar_videos", Array(
                "IBLOCK_TYPE" => 'main_content',
                "IBLOCK_ID" => 6,
                "NEWS_COUNT" => 9,
                "SORT_BY1" => 'ACTIVE_FROM',
                "SORT_ORDER1" => 'DESC',
                "SORT_BY2" => 'ID',
                "SORT_ORDER2" => 'DESC',
                "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
                "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'DATE_INTERVAL', ''],
                "DETAIL_URL" => '/competitions/#ELEMENT_CODE#/',
                "SECTION_URL" => '/competitions/',
                "IBLOCK_URL" => '/competitions/',
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
            ), $component);?>
        </div>
        <div class="event__row event__row_info">
            <div class="event__col event__info">
                <p class="event__info-address"><?=$arResult['PROPERTIES']['PLACE']['VALUE']?></p>
                <p class="event__info-type">дисциплина:</p>
                <p><?=$arResult['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                <a href="javascript:void(0)" class="event__link event__link_result" data-remodal-target="results">Результаты</a>
            </div>
        </div>
        <div class="event__row event__row_footer">
            <a href="<?=$arResult['LIST_PAGE_URL']?>" class="i-button">В список соревнований</a>
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
                <div class="result-item">
                    <div class="result-item__header">
                        <div class="result-item__td result-item__td--place">Место</div>
                        <div class="result-item__td result-item__td--name">Всадник</div>
                        <div class="result-item__td result-item__td--pass">FEI Passport No</div>
                        <div class="result-item__td result-item__td--horse">Лошадь</div>
                        <div class="result-item__td result-item__td--point">Баллы</div>
                    </div>
                    <div class="result-item__row">
                        <div class="result-item__td result-item__td--place">4</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Просветова</b> Анастасия / <b>Prosvetova</b> Anastasia / 2001 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Акапулько</div>
                        <div class="result-item__td result-item__td--point">666,5</div>
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
                        <div class="result-item__td result-item__td--place">5</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Иванова</b> Полина / <b>Ivanova</b> Polina / 2003 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Ратцингер</div>
                        <div class="result-item__td result-item__td--point">660,5</div>
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
                        <div class="result-item__td result-item__td--place">6</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Иванова</b> Александра / <b>Ivanova</b> Alexandra / 2001 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Данте Вельтино</div>
                        <div class="result-item__td result-item__td--point">658,0</div>
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
                        <div class="result-item__td result-item__td--place">7</div>
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
                        <div class="result-item__td result-item__td--place">8</div>
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
                        <div class="result-item__td result-item__td--place">9</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Иванова</b> Александра / <b>Ivanova</b> Alexandra / 2001 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Эдвард Сир</div>
                        <div class="result-item__td result-item__td--point">666,5</div>
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
                        <div class="result-item__td result-item__td--place">10</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Просветова</b> Анастасия / <b>Prosvetova</b> Anastasia / 2001 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Акапулько</div>
                        <div class="result-item__td result-item__td--point">666,5</div>
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
                        <div class="result-item__td result-item__td--place">11</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Иванова</b> Полина / <b>Ivanova</b> Polina / 2003 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Ратцингер</div>
                        <div class="result-item__td result-item__td--point">660,5</div>
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
                        <div class="result-item__td result-item__td--place">12</div>
                        <div class="result-item__td result-item__td--name">
                            <img src="images/icons/icon-flag.svg" class="result-item__flag" alt=""/>
                            <b>Иванова</b> Александра / <b>Ivanova</b> Alexandra / 2001 г.р. / RUS
                        </div>
                        <div class="result-item__td result-item__td--pass">106GZ61</div>
                        <div class="result-item__td result-item__td--horse">Данте Вельтино</div>
                        <div class="result-item__td result-item__td--point">658,0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Другие этапы соревнования -->
<section class="i-section event__others">
    <div class="g-wrap">
        <h2>Другие этапы соревнования</h2>
        <?$APPLICATION->IncludeComponent("bitrix:news.list", "similar_etapes", Array(
            "IBLOCK_TYPE" => 'main_content',
            "IBLOCK_ID" => 6,
            "NEWS_COUNT" => 9,
            "SORT_BY1" => 'ACTIVE_FROM',
            "SORT_ORDER1" => 'DESC',
            "SORT_BY2" => 'ID',
            "SORT_ORDER2" => 'DESC',
            "FIELD_CODE" => ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', ''],
            "PROPERTY_CODE" => ['DISCIPLINE', 'TYPE', 'DATE_INTERVAL', ''],
            "DETAIL_URL" => '/competitions/#ELEMENT_CODE#/',
            "SECTION_URL" => '/competitions/',
            "IBLOCK_URL" => '/competitions/',
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
        ), $component);?>
    </div>
</section>

<?php if (false && is_array($arResult['PROPERTIES']['FOTO']['VALUE'])) { ?>
    <!-- Фото соревнования -->
    <section class="i-section event__photo">
        <div class="g-wrap">
            <div class="i-section__header">
                <h2>Фото события</h2>
                <?/*a href="javascript:void(0)" class="event__photo-link">все фото</a*/?>
            </div>
            <div class="photo-list js-photo-list">
                <?php $index = 0; ?>
                <?php foreach ($arResult['PROPERTIES']['FOTO']['VALUE'] as $fotoId) { ?>
                    <?php
                        $class = '';
                        $index++;
                        switch ($index % 10) {
                            case 7:
                            case 1:
                                $class = 'photo-list__item_big';
                                $foto = \CFile::ResizeImageGet(
                                    $fotoId,
                                    ['width' => 650, 'height' => 396],
                                    BX_RESIZE_IMAGE_EXACT
                                );
                                break;
                            case 2:
                            case 6:
                                $class = 'photo-list__item_middle';
                                $foto = \CFile::ResizeImageGet(
                                    $fotoId,
                                    ['width' => 426, 'height' => 396],
                                    BX_RESIZE_IMAGE_EXACT
                                );
                                break;
                            default:
                                $foto = \CFile::ResizeImageGet(
                                    $fotoId,
                                    ['width' => 352, 'height' => 396],
                                    BX_RESIZE_IMAGE_EXACT
                                );
                                break;
                        }
                    ?>
                    <a href="javascript:void(0)" class="photo-list__item <?=$class?>"><img src="<?=$foto['src']?>"/></a>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>