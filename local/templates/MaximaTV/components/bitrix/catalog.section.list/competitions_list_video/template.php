<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) { ?>
    <?php foreach ($arResult['ITEMS'] as $arSectionId => $arSection) { ?>
        <div class="card-list js-card-list">
            <div class="card-list__info">
                <p class="card-list__info-title"><?=$arResult['SECTION_INFO'][$arSectionId]['NAME']?></p>
                <p class="card-list__info-date"><?=$arResult['SECTION_INFO'][$arSectionId]['DATE_INTERVAL']?></p>
            </div>
            <div class="card-list__content card-list__content_center">
                <?php $index = 0; ?>
                <?php foreach ($arSection as $item) { ?>
                    <?php
                    $index++;
                    $class = '';
                    if ($index > 6) {
                        $class = 'is-hidden';
                    }
                    switch ($item['UF_DISCIPLINE']) {
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
                    if ($item['PICTURE']['ID']) {
                        $preview = \CFile::ResizeImageGet(
                            $item['PICTURE']['ID'],
                            ['width' => 1096, 'height' => 685],
                            BX_RESIZE_IMAGE_EXACT
                        );
                    } else {
                        $preview = [
                            'src' => PREVIEW_IMAGES_DEFAULT_PATH . 'competition.' . $disciplineCode . '.jpg'
                        ];
                    }
                    ?>
                    <a href="<?=$item['SECTION_PAGE_URL']?>" class="card <?=$class?>">
                        <div class="card__row-top">
                            <span class="card__label"><?=$item['UF_DATE']?></span>
                        </div>
                        <div class="card__preview" style="background-image:url(<?=$preview['src']?>)">
                            <span class="card__btn"></span>
                        </div>
                        <div class="card__content">
                            <div class="card__header">
                                <p class="card__address"><?=$item['UF_PLACE']?></p>
                            </div>
                            <p class="card__title"><?=$item['NAME']?></p>
                        </div>
                        <div class="card__footer">
                            <div class="card__theme card__theme_column">
                                <p class="card__theme-title">дисциплина:</p>
                                <p class="card__theme-name"><?=$arResult['DISCIPLINES'][$item['UF_DISCIPLINE']]?></p>
                            </div>
                            <div class="card__count card__count_video"><?=$item['ELEMENT_CNT']?></div>
                        </div>
                    </a>
                <?php } ?>
            </div>
            <?php if ($index > 6) { ?>
                <div class="card-list__footer">
                    <a href="javascript:void(0)" class="card-list__more js-card-list-more"><span>ещё видео</span></a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <?/*p>По вашему запросу ничего не найдено</p*/?>
    <p>Здесь скоро будет видео-архив наших соревнований</p>
<?php } ?>
