<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
    <div class="g-wrap">
        <div class="subscription">
            <h2 class="subscription__title">Подписка</h2>
            <p class="subscription__preview">Смотрите видео, сохраняйте лучшие моменты!<br>Чтобы получить доступ к просмотру материалов в высоком качестве&nbsp;- выберите один из вариантов подписки.</p>
            <div class="subscription__list js-subscription-slider swiper-container">
                <div class="swiper-wrapper">
                <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                    <div class="subscription__item <?=$arItem['PROPERTIES']['CSS_CLASS']['VALUE']?> swiper-slide">
                        <p class="subscription__label"></p>
                        <p class="subscription__name"><?=$arItem['NAME']?></p>
                        <?/*div class="subscription__price">
                            <?=$arItem['PREVIEW_TEXT']?>
                        </div*/?>
                        <div style="text-decoration: line-through; font-size: 25px; color: #666666;">
                            <?=$arItem['PROPERTIES']['OLD_PRICE']['VALUE'] ?: '' ?>
                        </div>
                        <div class="subscription-lk__price">
                            <div class="subscription-lk__price-wrap">
                                <?=$arItem['PROPERTIES']['LK_PRICE']['~VALUE']['TEXT']?>
                            </div>
                        </div>
                        <p class="subscription__text">&nbsp;</p>
                        <p class="subscription__text"><?=$arItem['DETAIL_TEXT']?></p>
                        <?php if ($arParams['IS_AUTH']) { ?>
                            <a href="/lk/subscribe/<?//=$arItem['DETAIL_PAGE_URL']?>" class="subscription__btn i-button"><?=$arItem['PROPERTIES']['BUY_BUTTON_TEXT']['VALUE']?></a>
                        <?php } else { ?>
                            <a href="javascript:void(0);" class="subscription__btn i-button" data-remodal-target="login_register"><?=$arItem['PROPERTIES']['BUY_BUTTON_TEXT']['VALUE']?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
<?php } ?>