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
<div class="g-wrap">
    <div class="lk-notification">
        <div class="lk__header">
            <h1 class="lk__title">Оплата трансляции</h1>
        </div>
        <div class="lk__header">
            <p><?=$arResult['MESSAGE']?></p>
        </div>
        <div class="lk-notification__footer">
            <a href="/lk/subscribe/" class="i-button">Назад</a>
        </div>
    </div>
</div>