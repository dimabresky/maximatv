<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
<?php $index = 0; ?>
<div class="card-list">
    <div class="card-list__content">
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <?php
                $index++;
                if ($index === 4) {
                    $dimensions = ['width' => 724, 'height' => 420];
                } elseif ($index === 5) {
                    $dimensions = ['width' => 352, 'height' => 420];
                } else {
                    $dimensions = ['width' => 352, 'height' => 220];
                }
                if ($arItem['PREVIEW_PICTURE']) {
                    $preview = \CFile::ResizeImageGet(
                        $arItem['PREVIEW_PICTURE'],
                        $dimensions,
                        BX_RESIZE_IMAGE_EXACT
                    );
                } else {
                    $preview = [
                        'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'event.jpg'
                    ];
                }
            ?>
            <?php
                if ($index === 4) {
                    ?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card card_wide card_light card_light--big">
                        <div class="card__row-top">
                            <div class="card__theme">
                                <?php if (!empty($arItem['PROPERTIES']['DISCIPLINE']['VALUE'])) { ?>
                                    <p class="card__theme-title">дисциплина:</p>
                                    <p class="card__theme-name"><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                                <?php } ?>
                            </div>
                            <p class="card__date">
                                <? if (!empty($arItem['PROPERTIES']['DATE_FROM']['VALUE']) && !empty($arItem['PROPERTIES']['DATE_TO']['VALUE'])): ?>
                                    <?= $arItem['PROPERTIES']['DATE_FROM']['VALUE'] . ' - ' . $arItem['PROPERTIES']['DATE_TO']['VALUE'] ?>
                                <? endif ?>
                            </p>
                        </div>
                        <div class="card__preview" style="background-image:url(<?=$preview['src']?>)"></div>
                        <span class="card__btn card__btn_big"></span>
                        <div class="card__footer">
                            <div class="card__footer-col">
                                <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                                <div class="card__title"><?=$arItem['NAME']?></div>
                            </div>
                            <?/*div class="card__count card__count_video">10</div*/?>
                        </div>
                    </a>
                    <?php
                } elseif ($index === 5) {
                    ?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card card_light card_light--big">
                        <div class="card__row-top">
                            <div class="card__theme">
                                <?php if (!empty($arItem['PROPERTIES']['DISCIPLINE']['VALUE'])) { ?>
                                    <p class="card__theme-title">дисциплина:</p>
                                    <p class="card__theme-name"><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                                <?php } ?>
                            </div>
                            <p class="card__date">
                                <? if (!empty($arItem['PROPERTIES']['DATE_FROM']['VALUE']) && !empty($arItem['PROPERTIES']['DATE_TO']['VALUE'])): ?>
                                    <?= $arItem['PROPERTIES']['DATE_FROM']['VALUE'] . ' - ' . $arItem['PROPERTIES']['DATE_TO']['VALUE'] ?>
                                <? endif ?>
                            </p>
                        </div>
                        <div class="card__preview" style="background-image:url(<?=$preview['src']?>)"></div>
                        <span class="card__btn card__btn_big"></span>
                        <div class="card__footer">
                            <div class="card__footer-col">
                                <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                                <div class="card__title"><?=$arItem['NAME']?></div>
                            </div>
                            <?/*div class="card__count card__count_video">10</div*/?>
                        </div>
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card">
                        <div class="card__row-top">
                            <span class="card__label">
                                <? if (!empty($arItem['PROPERTIES']['DATE_FROM']['VALUE']) && !empty($arItem['PROPERTIES']['DATE_TO']['VALUE'])): ?>
                                    <?= $arItem['PROPERTIES']['DATE_FROM']['VALUE'] . ' - ' . $arItem['PROPERTIES']['DATE_TO']['VALUE'] ?>
                                <? endif ?>
                            </span>
                        </div>
                        <div  class="card__preview" style="background-image:url(<?=$preview['src']?>)">
                            <span class="card__btn"></span>
                        </div>
                        <div class="card__content">
                            <div class="card__header">
                                <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
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
                            <?/*div class="card__count card__count_video">10</div*/?>
                        </div>
                    </a>
                    <?php
                }
            ?>
        <?php } ?>
    </div>
    <div class="card-list__footer">
        <a href="<?=$arResult['ITEMS'][0]['~LIST_PAGE_URL']?>" class="i-button">Все видео</a>
    </div>
</div>
<?php } else { ?>
    <?/*p>По вашему запросу ничего не найдено</p*/?>
    <p>Здесь скоро будет видео-архив наших событий</p>
<?php } ?>
