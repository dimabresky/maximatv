<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<section class="i-section i-section_bg-darkness">
    <div class="g-wrap">
        <div class="feedback">
            <p class="feedback__title">Обратная связь</p>
            <form name="feedback" action="" method="POST" class="feedback__form js-validate">
                <?=bitrix_sessid_post()?>
                <input type="hidden" name="WEB_FORM_ID" value="<?=\CForm::GetBySID('feedback')->Fetch()['ID']?>">
                <?php
                    if ($arResult["isFormErrors"] == "Y") {
                        echo '<b>' . $arResult["FORM_ERRORS_TEXT"] . '</b>';
                    }
                    if (strlen($arResult["FORM_NOTE"]) > 0 || $_GET['formresult'] === 'addok') {
                        echo '<b style="color:#3549ff;">' . "Спасибо, Ваше сообщение отправлено. Мы свяжемся с Вами в ближайшее время." . '</b>';
                    }
                ?>
                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="form_text_1" type="text" placeholder="Ваше имя" required />
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="form_email_2" type="email" placeholder="E-mail" required />
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <textarea class="i-input i-input_textarea feedback__input" name="form_textarea_3" type="text" placeholder="Ваше сообщение" required></textarea>
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__checkbox">
                    <label class="checkbox">
                         <span class="checkbox__box">
                             <input type="checkbox" class="checkbox__control" name="userAgree" checked required>
                             <span class="checkbox__indicator checkbox__indicator_light"></span>
                             <span class="checkbox__content">Отправляя форму, вы даете согласие <a href="javascript:void(0)" class="checkbox__link">на обработку персональных данных</a></span>
                         </span>
                    </label>
                </div>
                <div class="feedback__footer">
                    <input type="submit" name="web_form_submit" value="Отправить" class="i-button" />
                </div>
            </form>
        </div>
    </div>
</section>