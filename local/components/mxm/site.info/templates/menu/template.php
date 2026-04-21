<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<div class="menu__phone">
	<a href="tel:<?=$arResult['GENERAL_PHONE']['FORMATTED']?>"><?=$arResult['GENERAL_PHONE']['VALUE']?></a>
</div>
<div class="menu__email">
	<a href="mailto:<?=$arResult['EMAIL']['VALUE']?>" class="menu__email-link"><?=$arResult['EMAIL']['VALUE']?></a>
</div>
