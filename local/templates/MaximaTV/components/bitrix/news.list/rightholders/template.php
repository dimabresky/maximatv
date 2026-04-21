<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
    <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <div class="text-page__block">
            <?php if (mb_substr($arItem['NAME'], 0, 1) !== '!') { ?>
                <h4 class="text-page__block-title"><?=$arItem['NAME']?></h4>
            <?php } ?>
            <?=$arItem['~DETAIL_TEXT']?>
        </div>
    <?php } ?>
<?php } ?>
