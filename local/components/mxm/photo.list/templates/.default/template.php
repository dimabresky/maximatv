<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? global $USER; ?>
<? if(!empty($arResult['PHOTO_LIST'])): ?>
<? if(!$arParams['SHOW_MORE']): ?>
<div class="g-wrap">
        <div class="photo__header" style="margin-bottom: 32px;">
            <div class="photo__header-row">
                <div class="photo__header-col">
                    <? if(!empty($arResult['ITEM']['NAME'])): ?>
                        <h1 class="photo__title"><?= $arResult['ITEM']['NAME'] ?></h1>
                    <? endif ?>
                </div>
                <?  if (!empty($arResult['ITEM']['DATE'])): ?>
                    <div class="photo__header-col">
                        <p class="photo__date"><?= $arResult['ITEM']['DATE'] ?></p>
                    </div>
                <? endif ?>
            </div>
            <div class="photo__header-row">
                <div class="photo__header-col">
                    <? if(!empty($arResult['ITEM']['PLACE'])): ?>
                        <p class="photo__address"><?= $arResult['ITEM']['PLACE'] ?></p>
                    <? endif ?>
                    <? if(!empty($arResult['ITEM']['DISCIPLINE'])): ?>
                        <p class="photo__type">дисциплина:</p><p><?= $arResult['ITEM']['DISCIPLINE'] ?></p>
                    <? endif ?>
                </div>
                <? if(!empty( $arResult['ALL_COUNT_PHOTO'])): ?>
                    <div class="photo__header-col">
                        <p class="photo__count"><span><?= $arResult['ALL_COUNT_PHOTO'] ?> фото</span></p>
                    </div>
                <? endif ?>
            </div>
        </div>
        <?php if (is_array($arResult['OTHERS']) && count($arResult['OTHERS']) > 0) { ?>
            <section class="i-section i-section_slider">
                <div class="i-section__content">
                    <div class="i-section__header">
                        <h2>Выберите маршрут</h2>
                    </div>
                    <div class="card-slider">
                        <div class="card-slider__list swiper-container js-cards-slider" data-name="video">
                            <div class="swiper-wrapper">
                                <?php foreach ($arResult['OTHERS'] as $arPhoto) { ?>
                                    <?php
                                        $preview = CFile::ResizeImageGet(
                                            $arPhoto['RESIZE_ARRAY'],
                                            ['width' => 538, 'height' => 396],
                                            BX_RESIZE_IMAGE_EXACT,
                                            false,
                                            $arPhoto['FILTER']['small']
                                        );
                                    ?>
                                    <a href="<?=$arResult['CUR_DIR'] . '?ID=' . $arPhoto['INDEX']?>" class="card card_shadow card_slider swiper-slide">
                                        <div class="card__row-top"></div>
                                        <div class="card__preview" style="background-image:url('<?=$preview['src']?>')"></div>
                                        <div class="card__header"></div>
                                        <div class="card__footer">
                                            <p class="card__title"><?=$arPhoto['NAME']?></p>
                                            <div class="card__count card__count_photo"><?=$arPhoto['COUNT']?></div>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 4"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 5"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 6"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 7"></span></div>
                            <div class="card-slider__nav card-slider__nav_prev" tabindex="0" role="button" aria-label="Previous slide">
                                <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30 59L1 30L30 0.999999" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="card-slider__nav card-slider__nav_next" tabindex="0" role="button" aria-label="Next slide">
                                <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.9,1L30,30L1.1,59" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
        <div class="photo__content js-photo-list" data-type="<?= $arParams['TYPE'] ?>" data-id="<?= $arParams['ID'] ?>" data-offset="500">
        <div class="photo-list" >
<? endif ?>
            <?php $index = 0; ?>
            <?php foreach ($arResult['PHOTO_LIST'] as $arPhoto) { ?>
                <?
                    $class = '';
                    $index++;
                    switch ($index % 7) {
                        case 0:
                        case 1:
                            $class = 'photo-list__item_big';
                            $arSize = ['width' => 1300, 'height' => 792];
                            break;
                        case 2:
                        case 6:
                            $class = 'photo-list__item_middle';
                            $arSize = ['width' => 852, 'height' => 792];
                            break;
                        default:
                            $arSize = ['width' => 704, 'height' => 792];
                            break;
                    };

                $prev = CFile::ResizeImageGet(
                        $arPhoto['RESIZE_ARRAY'],
                        $arSize,
                        BX_RESIZE_IMAGE_PROPORTIONAL,
                        false,
                        $arPhoto['FILTER']['small']
                );

                $origin =    CFile::ResizeImageGet(
                    $arPhoto['RESIZE_ARRAY'],
                    ['width' => 2000, 'height' => 2000],
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    false,
                    $arPhoto['FILTER']['big']
                );
                ?>
                <a href="<?= $origin['src'] ?>" data-id="<?=  $USER->IsAuthorized() ? $arParams['TYPE'] . '_' . $arParams['ID'] . '_' .  $arPhoto['INDEX'] : '-1'; ?>" data-fancybox="gallery" data-caption="" data-basket="<?= $arPhoto['IN_BASKET'] ? 1 : 0;  ?>" class="photo-list__item <?= $class ?> js-gallery" style="background-image:url(<?= $prev['src'] ?>)"></a>
            <?php } ?>

            <? if(empty($arResult['NEXT_PAGE'])): ?>
                <div class="hidden-photo js-end-photo-list"></div>
            <? endif  ?>
<? if(!$arParams['SHOW_MORE']): ?>
        </div>
        <div class="photo-list js-photo-wrap">
            <div class="photo-list__preloader"></div>
        </div>
    </div>

</div>
   <script type="text/javascript">

       var MaximaTvGalleryTpl  = '<div class="fancybox-container" role="dialog" tabindex="-1">' +
           '<div class="fancybox-bg"></div>' +
           '<div class="fancybox-inner">' +
           '<div class="fancybox-infobar"><span data-fancybox-index></span>&nbsp;/&nbsp;<span data-fancybox-count></span></div>' +
           '<div class="fancybox-toolbar">{{buttons}}</div>' +
           '<div class="fancybox-navigation">{{arrows}}</div>' +
           '<div class="fancybox-stage">' +
           '<div class="fancybox-message"><?
               if(!$USER->IsAuthorized()):
                   echo '<p>Покупка фотографий доступна только для зарегистрированных пользователей. Авторизуйтесь, чтобы получить доступ.</p>';
               endif;
               ?></div>' +
           '<a class="fancybox-cart i-button js-gallery-cart" <? if(!$USER->IsAuthorized()): ?> data-remodal-target="login_register" <? endif ?>><?=
               $USER->IsAuthorized() ? 'Добавить в корзину' : 'Aвторизоваться' ?> </a>' +
           '</div>' +
           '</div></div>';
   </script>
<? endif ?>
<? endif; ?>

