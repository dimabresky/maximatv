<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arFieldNames = [
    'NAME'              => 'Имя',
    'LAST_NAME'         => 'Фамилия',
    'LOGIN'             => 'E-mail',
    'PASSWORD'          => 'Пароль',
    'CONFIRM_PASSWORD'  => 'Подтверждение пароля',
];

global $USER;
?>

<?php if ($USER->IsAuthorized() && count($_POST) > 0 && !empty($_POST['registration_backurl'])) {
    $backUrl = filter_input(INPUT_POST, 'registration_backurl');
    if ($APPLICATION->GetCurPage(false) == $backUrl) {
        ?>
        <script>
            $(document).ready(function() {
                window.location.reload(true);
            });
        </script>
        <?php
    } else {
        LocalRedirect($backUrl);
    }
} elseif (count($_POST) > 0 && !empty($arResult['VALUES']['CHECKWORD'])  && empty($arResult["ERRORS"])) { ?>
    <form class="form-login__form js-validate js-registration-modal">
        Спасибо за регистрацию! Но это еще не все.<br/><br/>
        Для активации аккаунта перейдите по ссылке, которая была отправлена на ваш e-mail.
        Проверьте, не попало ли письмо с активацией в папку со спамом.<br/><br/><br/>
    </form>
<?php } else { ?>
    <form class="form-login__form js-validate js-registration-modal" method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
        <input type="hidden" name="registration_backurl" id="registration_backurl" value="<?=$arParams['backurl'] ?: '/lk/'?>" />
        <input type="hidden" name="REGISTER[EMAIL]" id="js-register-email"  value="<?=$arResult['VALUES']['EMAIL']?>" value="" />
        <?php
            if (count($arResult["ERRORS"]) > 0) {
                foreach ($arResult["ERRORS"] as $key => $error) {
                    if ((int)$key === 0 && $key !== 0) {
                        $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . $key . "&quot;", $error);
                    }
                }

                ShowError(implode("<br />", $arResult["ERRORS"]));
            }
        ?>
        <div class="form-login__row">
            <div class="form-login__field form__field">
                <input class="i-input form-login__input" name="REGISTER[NAME]" value="<?=$arResult['VALUES']['NAME']?>" type="text" placeholder="Имя"
                       required/>
                <span class="form__error"></span>
            </div>
        </div>
        <div class="form-login__row">
            <div class="form-login__field form__field">
                <input class="i-input form-login__input" name="REGISTER[LAST_NAME]" value="<?=$arResult['VALUES']['LAST_NAME']?>" type="text" placeholder="Фамилия"
                       required/>
                <span class="form__error"></span>
            </div>
        </div>
        <div class="form-login__row">
            <div class="form-login__field form__field">
                <input class="i-input form-login__input js-register-login" name="REGISTER[LOGIN]"  value="<?=$arResult['VALUES']['LOGIN']?>" type="email" placeholder="E-mail"
                       required/>
                <span class="form__error"></span>
            </div>
        </div>
        <div class="form-login__row">
            <div class="form-login__field form__field">
                <div class="form__password js-password">
                    <input class="i-input form-login__input js-password-input" name="REGISTER[PASSWORD]"
                           type="password"
                           placeholder="Пароль" required/>
                    <span class="form__password-icon js-password-btn">
                                        <svg class="i-svg" viewBox="0 0 24 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M22 9.83333C22 9.83333 17.5228 15.6667 12 15.6667C6.47715 15.6667 2 9.83333 2 9.83333C2 9.83333 6.47715 4 12 4C17.5228 4 22 9.83333 22 9.83333Z"
                                                    stroke="#2A2D4E" fill="none" stroke-width="2"></path>
                                            <circle cx="12.0003" cy="9.83333" r="3.33333" fill="#2A2D4E"></circle>
                                            <path id="iconLine" d="M20 2.31348L4 18.3135" stroke="#2A2D4E" fill="none"
                                                  stroke-width="2" stroke-linecap="round"></path>
                                        </svg>
                                    </span>
                </div>
                <span class="form__error"></span>
            </div>
        </div>
        <div class="form-login__row">
            <div class="form-login__field form__field">
                <div class="form__password js-password">
                    <input class="i-input form-login__input js-password-input" name="REGISTER[CONFIRM_PASSWORD]"
                           type="password"
                           placeholder="Подтверждение пароля" required/>
                    <span class="form__password-icon js-password-btn">
                                        <svg class="i-svg" viewBox="0 0 24 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M22 9.83333C22 9.83333 17.5228 15.6667 12 15.6667C6.47715 15.6667 2 9.83333 2 9.83333C2 9.83333 6.47715 4 12 4C17.5228 4 22 9.83333 22 9.83333Z"
                                                    stroke="#2A2D4E" fill="none" stroke-width="2"></path>
                                            <circle cx="12.0003" cy="9.83333" r="3.33333" fill="#2A2D4E"></circle>
                                            <path id="iconLine" d="M20 2.31348L4 18.3135" stroke="#2A2D4E" fill="none"
                                                  stroke-width="2" stroke-linecap="round"></path>
                                        </svg>
                                    </span>
                </div>
                <span class="form__error"></span>
            </div>
        </div>
        <div class="form-login__buttons form-login__buttons_reg">
            <div class="form-login__checkbox">
                <label class="checkbox">
                                    <span class="checkbox__box">
                                        <input type="checkbox"
                                               class="checkbox__control" name="userAgree" checked required>
                                        <span class="checkbox__indicator"></span>
                                        <span class="checkbox__content">Регистрируясь на сайте, вы даете согласие <a
                                                    href="https://maximatv.ru/policy/" class="checkbox__link">на обработку персональных данных</a></span>
                                    </span>
                </label>
            </div>
            <input class="i-button form-login__btn" type="submit" name="register_submit_button" value="Зарегистрироваться" />
        </div>
    </form>
<?php } ?>
