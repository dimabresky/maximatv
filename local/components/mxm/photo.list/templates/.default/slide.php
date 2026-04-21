<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($arResult['PHOTO_LIST'])): ?>

<section class="i-section i-section_bg-dark event__photo">
    <div class="g-wrap">
        <div class="i-section__header">
            <h2><?= $arParams['SECTION_TITLE'] ?></h2>
            <a href="<?= $APPLICATION->GetCurPage() ?>photo/<?=(!empty($_GET['ID'])) ? '?ID=' . $_GET['ID'] : '' ?>" class="event__photo-link">все фото</a>
        </div>
        <div class="i-section__content">
            <div class="photo-slider">
                <div class="photo-slider__wrap swiper-container js-photo-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($arResult['PHOTO_LIST'] as $arPhoto) { ?>
                            <?
                                $prev = CFile::ResizeImageGet(
                                    $arPhoto['RESIZE_ARRAY'],
                                    ['width' => 538, 'height' => 396],
                                    BX_RESIZE_IMAGE_EXACT,
                                    false,
                                    $arPhoto['FILTER']['small']
                                );
                            ?>
                            <div class="photo-slider__item swiper-slide"><img src="<?= $prev['src'] ?>" alt=""/></div>
                        <?php } ?>
                    </div>
                    <div class="photo-slider__nav photo-slider__nav_prev">
                        <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30 59L1 30L30 0.999999" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="photo-slider__nav photo-slider__nav_next">
                        <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.9,1L30,30L1.1,59" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<? endif; ?>
