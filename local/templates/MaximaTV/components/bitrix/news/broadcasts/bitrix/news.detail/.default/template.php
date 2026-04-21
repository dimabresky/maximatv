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

$this->setFrameMode(true);
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

    switch ($arResult['PROPERTIES']['DISCIPLINE']['VALUE_XML_ID']) {
        case 'dressage':
        case 'jumping':
            $disciplineCode = $arResult['PROPERTIES']['DISCIPLINE']['VALUE_XML_ID'];
            break;
        default:
            $disciplineCode = 'dressage';
            break;
    }
    if ($arResult['PREVIEW_PICTURE']) {
        $preview = \CFile::ResizeImageGet(
            $arResult['PREVIEW_PICTURE'],
            ['width' => 1096, 'height' => 553],
            BX_RESIZE_IMAGE_EXACT
        );
    } else {
        $preview = [
            'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'broadcast.' . $disciplineCode . '.jpg'
        ];
    }

    $fvHelper = new FavoritesHelper();
    $curUser = $USER->GetId();
?>
<section class="i-section event__main event__main_broadcast">
    <div class="g-wrap">
        <div class="event__row event__row_header">
            <div class="event__header event__header_broadcast">
                <?php if ($isLive) { ?>
                    <div class="event__label">live</div>
                <?php } ?>
                <h1 class="event__title"><?=$arResult['NAME']?></h1>
                <?php if ($curUser !== null) { ?>
                    <button class="event__favorite js-favorite <?=$fvHelper->isItemExist($curUser, $arResult['ID']) ? 'is-active' : ''?>" data-eid="<?=$arResult['ID']?>" data-uid="<?=$curUser?>">Избранное</button>
                <?php } ?>
                <div class="favorite-mess js-favorite-mess">
                    <p class="favorite-mess__text js-delete">Видео удалено из раздела "Избранное"</p>
                    <p class="favorite-mess__text js-add">Видео добавлено в раздел "Избранное"</p>
                </div>
                <?php if ($arResult['PROPERTIES']['PROTOCOL']['VALUE'] !== '') { ?>
                    <div class="event__info event__info_mobile">
                        <a href="<?=CFile::GetPath($arResult['PROPERTIES']['PROTOCOL']['VALUE'])?>" class="event__link event__link_protocol" target="_blank">Стартовый протокол</a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if (in_array($arResult['PROPERTIES']['ACCESS']['VALUE_ENUM_ID'], $arParams['ACCESS_FILTER']) || $arParams['BROADCAST_ACCESS']) { ?>
            <div class="event__row">
                <div class="event__video">
                    <?php if ($arResult['PROPERTIES']['WIDGET']['VALUE'] === '') { ?>
                        <img src="<?=$preview['src']?>" class=""/>
                    <?php } else { ?>
                        <style>
                            .event__video {
                                overflow: hidden;
                                padding-top: 56.25%;
                                position: relative;
                            }

                            .event__video iframe {
                                border: 0;
                                height: 100%;
                                left: 0;
                                position: absolute;
                                top: 0;
                                width: 100%;
                            }
                        </style>
                        <?=$arResult['PROPERTIES']['WIDGET']['~VALUE']['TEXT']?>
                    <?php } ?>
                </div>
            </div>
        <?php } elseif ($arResult['PROPERTIES']['ACCESS']['VALUE_XML_ID'] === 'registered' && !$arParams['IS_AUTH']) { ?>
            <div class="event__row">
                <div class="event__video">
                    <div class="event__video-preview event__video-preview_disabled" style="background-image:url(<?=$preview['src']?>)">
                        <span class="event__video-message">
                            Просмотр этой трансляции доступен только для зарегистрированных пользователей.<br>Зарегистрируйтесь на сайте,<br>чтобы наблюдать за нашими мероприятиями в режиме реального времени.
                        </span>
                    </div>
                </div>
            </div>
        <?php } else {?>
            <div class="event__row">
                <div class="event__video">
                    <div class="event__video-preview event__video-preview_disabled" style="background-image:url(<?=$preview['src']?>)">
                        <span class="event__video-message">
                            Просмотр этой трансляции не включен в ваш тариф.<br>Чтобы наблюдать за нашими мероприятиями в режиме реального времени, подключите тариф с расширенным доступом или купите билет.
                            <br><br>
                            <?php if ($arParams['IS_AUTH']) { ?>
                                <a href="/lk/buy-broadcast/<?=$arResult['ID']?>/" class="i-button">Купить билет на трансляцию</a>
                                <br><br>
                                <?php
                                    if(!empty($arResult['PROPERTIES']['PRICE']['VALUE'])) {
                                        echo $arResult['PROPERTIES']['PRICE']['VALUE'] . ' руб.';
                                    }
                                ?>
                            <?php } else { ?>
                                <a href="javascript:void(0);" class="i-button" data-remodal-target="login_register">Авторизоваться</a>
                                <br><br>
                                <?php
                                if(!empty($arResult['PROPERTIES']['PRICE']['VALUE'])) {
                                    echo $arResult['PROPERTIES']['PRICE']['VALUE'] . ' руб.';
                                }
                                ?>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="event__row event__row_info">
            <div class="event__info event__info_wide">
                <div class="event__info-col">
                    <p class="event__info-address"><?=$arResult['PROPERTIES']['PLACE']['VALUE']?></p>
                    <p class="event__info-type">дисциплина:</p>
                    <p><?=$arResult['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                    <p class="event__info-date"><?=$arResult['PROPERTIES']['DATE_INTERVAL']['VALUE']?></p>
                </div>
                <?php if ($arResult['PROPERTIES']['PROTOCOL']['VALUE'] !== '') { ?>
                    <a href="<?=CFile::GetPath($arResult['PROPERTIES']['PROTOCOL']['VALUE'])?>" class="event__link event__link_protocol hide-on-mobile" target="_blank">Стартовый протокол</a>
                <?php } ?>
            </div>
        </div>
        <?/*br><br>
        <div class="event__row">
            <a href="https://www.volkswagen-commercial.ru/ru.html" target="_blank">
                <img src="<?=SITE_TEMPLATE_PATH?>/images/banner_gefest.jpg">
            </a>
        </div*/?>
        <div class="event__row event__row_footer">
            <a href="<?=$arResult['LIST_PAGE_URL']?>" class="i-button">В список трансляций</a>
        </div>
    </div>
</section>
