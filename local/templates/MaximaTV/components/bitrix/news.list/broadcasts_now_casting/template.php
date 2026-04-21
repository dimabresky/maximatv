<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$additionalClass = '';
switch (count($arResult['ITEMS'])) {
    case 1:
        $dimensions = ['width' => 1096, 'height' => 386];
        break;
    case 2:
        $dimensions = ['width' => 538, 'height' => 336];
        $additionalClass = 'card_half';
        break;
    default:
        $dimensions = ['width' => 352, 'height' => 220];
        break;
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
<section class="i-section event__others event__others_dark">
    <div class="g-wrap">
        <h2>Сейчас в прямом эфире</h2>
        <div class="i-section__content card-list">
            <div class="card-list__content">
                <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                    <?php
                        $preview = \CFile::ResizeImageGet(
                            $arItem['PREVIEW_PICTURE'],
                            $dimensions,
                            BX_RESIZE_IMAGE_EXACT
                        );
                        $liveFrom = $arItem['PROPERTIES']['LIVE_FROM']['VALUE'];
                        $liveTo = $arItem['PROPERTIES']['LIVE_TO']['VALUE'];
                        $isLive = false;
                        if ($liveFrom !== '' && $liveTo !== '') {
                            $liveFrom = strtotime($liveFrom);
                            $liveTo = strtotime($liveTo);
                            $now = time();
                            $isLive = $now >= $liveFrom && $now <= $liveTo;
                        }
                    ?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card <?=$additionalClass?>">
                        <div class="card__row-top">
                            <?php if ($isLive) { ?>
                                <span class="card__label card__label_live">live</span>
                            <?php } ?>
                        </div>
                        <div class="card__preview" style="background-image:url(<?=$preview['src']?>)">
                            <span class="card__btn"></span>
                        </div>
                        <div class="card__content">
                            <div class="card__header">
                                <p class="card__address"><?=$arItem['PROPERTIES']['PLACE']['VALUE']?></p>
                            </div>
                            <p class="card__title"><?=$arItem['NAME']?></p>
                        </div>
                        <div class="card__footer">
                            <div class="card__theme">
                                <p class="card__theme-title">дисциплина:</p>
                                <p class="card__theme-name"><?=$arItem['PROPERTIES']['DISCIPLINE']['VALUE']?></p>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
            <?php if ($arParams['SHOW_ALL_BTN'] === 'Y') { ?>
                <div class="card-list__footer">
                    <a href="<?=$arResult['ITEMS'][0]['~LIST_PAGE_URL']?>" class="i-button">Все трансляции</a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>
