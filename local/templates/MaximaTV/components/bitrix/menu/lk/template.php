<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?php if (count($arResult) > 0 && $USER->IsAuthorized()) { ?>
    <?php
        $arIcons = [
            'Подписка' => 'lk-nav__icon_subscription',
            'Мои заезды' => 'lk-nav__icon_rides',
            'Избранное' => 'lk-nav__icon_favorite',
            'Покупки' => 'lk-nav__icon_history',
            'Профиль' => 'lk-nav__icon_profile',
            'Выход' => 'lk-nav__icon_quite',
        ];
    ?>
    <aside class="lk__aside">
        <div class="lk-nav js-lk-nav swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($arResult as $arItem) { ?>
                    <a href="<?=$arItem['LINK']?>" class="lk-nav__link swiper-slide <?=$arItem['SELECTED'] ? 'lk-nav__link_active' : ''?>">
                        <span class="lk-nav__icon <?=$arIcons[$arItem['TEXT']]?>"></span>
                        <span class="lk-nav__text"><?=$arItem['TEXT']?></span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </aside>
<?php } ?>
