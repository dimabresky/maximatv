<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */

//delayed function must return a string
if (empty($arResult)) {
	return "";
}
unset($arResult[ count($arResult) - 1 ][ "LINK" ]);
ob_start();
?>

	<div class="breadcrumbs">
		<div class="breadcrumbs__list">
			<? foreach ($arResult as $arItem): ?>
				<? if ($arItem[ 'LINK' ]): ?>
					<a class="breadcrumbs__item"
					   href="<?= $arItem[ 'LINK' ] ?>"><?= htmlspecialcharsEx($arItem[ "TITLE" ]) ?></a>
				<? else: ?>
					<a class="breadcrumbs__item"
					   href="javascript:void(0)"><?= htmlspecialcharsEx($arItem[ "TITLE" ]) ?></a>
				<? endif; ?>
			<? endforeach ?>
		</div>
	</div>

<?
return ob_get_clean();
