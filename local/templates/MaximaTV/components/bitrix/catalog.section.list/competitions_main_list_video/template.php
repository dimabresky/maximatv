<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) { ?>
    <div class="card-slider">
        <div class="card-slider__list swiper-container js-cards-slider" data-name="video">
            <div class="swiper-wrapper">
                <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                    <?php
                        switch ($arItem['UF_DISCIPLINE']) {
                            case '1':
                                $disciplineCode = 'dressage';
                                break;
                            case '2':
                                $disciplineCode = 'jumping';
                                break;
                            default:
                                $disciplineCode = 'dressage';
                                break;
                        }
                        if ($arItem['PICTURE']['ID']) {
                            $preview = \CFile::ResizeImageGet(
                                $arItem['PICTURE']['ID'],
                                ['width' => 352, 'height' => 220],
                                BX_RESIZE_IMAGE_EXACT
                            );
                        } else {
                            $preview = [
                                'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg'
                            ];
                        }
                    ?>
                    <a href="<?=$arItem['SECTION_PAGE_URL']?>" class="card card_slider swiper-slide">
                        <div class="card__row-top">
                            <div class="card__theme">
                                <p class="card__theme-title">дисциплина:</p>
                                <p class="card__theme-name"><?=$arResult['DISCIPLINES'][$arItem['UF_DISCIPLINE']]?></p>
                            </div>
                            <p class="card__date"><?=$arItem['UF_DATE']?></p>
                        </div>
                        <div class="card__preview" style="background-image:url(<?=$preview['src']?>)">
                            <span class="card__btn"></span>
                        </div>
                        <div class="card__header">
                            <p class="card__address"><?=$arItem['UF_PLACE']?></p>
                        </div>
                        <div class="card__footer">
                            <p class="card__title"><?=$arItem['NAME']?></p>
                            <div class="card__count card__count_video"><?=$arItem['ELEMENT_CNT']?></div>
                        </div>
                    </a>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="card-slider__nav card-slider__nav_prev">
                <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M30 59L1 30L30 0.999999" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <div class="card-slider__nav card-slider__nav_next">
                <svg class="i-svg" width="31" height="60" viewBox="0 0 31 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.9,1L30,30L1.1,59" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>
        <div class="card-slider__footer">
            <a href="<?=$arResult['SECTIONS'][0]['~LIST_PAGE_URL']?>" class="i-button">Все видео</a>
        </div>
    </div>
<?php } else { ?>
    <p>По вашему запросу ничего не найдено</p>
<?php } ?>