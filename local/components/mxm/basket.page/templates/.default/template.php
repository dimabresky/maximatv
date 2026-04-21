<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<!--

    <? if($arResult['BASKET']['ITEMS']): ?>
    <div class="header__cart-list js-cart-list">


        <div class="header__cart-footer">
            <p>Итого:</p>
            <p class="header__cart-sum"><?= number_format($arResult['BASKET']['SUM'], 0 , "," , " " ); ?>&nbsp;<span class="rub">ю</span></p>
        </div>
        <a href="/lk/buy-photo/" class="header__cart-link">Перейти к оплате</a>
    </div>
    <? endif; ?>
<?php if(empty($arResult['IS_AJAX'])): ?>
</div>
<? endif ?> -->

<div class="g-wrap" xmlns="http://www.w3.org/1999/html">
    <div class="cart__header">
        <div class="cart__header-row">
            <h1 class="cart__title">Корзина</h1>
            <? if(!empty($arResult['BASKET']['ITEMS'])): ?>
                <p class="cart__count js-cart-page-count"><?= $arResult['BASKET']['COUNT'] ?> фото</p>
            <? endif ?>
        </div>
        <? if(!empty($arResult['BASKET']['ITEMS'])): ?>
            <a href="javascript:void(0)" class="cart__clean js-clean-cart-page">Удалить все</a>
        <? endif ?>
    </div>
    <? if(!empty($arResult['BASKET']['ITEMS'])): ?>
    <div class="cart__content">
        <? if(!empty($arResult['PHOTO_PRICE'])): ?>
            <ul class="event-photo__list event-photo__list_cart">
                <? foreach($arResult['PHOTO_PRICE'] as  $k => $arItem): ?>
                    <li class="event-photo__item"><p class="event-photo__count"><?= $arItem['UF_PHOTO_COUNT'] ?> шт</p>
                        <p class="event-photo__price"><?= $arItem['UF_PRICE'] * $arItem['UF_PHOTO_COUNT'] ?> &nbsp;<span class="rub">ю</span></p>
                    </li>
                <? endforeach ?>
            </ul>
        <? endif ?>
        <? foreach($arResult['BASKET']['ITEMS'] as $arItem): ?>
            <div class="cart__item js-cart-page-item" data-id="<?= $arItem['ID'] ?>">
                <a href="javascript:void(0);" class="cart__item-title cart__item-title_mb"><?=html_entity_decode($arItem['NAME']) ?></a>
                <div class="cart__item-image">
                    <?

                    $prev = CFile::ResizeImageGet(
                        $arItem['PHOTO']['ID'],
                        ['width' => 768, 'height' => 536],
                        BX_RESIZE_IMAGE_PROPORTIONAL,
                        false,
                        [
                            [
                                "name" => "watermark",
                                "position" => "center", // Положение
                                "type" => "image",
                                "size" => "real",
                                "file" => $_SERVER["DOCUMENT_ROOT"].'/local/images/Maxima-logo-2.png',
                                "fill" => "exact",
                            ]
                        ]
                    );
                    $orig = CFile::ResizeImageGet(
                        $arItem['PHOTO']['ID'],
                        ['width' => 2000, 'height' => 2000],
                        BX_RESIZE_IMAGE_PROPORTIONAL,
                        false,
                        [
                            [
                                "name" => "watermark",
                                "position" => "center", // Положение
                                "type" => "image",
                                "size" => "real",
                                "file" => $_SERVER["DOCUMENT_ROOT"].'/local/images/maxima-logo-750.png',
                                "fill" => "exact",
                            ]
                        ]
                    );

                    ?>
                    <a href="<?= $orig['src'] ?>" class="js-cart-img"><img src="<?= $prev['src'] ?>" class="cart__item-img" alt="" /></a>
                </div>
                <div class="cart__item-row">
                    <a href="<?= $orig['src'] ?>" class="cart__item-title js-cart-img"><?=html_entity_decode($arItem['NAME']) ?></a>
                    <div class="cart__item-info">
                        <p class="cart__item-name">формат:</p>
                        <p class="cart__item-val"><?= strtoupper(str_replace('image/', '', $arItem['PHOTO']['CONTENT_TYPE'])); ?></p>
                    </div>
                    <div class="cart__item-info">
                        <p class="cart__item-name">размер:</p>
                        <p class="cart__item-val"><?= number_format($arItem['PHOTO']['WIDTH'], 0 , "," , " " ) ?> х
                            <?= number_format($arItem['PHOTO']['HEIGHT'], 0 , "," , " " ) ?> px</p>
                    </div>
                    <div class="cart__item-price"><span class="js-photo-price"><?= $arItem['PRICE'] ?></span> <span class="rub">ю</span></div>
                    <a href="javascript:void(0);" class="cart__item-del js-cart-page-btn" data-id="<?= $arItem['ID'] ?>">
                        <svg class="i-svg" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2L16 16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16 2L2 16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>
            </div>
        <? endforeach; ?>
    </div>
    <div class="cart__footer js-cart-page-footer">
        <p class="cart__footer-title">Итого к оплате:</p>
        <p class="cart__sum"><span class="js-all-cart-sum"><?= number_format($arResult['BASKET']['SUM'], 0 , "," , " " ); ?></span> <span class="rub">ю</span></p>
        <a class="cart__btn i-button" href="/lk/buy-photo/">Оплатить</a>
    </div>
    <? else: ?>
        <div class="cart__item-row">Ваша корзина пуста</div>
    <? endif ?>

</div>
