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
                    $preview = \CFile::ResizeImageGet(
                        $item['PICTURE']['ID'],
                        ['width' => 352, 'height' => 220],
                        BX_RESIZE_IMAGE_EXACT
                    );
                    ?>
                    <a href="<?=$item['SECTION_PAGE_URL']?>" class="card <?=$class?>">
                        <div class="card__row-top">
                            <span class="card__label"><?=$item['UF_DATE_INTERVAL']?></span>
                        </div>
                        <div class="card__preview">
                            <img src="<?=$preview['src']?>" class="card__img" alt=""/>
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
    <p>По вашему запросу ничего не найдено</p>
<?php } ?>
