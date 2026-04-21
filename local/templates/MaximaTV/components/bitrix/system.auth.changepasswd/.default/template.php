<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<section class="i-section i-section_bg-darkness">
    <div class="g-wrap">
        <div class="feedback">
            <p class="feedback__title"><?=GetMessage("AUTH_CHANGE_PASSWORD")?></p>
            <p><?php ShowMessage($arParams["~AUTH_RESULT"]);?></p>

            <form class="feedback__form js-validate" novalidate="novalidate" name="bform" method="post">
                <?if (strlen($arResult["BACKURL"]) > 0): ?>
                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                <? endif ?>
                <input type="hidden" name="AUTH_FORM" value="Y">
                <input type="hidden" name="TYPE" value="CHANGE_PWD">

                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="USER_LOGIN" type="text" placeholder="<?=GetMessage("AUTH_LOGIN")?>" required="" aria-required="true" value="<?=$arResult["LAST_LOGIN"]?>">
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="USER_CHECKWORD" type="text" placeholder="<?=GetMessage("AUTH_CHECKWORD")?>" required="" aria-required="true" value="<?=$arResult["USER_CHECKWORD"]?>">
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="USER_PASSWORD" type="password" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_REQ")?>" required="" aria-required="true" value="<?=$arResult["USER_PASSWORD"]?>">
                        <span class="form__error"></span>
                    </div>
                </div>
                <div class="feedback__row">
                    <div class="feedback__field form__field">
                        <input class="i-input feedback__input" name="USER_CONFIRM_PASSWORD" type="password" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?>" required="" aria-required="true" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>">
                        <span class="form__error"></span>
                    </div>
                </div>
                <p><br><br></p>
                <p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
                <div class="feedback__footer">
                    <input type="submit" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" class="i-button">
                </div>
            </form>
        </div>
    </div>
</section>