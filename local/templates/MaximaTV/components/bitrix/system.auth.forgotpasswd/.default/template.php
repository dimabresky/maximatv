<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
?>

<section class="i-section i-section_bg-darkness">
    <div class="g-wrap">
        <div class="feedback">
            <p class="feedback__title">Восстановление пароля</p>
            <?php
                if (!empty($arParams["~AUTH_RESULT"])) {
                    $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
                    echo '<p>' . nl2br(htmlspecialcharsbx($text)) . '</p>';
                } else {
                    ?>
                    <p>Если вы забыли пароль, введите Ваш E-Mail. Контрольная строка для смены пароля, а также ваши регистрационные данные, будут высланы вам на электронную почту.</p>
                    <?php
                }
            ?>


            <form class="feedback__form js-validate" novalidate="novalidate" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
                <?if($arResult["BACKURL"] <> ''):?>
                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                <?endif?>
                <input type="hidden" name="AUTH_FORM" value="Y">
                <input type="hidden" name="TYPE" value="SEND_PWD">

                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="USER_LOGIN" type="email" placeholder="E-mail" required="" aria-required="true">
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__footer">
                    <input type="submit" name="send_account_info" value="Выслать" class="i-button">
                </div>
            </form>
        </div>
    </div>
    <br/><br/><br/><br/><br/><br/><br/>
</section>
