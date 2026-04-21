<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) { ?>
<div class="card-slider">
    <div class="card-slider__list swiper-container js-cards-slider" data-name="photo">
        <div class="swiper-wrapper">
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <?php
                $preview = \CFile::ResizeImageGet(
                    $arItem['PREVIEW_PICTURE'],
                    ['width' => 538, 'height' => 308],
                    BX_RESIZE_IMAGE_EXACT
                );
            ?>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card card_shadow card_slider swiper-slide">
                <div class="card__row-top">
                    <span class="card__label"><?=$arItem['PROPERTIES']['DATE_INTERVAL']['VALUE']?></span>
                </div>
                <div class="card__preview" style="background-image:url(<?=$preview['src']?>)"></div>
                <div class="card__header">
                    <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                </div>
                <div class="card__footer">
                    <p class="card__title"><?=$arItem['NAME']?></p>
                    <?php if (is_array($arItem['PROPERTIES']['FOTO']['VALUE'])) { ?>
                        <div class="card__count card__count_photo"><?=count($arItem['PROPERTIES']['FOTO']['VALUE'])?></div>
                    <?php } ?>
                </div>
            </a>
        <?php } ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="card-slider__nav card-slider__nav_prev">
            <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M30 59L1 30L30 0.999999" fill="none" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"></path>
            </svg>
        </div>
        <div class="card-slider__nav card-slider__nav_next">
            <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.9,1L30,30L1.1,59" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
    </div>
    <div class="card-slider__footer">
        <?/*a href="<?=$arResult['ITEMS'][0]['~LIST_PAGE_URL']?>?foto=Y" class="i-button">Все фото</a*/?>
    </div>
</div>
<?php } else { ?>
    <p>По вашему запросу ничего не найдено</p>
<?php } ?>
