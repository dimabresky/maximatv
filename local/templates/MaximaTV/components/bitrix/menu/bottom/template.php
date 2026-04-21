<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<nav class="menu__nav-list">
    <?php foreach ($arResult as $arItem) { ?>
        <div class="menu__nav-item"><a href="<?=$arItem['LINK']?>" class="menu__nav-link"><?=$arItem['TEXT']?></a></div>
    <?php } ?>
</nav>