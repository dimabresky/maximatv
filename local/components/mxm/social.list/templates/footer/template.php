<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?if(!empty($arResult)):?>
<p class="social__title">следите за нами</p>
<ul class="social__list">
<?php foreach ($arResult as $arSocial) { ?>
    <?php if ($arSocial['VALUE'] == '') { continue; }?>
	<li class="social__item">
		<a href="<?=$arSocial['VALUE']?>" class="social__link">
			<?=$arSocial['SVG']?>
		</a>
	</li>
<?php } ?>
</ul>
<?endif;?>