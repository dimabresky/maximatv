<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
    <section class="i-section i-section_banner">
        <div class="g-wrap">
            <div class="banner js-banner">
                <div class="banner__slider js-banner-slider" data-autoplay-speed="5000">
                    <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                        <?php
                            $preview = \CFile::ResizeImageGet(
                                $arItem['PREVIEW_PICTURE'],
                                ['width' => 1096, 'height' => 600],
                                BX_RESIZE_IMAGE_EXACT
                            );
                            $liveFrom = $arItem['PROPERTIES']['LIVE_FROM']['VALUE'];
                            $liveTo = $arItem['PROPERTIES']['LIVE_TO']['VALUE'];
                            $isLive = false;
                            $bannerDate = '';
                            if ($liveFrom !== '' && $liveTo !== '') {
                                $liveFrom = strtotime($liveFrom);
                                $liveTo = strtotime($liveTo);
                                $now = time();
                                $isLive = $now >= $liveFrom && $now <= $liveTo;
                                $bannerDate = explode(' ', $arItem['PROPERTIES']['LIVE_FROM']['VALUE'])[0];

                                $tranlationTime = $now - $liveFrom;
                                $tranlationHours = sprintf("%02d", (int) ($tranlationTime / 3600));
                                $tranlationMinutes = sprintf("%02d", (int) (($tranlationTime - $tranlationHours * 3600) / 60));
                            }
                        ?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="banner__item">
                            <div class="banner__content" style="background-image:url(<?=$preview['src']?>)">
                                <div class="banner__header">
                                    <?php if ($isLive) { ?>
                                        <span class="banner__label banner__label_live">live</span>
                                        <div class="banner__info js-timer">
                                            <span class="banner__info-title">идет уже</span>&nbsp;<span class="js-timer-hours"><?=$tranlationHours?></span>&nbsp;ч&nbsp;:&nbsp;<span class="js-timer-minutes"><?=$tranlationMinutes?></span>&nbsp;мин
                                        </div>
                                    <?php } else { ?>
                                        <span class="banner__label banner__label_live"></span>
                                        <div class="banner__info">
                                            <span class="banner__info-title">начало в <?=date('H:i', $liveFrom)?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <h1 class="banner__title"><?=$arItem['NAME']?></h1>
                                <p class="banner__text"><?=$arItem['PREVIEW_TEXT']?></p>
                            </div>
                            <span class="banner__btn"></span>
                            <div class="banner__footer">
                                <p class="banner__date"><?=$bannerDate?></p>
                            </div>
                        </a>
                    <?php } ?>
                </div>
                <div class="banner__counter js-banner-counter">
                    <span class="banner__counter-number">01</span>
                    <div class="banner__counter-line js-runner-line">
                        <span class="banner__counter-runner js-runner"></span>
                    </div>
                    <span class="banner__counter-number"><?=sprintf("%02d", count($arResult['ITEMS']))?></span>
                </div>
            </div>
        </div>
    </section>
<?php } ?>