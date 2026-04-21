<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="program-detail">
    <div class="program-detail__header" style="background-image:url(<?=$arResult['PREVIEW_PICTURE']['SRC']?>)">
        <div class="g-wrap">
            <a href="javascript:history.back()" class="program-detail__back"><span>назад</span></a>
            <h1 class="program-detail__title"><?=$arResult['NAME']?></h1>
            <p class="program-detail__text"><?=$arResult['DETAIL_TEXT']?></p>
            <div class="program-detail__desc">
                <p class="program-detail__address"><?=$arResult['PROPERTIES']['PLACE']['VALUE']?></p>
                <p class="program-detail__info">
                    <span class="program-detail__info-title">дисциплина:</span>
                    <span><?=implode(' / ', $arResult['PROPERTIES']['DISCIPLINE']['VALUE'])?></span>
                </p>
                <p class="program-detail__date"><?=$arResult['ACTIVE_FROM']?> - <?=$arResult['ACTIVE_TO']?></p>
            </div>
        </div>
    </div>
    <div class="program-detail__content">
        <div class="g-wrap">
            <h2 class="">Программа</h2>
            <?php foreach ($arResult['ITEMS'] as $item) { ?>
                <div class="program-short">
                    <p class="program-short__date"><?=$item['PROPERTY_DATE_INTERVAL_VALUE']?></p>
                    <div class="program-short__col">
                        <p class="program-short__name"><?=$item['NAME']?></p>
                    </div>
                    <div class="program-short__col">
                        <?php if ($item['PROPERTY_PATH_TO_VIDEO_VALUE']) { ?>
                            <a href="<?=$item['DETAIL_PAGE_URL']?>" class="program-short__link program-short__link_video"><span>Видео</span></a>
                        <?php } else { ?>
                            <p class="program-short__info">Результаты и&nbsp;видео будут доступны&nbsp;в&nbsp;день
                                соревнований</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php foreach($arResult['EVENT_LIST'] as $item){ ?>
                <div class="program-short">
                    <p class="program-short__date"><?
                            if(!empty($item['PROPERTY_DATE_FROM_VALUE']) && !empty($item['PROPERTY_DATE_TO_VALUE'])){
                                echo $item['PROPERTY_DATE_FROM_VALUE'] . ' - ' . $item['PROPERTY_DATE_TO_VALUE'];
                            }
                        ?></p>
                    <div class="program-short__col">
                        <p class="program-short__name"><?=$item['NAME']?></p>
                    </div>
                    <div class="program-short__col">
                        <?php if ($item['PROPERTY_VIDEO_FILE_VALUE']) { ?>
                            <a href="<?=$item['DETAIL_PAGE_URL']?>" class="program-short__link program-short__link_video"><span>Видео</span></a>
                        <?php } else { ?>
                            <p class="program-short__info">Результаты и&nbsp;видео будут доступны&nbsp;в&nbsp;день
                                соревнований</p>
                        <?php } ?>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
</div>

