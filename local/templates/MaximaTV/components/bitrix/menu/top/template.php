<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?php foreach ($arResult as $arItem) { ?>
    <li><a href="<?=$arItem['LINK']?>" class="menu__link"><?=$arItem['TEXT']?></a></li>
<?php } ?>
