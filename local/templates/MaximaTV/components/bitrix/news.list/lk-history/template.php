<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<main class="lk__main js-tabs">
    <div class="g-wrap">
        <?php if (count($arResult['ITEMS']) > 0) {?>
            <div class="lk__header">
                <h1 class="lk__title">История покупок</h1>
            </div>
            <div class="lk__purchase js-tabs-content is-active" data-group="01">
                <div class="lk__purchase-wrap">
                    <?php foreach ($arResult['ITEMS'] as $key => $arItem) { ?>
                        <?php
                            $photoOrder = $arItem['PROPERTIES']['ORDER_TYPE']['VALUE_XML_ID'] == 'photo'
                                ? true : false;
                            $broadcast = $arResult['BROADCASTS'][$arItem['PROPERTIES']['BROADCAST_ID']['VALUE']];
                            $broadcastDate = strtotime($broadcast['PROPERTY_LIVE_FROM_VALUE'])
                        ?>
                        <div class="purchase">
                            <a href="<?= $photoOrder
                                ? 'javascript:void(0)' : '/broadcasts/' .$broadcast['CODE'] . '/' ?>" <?
                                if($photoOrder) echo 'data-remodal-target=previewPhoto' .$key;
                            ?> class="purchase__link"></a>
                            <div class="purchase__header">
                                <p class="purchase__date"><?= !$photoOrder ? \Maxima\Helpers\DateHelper::getFullRuDate($broadcastDate) : '' ?></p>
                                <? if($arItem['PROPERTIES']['PHOTO']['VALUE']): ?>
                                    <p class="purchase__count">
                                        <a href="javascript:void(0)" data-remodal-target=previewPhoto<?= $key;?>>
                                            <?= count($arItem['PROPERTIES']['PHOTO']['VALUE']) ?> фото
                                        </a>
                                    </p>
                                <? endif; ?>
                            </div>
                            <div class="purchase__content">
                                <p class="purchase__stage"></p>
                                <p class="purchase__title"><?=$photoOrder ?  'Заказ №' . $arItem['ID'] . '. Покупка фотографий' : $broadcast['NAME']?></p>
                            </div>
                            <div class="purchase__footer">
                                <div class="purchase__info">
                                    <p class="purchase__info-name">Дата покупки:</p>
                                    <p class="purchase__info-value"><?=explode(' ', $arItem['TIMESTAMP_X'])[0]?></p>
                                </div>
                                <div class="purchase__info">
                                    <p class="purchase__info-name">Сумма:</p>
                                    <p class="purchase__info-value"><?=$arItem['PROPERTIES']['PRICE']['VALUE']?> <span class="rub"></span></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="lk__header">
                <h1 class="lk__title">Вы еще не совершали покупок</h1>
            </div>
        <?php } ?>
    </div>
    <?php foreach ($arResult['ITEMS'] as $key => $arItem) { ?>
        <?php if( $arItem['PROPERTIES']['ORDER_TYPE']['VALUE_XML_ID'] != 'photo') continue; ?>
        <div data-remodal-id="previewPhoto<?= $key ?>" data-remodal-options="hashTracking: false" class="remodal modal modal_photo-preview">
            <a data-remodal-action="close" class="modal__close"></a>
            <div class="modal__content">
                <form class="photo-preview js-check-group js-photo-preview-list" action="/lk/history/download/">
                    <input type="hidden" name="order_id" value="<?= $arItem['ID'] ?>"  />
                    <div class="photo-preview__header">
                        <p class="photo-preview__place"></p>
                        <div class="photo-preview__header-row">
                            <h3 class="photo-preview__title">Заказ №<?= $arItem['ID'] ?>. Покупка фотографий</h3>
                            <p class="photo-preview__count"><?= count($arItem['PROPERTIES']['PHOTO']['VALUE'])  ?> фото</p>
                        </div>
                        <? if(false): ?>
                            <div class="photo-preview__header-row">
                                <p class="photo-preview__stage">1 этап</p>
                                <div class="photo-preview__discipline"><p>дисциплина:</p><b>Конкур</b></div>
                                <p class="photo-preview__date">12.03.2018 - 16.03.2018</p>
                            </div>
                        <? endif ?>
                    </div>
                    <div class="photo-preview__content js-scroll">
                        <div class="photo-preview__wrap">
                            <?
                                if(count( $arItem['PROPERTIES']['PHOTO']['VALUE']) == 1) :
                                    $arItem['DISPLAY_PROPERTIES']['PHOTO']['FILE_VALUE'] = [$arItem['DISPLAY_PROPERTIES']['PHOTO']['FILE_VALUE']];
                                endif;
                            ?>
                            <? foreach($arItem['DISPLAY_PROPERTIES']['PHOTO']['FILE_VALUE'] as $photo): ?>
                                <?
                                $prev = CFile::ResizeImageGet(
                                    $photo['ID'],
                                    ['width' => 768, 'height' => 718],
                                    BX_RESIZE_IMAGE_PROPORTIONAL,
                                    false,
                                    false
                                );
                                //\Maxima\Helpers\CommonHelper::aDump($photo);
                                $photoSize = round((float)$photo['FILE_SIZE'] / 1024 / 1024, 0);
                                ?>
                                <div class="photo-preview__item" style="background-image:url(<?= $prev['src'] ?>)">
                                    <label class="photo-preview__checkbox checkbox">
                                    <span class="checkbox__box">
                                        <input type="checkbox" class="checkbox__control js-check-item" name="photo[]"  value="<?= $photo['ID'] ?>" data-size="<?= $photo['FILE_SIZE'] ?>"/>
                                        <span class="checkbox__indicator checkbox__indicator_blue"></span>
                                    </span>
                                    </label>
                                    <div class="photo-preview__download">
                                        <a href="<?=$photo['SRC']?>" download class="card__download-link">
                                            <span class="card__download-icon">
                                                <svg class="i-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M7 10L12 15L17 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M12 15V3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
                                            <span class="card__download-text">Скачать фото</span>
                                            <span class="card__download-desc"><?=$photoSize?> Мб</span>
                                        </a>
                                    </div>
                                </div>
                            <? endforeach; ?>

                            <div class="photo-preview__more">
                                <a href="javascript:void(0)" class="i-button js-photo-preview-more">Показать еще</a>
                            </div>
                        </div>
                    </div>
                    <div class="photo-preview__footer">
                        <div class="lk-rides__download">
                            <div class="js-check-download">
                                <a href="javascript:void(0)" class="lk-rides__download-link js-download-selected-photo">
                        <span class="lk-rides__download-icon">
                            <svg class="i-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M7 10L12 15L17 10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12 15V3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                                    <span class="lk-rides__download-text">Скачать выбранное в архиве</span>
                                </a>
                                <span class="lk-rides__download-desc js-check-size"></span>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="lk-rides__link js-check-all"><span>выбрать все</span></a>
                        <a href="javascript:void(0)" class="lk-rides__link lk-rides__link_red js-reset-all"><span>отменить выбор</span></a>
                    </div>
                </form>
            </div>
        </div>
    <? } ?>
</main>

