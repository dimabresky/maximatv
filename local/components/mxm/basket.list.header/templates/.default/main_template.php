<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?php if(empty($arResult['IS_AJAX'])): ?>
    <div class="header__cart js-cart-header is-open">
<? endif ?>
    <a href="/lk/cart/" class="header__cart-btn js-cart-btn">
        <? if(!empty($arResult['BASKET']['COUNT'])): ?>
            <span class="header__cart-count"><?= $arResult['BASKET']['COUNT']; ?></span>
        <? endif ?>
    </a>
    <? if(false && $arResult['BASKET']['ITEMS']): ?>
    <div class="header__cart-list js-cart-list">
        <a href="#" class="header__cart-close js-cart-close">
            <svg class="i-svg" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 2L16 16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M16 2L2 16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
        <div class="header__cart-wrap js-cart-wrap">
            <? foreach($arResult['BASKET']['ITEMS'] as $arItem): ?>
                <div class="cart-item">
                    <div class="cart-item__img">
                        <?
                        $filter = [
                            [
                                "name" => "watermark",
                                "position" => "center", // Положение
                                "type" => "image",
                                "size" => "real",
                                "file" => $_SERVER["DOCUMENT_ROOT"].'/local/images/maxima-logo.png',
                                "fill" => "exact",
                            ]
                        ];
                        $prev = CFile::ResizeImageGet(
                                $arItem['PHOTO'],
                                ['width' => 55, 'height' => 56],
                                BX_RESIZE_IMAGE_EXACT,
                                false,
                                $filter
                        );
                        ?>
                        <img src="<?= $prev['src'] ?>" alt="" />
                    </div>
                    <div class="cart-item__content">
                        <a href="javascript:void(0)" class="cart-item__name"><? //= $arItem['NAME'] ?></a>
                        <p class="cart-item__price"><?= $arItem['PRICE'] ?><span class="rub">ю</span></p>
                    </div>
                    <a href="javascript:void(0)" class="cart-item__delete js-cart-item__delete" data-id="<?= $arItem['ID'] ?>">
                        <svg class="i-svg" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2L16 16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16 2L2 16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>
            <? endforeach; ?>
        </div>
        <div class="header__cart-footer">
            <p>Итого:</p>
            <p class="header__cart-sum"><?= number_format($arResult['BASKET']['SUM'], 0 , "," , " " ); ?>&nbsp;<span class="rub">ю</span></p>
        </div>
        <a href="/lk/cart/" class="header__cart-link">Перейти к оплате</a>
    </div>
    <? endif; ?>
<?php if(empty($arResult['IS_AJAX'])): ?>
</div>
<? endif ?>