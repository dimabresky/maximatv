<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?php global $USER; ?>

<?php if ($USER->IsAuthorized() && count($_POST) > 0 && $_POST['TYPE'] === 'AUTH') {
    ?>
    <script>
        $(document).ready(function() {
            window.location.reload();
        });
    </script>
    <?
    $backUrl = filter_input(INPUT_POST, 'backurl');
    /*if ($APPLICATION->GetCurPage(false) == $backUrl) {
       ?>
        <script>
            $(document).ready(function() {
                window.location.reload(true);
            });
        </script>
        <?php
    } else { */
		// LocalRedirect($backUrl);
   // }
} else { ?>
<form class="form-login__form js-validate" name="system_auth_form<?=$arResult["RND"]?>" method="post" action="<?=$arResult["AUTH_URL"]?>">
    <?php foreach ($arResult["POST"] as $key => $value) { ?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?php } ?>
    <input type="hidden" name="AUTH_FORM" value="Y" />
    <input type="hidden" name="TYPE" value="AUTH" />
    <input type="hidden" name="backurl" id="login_backurl" value="<?=$arParams['backurl'] ?: '/lk/subscribe/'?>" />

    <p class="form-login__title">Войдите в свой аккаунт</p>
    <?php
        if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'] && stripos($arResult['ERROR_MESSAGE']['MESSAGE'], 'Контрольная строка') === false) {
            ShowMessage($arResult['ERROR_MESSAGE']);
        }
    ?>
    <div class="form-login__row">
        <div class="form-login__field form__field">
            <input class="i-input form-login__input" name="USER_LOGIN" type="email" placeholder="E-mail" required style="font-size: 16px;"/>
            <span class="form__error"></span>
        </div>
    </div>
    <div class="form-login__row">
        <div class="form-login__field form__field">
            <div class="form__password js-password">
                <input class="i-input form-login__input js-password-input" name="USER_PASSWORD" type="password" placeholder="Пароль" required style="font-size: 16px;"/>
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
    <div class="form-login__buttons">
        <div class="form-login__col">
            <?/*div class="form-login__checkbox">
                <label class="checkbox">
                    <span class="checkbox__box">
                        <input type="checkbox" class="checkbox__control" checked>
                        <span class="checkbox__indicator"></span>
                        <span class="checkbox__content">Запомнить меня</span>
                    </span>
                </label>
            </div*/?>
            <a href="/auth/?forgot_password=yes" class="form-login__link">Забыли пароль?</a>
        </div>
        <input type="submit" name="Login" class="i-button form-login__btn" value="Войти">
    </div>
    <div class="form-login__footer">
        <?/*p>Ещё не зарегистрированы?&nbsp;<a href="javascript:void(0)" class="form-login__link">Регистрация</a></p*/?>
    </div>
</form>
<?php } ?>