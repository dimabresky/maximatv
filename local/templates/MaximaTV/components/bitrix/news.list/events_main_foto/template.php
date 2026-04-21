<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
<div class="card-list">
    <div class="card-list__content">
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <?php
                if ($arItem['PREVIEW_PICTURE']) {
                    $preview = \CFile::ResizeImageGet(
                        $arItem['PREVIEW_PICTURE'],
                        ['width' => 352, 'height' => 241],
                        BX_RESIZE_IMAGE_EXACT
                    );
                } else {
                    $preview = [
                        'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'event.jpg'
                    ];
                }
            ?>

            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card card_shadow">
                <div class="card__row-top">
                    <span class="card__label"> <? if (!empty($arItem['PROPERTIES']['DATE_FROM']['VALUE']) && !empty($arItem['PROPERTIES']['DATE_TO']['VALUE'])): ?>
                            <?= $arItem['PROPERTIES']['DATE_FROM']['VALUE'] . ' - ' . $arItem['PROPERTIES']['DATE_TO']['VALUE'] ?>
                        <? endif ?></span>
                </div>
                <div  class="card__preview" style="background-image:url(<?=$preview['src']?>)"></div>
                <div class="card__content">
                    <div class="card__header">
                        <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                        <p class="card__stage"></p>
                    </div>
                    <p class="card__title"><?=$arItem['NAME']?></p>
                </div>
                <div class="card__footer">
                    <div class="card__theme card__theme_column">
                        <?php if (!empty($arItem['PROPERTIES']['DISCIPLINE']['VALUE'])) { ?>
                            <p class="card__theme-title">дисциплина:</p>
                            <p class="card__theme-name"><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                        <?php } ?>
                    </div>
                    <?php if (is_array($arItem['PROPERTIES']['FOTO']['VALUE'])): ?>
                        <div class="card__count card__count_photo"><?=count($arItem['PROPERTIES']['FOTO']['VALUE'])?></div>
                    <?php endif; ?>
                </div>
            </a>
        <?php } ?>
    </div>
    <div class="card-list__footer">
        <?/*a href="<?=$arResult['ITEMS'][0]['~LIST_PAGE_URL']?>" class="i-button">Все фото</a*/?>
    </div>
</div>
<?php } else { ?>
    <p>По вашему запросу ничего не найдено</p>
<?php } ?>
