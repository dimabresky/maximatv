<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
<div class="text-page__block">
    <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <div class="text-page__accordion js-accordion">
            <div class="text-page__accordion-header js-accordion-trigger">
                <p><?=$arItem['NAME']?></p>
                <span class="text-page__accordion-icon">
                    <svg class="i-svg" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.2656 1.02344C17.3438 1.14062 17.3828 1.25781 17.3828 1.375C17.3828 1.53125 17.3438 1.60938 17.2656 1.6875L9.10156 9.89062C8.98438 9.96875 8.86719 10.0078 8.75 10.0078C8.59375 10.0078 8.51562 9.96875 8.4375 9.89062L0.234375 1.6875C0.15625 1.60938 0.117188 1.53125 0.117188 1.375C0.117188 1.25781 0.15625 1.14062 0.234375 1.02344L1.01562 0.242188C1.09375 0.164062 1.17188 0.125 1.32812 0.125C1.44531 0.125 1.5625 0.164062 1.67969 0.242188L8.75 7.3125L15.8203 0.242188C15.8984 0.164062 16.0156 0.125 16.1719 0.125C16.2891 0.125 16.4062 0.164062 16.4844 0.242188L17.2656 1.02344Z"></path>
                    </svg>
                </span>
            </div>
            <div class="text-page__accordion-body js-accordion-content">
                <div class="text-page__accordion-content">
                    <?=$arItem['~DETAIL_TEXT']?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>
