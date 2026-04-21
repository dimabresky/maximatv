<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);

//echo '<pre>';
//var_dump($arResult);
?>
<div class="g-wrap">
    <div class="photo__header">
        <a href="<?=$arResult['SECTION_PAGE_URL']?>" class="photo__back"><span>назад</span></a>
        <div class="photo__header-row">
            <div class="photo__header-col">
                <h1 class="photo__title"><?=$arResult['NAME']?></h1>
                <p class="photo__stage"><?/*1 этап*/?></p>
            </div>
            <div class="photo__header-col">
                <p class="photo__date"><?=$arResult['UF_DATE_INTERVAL']?></p>
            </div>
        </div>
        <div class="photo__header-row">
            <div class="photo__header-col">
                <p class="photo__address"><?=$arResult['UF_PLACE']?></p>
                <p class="photo__type">дисциплина:</p><p><?=$arResult['DISCIPLINES'][$arResult['UF_DISCIPLINE']]?></p>
            </div>
            <div class="photo__header-col">
                <?/*p class="photo__count"><span><?=count($arResult['UF_FOTO'])?> фото</span></p*/?>
            </div>
        </div>
    </div>

    <div class="photo__content">
        <div class="photo-list js-photo-list">
            <?php $index = 0; ?>
            <?php foreach ($arResult['UF_FOTO'] as $fotoId) { ?>
                <?php
                $originalFoto = CFile::GetByID($fotoId)->Fetch();
                $originalFoto['src'] = CFile::GetPath($fotoId);
                $class = '';
                $index++;
                if ($index > 5) {
                    $class = 'hidden-photo ';
                }

                switch ($index % 10) {
                    case 7:
                    case 1:
                        $class .= 'photo-list__item_big';
                        $foto = \CFile::ResizeImageGet(
                            $fotoId,
                            ['width' => 650, 'height' => 396],
                            BX_RESIZE_IMAGE_EXACT
                        );
                        break;
                    case 2:
                    case 6:
                        $class .= 'photo-list__item_middle';
                        $foto = \CFile::ResizeImageGet(
                            $fotoId,
                            ['width' => 426, 'height' => 396],
                            BX_RESIZE_IMAGE_EXACT
                        );
                        break;
                    default:
                        $foto = \CFile::ResizeImageGet(
                            $fotoId,
                            ['width' => 352, 'height' => 396],
                            BX_RESIZE_IMAGE_EXACT
                        );
                        break;
                }
                ?>
                <a href="<?=$originalFoto['src']?>" data-lightbox="eventMedia" data-title="<?=$originalFoto['DESCRIPTION']?>" class="photo-list__item <?=$class?>"><img src="<?=$foto['src']?>"/></a>
            <?php } ?>
        </div>
    </div>
    <div class="photo__footer">
        <a href="javascript:void(0);" class="i-button js-show_more">Показать ещё</a>
    </div>
</div>
