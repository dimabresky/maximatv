<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<div class="card-list">
    <div class="card-list__content">
        <?php if (count($arResult['ITEMS']) > 0) {?>
            <?php $index = 0; ?>
            <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                <?php
                    $index++;
                    $class = '';
                    if ($index > 6) {
                        $class = 'is-hidden';
                    }
                    $preview = \CFile::ResizeImageGet(
                        $arItem['PREVIEW_PICTURE'],
                        ['width' => 352, 'height' => 199],
                        BX_RESIZE_IMAGE_EXACT
                    );
                ?>
                <div class="card <?=$class?>">
                    <div class="card__row-top">
                        <span class="card__label"><?=$arItem['PROPERTIES']['DATE_INTERVAL']['VALUE']?></span>
                    </div>
                    <div class="card__preview">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card__preview-link"></a>
                        <img src="<?=$preview['src']?>" class="card__img" alt=""/>
                        <span class="card__btn"></span>
                    </div>
                    <div class="card__content">
                        <div class="card__header">
                            <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                            <p class="card__stage"><?/*2 этап*/?></p>
                        </div>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card__title"><?=$arItem['NAME']?></a>
                    </div>
                    <div class="card__footer">
                        <div class="card__theme card__theme_column">
                            <p class="card__theme-title">дисциплина:</p>
                            <p class="card__theme-name"><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                        </div>
                        <?/*div class="card__count card__count_video">5</div*/?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <?php if ($index > 6) { ?>
        <div class="card-list__footer">
            <a href="javascript:void(0)" class="i-link i-link_upload"><span>ещё видео</span></a>
        </div>
    <?php } ?>
</div>