<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
<div class="i-section__content program-list">
    <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <?php
            $preview = \CFile::ResizeImageGet(
                $arItem['PREVIEW_PICTURE'],
                ['width' => 315, 'height' => 213],
                BX_RESIZE_IMAGE_EXACT
            );
        ?>
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="program">
            <div class="program__img" style="background-image:url(<?=$preview['src']?>)"></div>
            <div class="program__content">
                <div class="program__header">
                    <p class="program__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                    <p class="program__date"><?=$arItem['ACTIVE_FROM']?> - <?=$arItem['ACTIVE_TO']?></p>
                </div>
                <p class="program__title"><?=$arItem['NAME']?></p>
                <div class="program__footer">
                    <div class="program__info">
                        <p class="program__info-title">дисциплина:</p>
                        <p><?= implode(' / ', $arItem['PROPERTIES']['DISCIPLINE']['VALUE'])?></p>
                    </div>
                </div>
            </div>
        </a>
    <?php } ?>
</div>
<?php } else { ?>
    <p>Программ по Вашему фильтру не найдено.</p>
<?php } ?>
