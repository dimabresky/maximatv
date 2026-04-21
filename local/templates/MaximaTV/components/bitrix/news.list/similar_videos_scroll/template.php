<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<div class="event-video event-video_scroll js-scroll" data-min-width="1440">
    <?php if (count($arResult['ITEMS']) > 0) {?>
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <?php
                if (isset($arItem['PREVIEW_PICTURE']['ID'])) {
                    $preview = \CFile::ResizeImageGet(
                        $arItem['PREVIEW_PICTURE']['ID'],
                        ['width' => 315, 'height' => 196],
                        BX_RESIZE_IMAGE_EXACT
                    );
                    \Maxima\Helpers\CommonHelper::aDump($preview);
                } else {
                    $preview = null;
                }
            ?>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="event-video__item <?=($arItem['ID'] == $arParams['CURRENT_ID']) ? 'selected-slide' : ''?>"  style="background-image:url(<?=$preview ? $preview['src'] : $arParams['FOTO_PREVIEW']?>)">
                <div class="event-video__header">
                    <p class="event-video__type">дисциплина:</p>
                    <p><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                    <p class="event-video__date"><?=$arItem['PROPERTIES']['DATE_INTERVAL']['VALUE']?></p>
                </div>
                <span class="card__btn"></span>
                <div class="event-video__footer">
                    <p class="event-video__stage"></p>
                    <p class="event-video__name"><?=$arItem['NAME']?></p>
                </div>
            </a>
        <?php } ?>
    <?php } ?>
</div>