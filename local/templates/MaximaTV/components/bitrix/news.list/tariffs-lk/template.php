<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
?>
<?php if (count($arResult['ITEMS']) > 0) {?>
    <main class="lk__main js-tabs">
        <div class="g-wrap">
            <div class="lk__header">
                <h1 class="lk__title">Подписка</h1>
                <?/*div class="tabs_light">
                    <div class="tabs__links">
                        <a href="javascript:void(0);" class="tabs__link js-tabs-link is-active" data-group="01">1 месяц</a>
                        <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="02">6 месяцев</a>
                        <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="03">12 месяцев</a>
                    </div>
                </div*/?>
            </div>
            <?/*div class="tabs__content js-tabs-content" data-group="01"*/?>
                <div class="subscription__list subscription__list_lk js-subscription-lk-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                            <div class="subscription-lk swiper-slide">
                                <div class="subscription-lk__header">
                                    <p class="subscription-lk__name <?=$arItem['PROPERTIES']['CSS_CLASS_LK']['~VALUE']?>"><?=$arItem['NAME']?></p>
                                </div>
                                <div class="subscription-lk__list">
                                    <?=$arItem['PROPERTIES']['LK_DESCRIPTION']['~VALUE']['TEXT']?>
                                </div>
                                <?php if ($arItem['ID'] == $arResult['CURRENT_TARIFF']['UF_TARIFF_ID']) { ?>
                                    <div class="subscription-lk__price subscription-lk__price_current">
                                        <p>Ваш текущий тариф</p>
                                    </div>
                                    <div class="subscription-lk__footer">
                                        <p>
                                            Окончание действия: <b><?=$arResult['CURRENT_TARIFF']['UF_SUBSCRIPTION_TO']->format('d.m.Y')?></b><br><br>
                                            <a data-href="<?=$arItem['DETAIL_PAGE_URL']?>" class="subscription-lk__btn i-button" data-remodal-target="userAgreement">
                                                Продлить
                                            </a>
                                        </p>
                                    </div>
                                <?php } else { ?>
                                    <div style="text-decoration: line-through; font-size: 25px; color: #666666;">
                                        <br><?=$arItem['PROPERTIES']['OLD_PRICE']['VALUE'] ?: '' ?>
                                    </div>
                                    <div class="subscription-lk__price">
                                        <div class="subscription-lk__price-wrap">

                                            <?=$arItem['PROPERTIES']['LK_PRICE']['~VALUE']['TEXT']?>
                                        </div>
                                    </div>
                                    <div class="subscription-lk__footer">
                                        <a data-href="<?=$arItem['DETAIL_PAGE_URL']?>" class="subscription-lk__btn i-button" data-remodal-target="userAgreement">
                                            <?=$arItem['PROPERTIES']['BUY_BUTTON_TEXT']['VALUE']?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            <?/*/div*/?>
        </div>
        <div data-remodal-id="userAgreement" data-remodal-options="hashTracking: false"
             class="remodal modal modal_slim">
            <a data-remodal-action="close" class="modal__close"></a>
            <div class="modal__content agreement-block">
                <h3 class="agreement-block__title">Пользовательское соглашение</h3>
                <div class="agreement-block__content">
                    <p>Настоящее Соглашение является публичной офертой в&nbsp;соответствии со&nbsp;статьей
                        437&nbsp;ГК РФ&nbsp;и&nbsp;регламентирует отношения между Компанией
                        и&nbsp;Пользователем.</p>

                    <p>Пользователь уведомлен, что получая доступ к&nbsp;архивному материалу, он&nbsp;праве
                        использовать его только для собственного просмотра, отснятый видео материал принадлежит
                        ООО
                        &laquo;МАКСИМА&nbsp;ТВ&raquo; и&nbsp;его использование, копирование, тиражирование
                        в&nbsp;коммерческих целях и&nbsp;некоммерческих целях ЗАПРЕЩЕНО и&nbsp;предусматривает
                        административную (ст.7.12 КоАП&nbsp;РФ) и&nbsp;уголовную (ст.&nbsp;146&nbsp;УК РФ)
                        ответственность в&nbsp;соответствии с&nbsp;законодательством&nbsp;РФ.</p>
                </div>
                <div class="agreement-block__footer">
                    <label class="agreement-block__checkbox checkbox">
                                <span class="checkbox__box">
                                    <input type="checkbox" class="checkbox__control" name="userAgree" required>
                                    <span class="checkbox__indicator checkbox__indicator_purple"></span>
                                    <span class="checkbox__content">Я принимаю условия соглашения</span>
                                </span>
                    </label>
                    <button type="button" class="i-button i-button_sm" disabled>Далее</button>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modalAgreement = document.querySelector('[data-remodal-id="userAgreement"]')

                    if (!modalAgreement) {
                        return false;
                    }

                    const checkbox = modalAgreement.querySelector('[type="checkbox"]')
                    const modalAgreementButton = modalAgreement.querySelector('[type="button"]')
                    const $closeBtn = $(modalAgreement).find('[data-remodal-action="close"]')

                    checkbox.addEventListener('change', () => {
                        if (checkbox.checked) {
                            modalAgreementButton.removeAttribute('disabled')
                        } else {
                            modalAgreementButton.setAttribute('disabled', true)
                        }
                    })

                    modalAgreementButton.addEventListener('click', () => {
                        let dataHref = modalAgreementButton.getAttribute('data-href')
                        if (dataHref) {
                            window.location.href = dataHref
                        } else {
                            $closeBtn.trigger('click')
                        }
                    })

                    const $payButtons = $('.subscription-lk__btn.i-button')
                    $payButtons.on('click',function() {
                        modalAgreementButton.setAttribute('data-href', this.getAttribute('data-href'))
                    })
                })
            </script>
        </div>
    </main>
<?php } ?>