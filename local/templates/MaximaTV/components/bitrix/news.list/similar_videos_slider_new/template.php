<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
    <section class="i-section event__others">
        <div class="g-wrap">
            <h2>Другие события</h2>
            <div class="card-list">
                <div class="card-list__content">
                    <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                        <?php
                        if ($arItem['PREVIEW_PICTURE']) {
                            $preview = \CFile::ResizeImageGet(
                                $arItem['PREVIEW_PICTURE'],
                                ['width' => 315, 'height' => 196],
                                BX_RESIZE_IMAGE_EXACT
                            );
                        } else {
                            $preview = [
                                'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'event.jpg'
                            ];
                        }
                        ?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card"  style="background-image:url()">
                            <div class="card__row-top">
                                <span class="card__label"><?=$arItem['PROPERTIES']['DATE_INTERVAL']['VALUE']?></span>
                            </div>
                            <div class="card__preview" style="background-image:url(<?=$arParams['FOTO_PREVIEW'] ? $arParams['FOTO_PREVIEW'] : $preview['src']?>)">
                                <span class="card__btn"></span>
                            </div>
                            <div class="card__content">
                                <div class="card__header">
                                    <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                                    <p class="card__stage"></p>
                                </div>
                                <p class="card__title"><?=$arItem['NAME']?></p>
                            </div>
                            <div class="card__footer">
                                <div class="card__theme card__theme_column">
                                    <p class="card__theme-title">дисциплина:</p>
                                    <p class="card__theme-name"><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
<?php } ?>