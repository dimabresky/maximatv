<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="header__lk header__lk_auth js-auth-header">
    <a href="javascript:void(0);" class="header__lk-link js-auth-link">
        <img src="<?=$arResult['USER']['PHOTO']?>" class="header__lk-img" alt="<?=$arResult['USER']['NAME']?>"/>
        <span><?=$arResult['USER']['NAME']?></span></a>
    <div class="header__lk-menu lk-menu">
        <a href="#" class="lk-menu__close js-menu-close"></a>
        <ul class="lk-menu__list">
            <li class="lk-menu__item"><a href="/lk/subscribe/" class="lk-menu__link">Подписка</a></li>
            <li class="lk-menu__item"><a href="/lk/history/" class="lk-menu__link">Покупки</a></li>
            <li class="lk-menu__item"><a href="/lk/" class="lk-menu__link">Профиль</a></li>
            <?/*li class="lk-menu__item">
                <a href="/lk/" class="lk-menu__link">Уведомления<span class="lk-menu__info">25</span></a>
            </li*/?>
        </ul>
        <div class="lk-menu__footer">
            <a href="/?logout=yes" class="lk-menu__link lk-menu__link_quit">Выйти</a>
        </div>
    </div>
</div>
