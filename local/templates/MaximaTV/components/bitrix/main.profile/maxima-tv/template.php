<?php
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Maxima\Helpers\DateHelper;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$photo = '';
if ($arResult['arUser']['PERSONAL_PHOTO'] != '') {
    $arPhoto = CFile::ResizeImageGet(
        $arResult['arUser']['PERSONAL_PHOTO'],
        ['width' => 150, 'height' => 150],
        BX_RESIZE_IMAGE_EXACT
    );
    $photo = $arPhoto['src'];
}
$birthday = explode('.', $arResult['arUser']['PERSONAL_BIRTHDAY']);
?>
<main class="lk__main">
    <div class="g-wrap">
        <div class="lk-edit">
            <form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
                <?=$arResult["BX_SESSION_CHECK"]?>
                <input type="hidden" name="lang" value="<?=LANG?>" />
                <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
                <input type="hidden" name="PERSONAL_BIRTHDAY" value=<?=$arResult['arUser']['PERSONAL_BIRTHDAY']?> />

                <?php ShowError($arResult["strProfileError"]); ?>
                <?php if ($arResult['DATA_SAVED'] == 'Y') { ShowNote(GetMessage('PROFILE_DATA_SAVED')); } ?>
                <section class="lk-edit__section no-padding">
                    <h1 class="lk__title">Редактирование профиля</h1>
                    <div class="lk-edit__photo js-user-photo">
                        <div class="lk-edit__photo-preview js-photo-drag">
                            <img src="<?=$photo?>" class="lk-edit__photo-img js-photo-preview" />
                        </div>
                        <p class="lk-edit__photo-text">Перетяните в область ваше фото или&nbsp;загрузите фото с компьютера</p>
                        <label class="lk-edit__photo-label">
                            <input type="file" name="PERSONAL_PHOTO" multiple="multiple" accept="image/*">
                            <span class="i-button i-button_light">Загрузить фото</span>
                        </label>
                    </div>
                </section>
                <section class="lk-edit__section">
                    <div class="lk-form js-validate">
                        <p class="lk-form__title">Личные данные</p>
                        <div class="lk-form__row">
                            <div class="lk-form__field form__field">
                                <input class="i-input lk-form__input"
                                       name="LAST_NAME"
                                       type="text"
                                       value="<?=$arResult['arUser']['LAST_NAME']?>"
                                       data-inputmask-regex="[a-zA-Zа-яА-ЯЁё ]*"
                                       placeholder="Фамилия" required />
                                <span class="form__error"></span>
                            </div>
                        </div>
                        <div class="lk-form__row">
                            <div class="lk-form__field form__field">
                                <input class="i-input lk-form__input"
                                       name="NAME"
                                       type="text"
                                       value="<?=$arResult['arUser']['NAME']?>"
                                       data-inputmask-regex="[a-zA-Zа-яА-ЯЁё ]*"
                                       placeholder="Имя" required />
                                <span class="form__error"></span>
                            </div>
                        </div>
                        <div class="lk-form__row">
                            <div class="lk-form__field form__field">
                                <input class="i-input lk-form__input"
                                       name="SECOND_NAME"
                                       type="text"
                                       value="<?=$arResult['arUser']['SECOND_NAME']?>"
                                       data-inputmask-regex="[a-zA-Zа-яА-ЯЁё ]*"
                                       placeholder="Отчество" />
                                <span class="form__error"></span>
                            </div>
                        </div>
                        <div class="lk-form__row">
                            <div class="lk-form__field form__field">
                                <input class="i-input lk-form__input"
                                       name="LOGIN"
                                       type="email"
                                       value="<?=$arResult['arUser']['LOGIN']?>"
                                       placeholder="Email" required />
                                <span class="form__error"></span>
                            </div>
                        </div>
                        <?if($arResult['CAN_EDIT_PASSWORD']):?>
                            <div class="lk-form__row">
                                <div class="lk-form__field form__field">
                                    <input class="i-input lk-form__input"
                                           name="NEW_PASSWORD"
                                           type="password"
                                           autocomplete="off"
                                           value=""
                                           placeholder="Пароль"  />
                                    <span class="form__error"></span>
                                </div>
                            </div>
                            <div class="lk-form__row">
                                <div class="lk-form__field form__field">
                                    <input class="i-input lk-form__input"
                                           name="NEW_PASSWORD_CONFIRM"
                                           type="password"
                                           autocomplete="off"
                                           value=""
                                           placeholder="Подтверждение пароля"  />
                                    <span class="form__error"></span>
                                </div>
                            </div>
                        <?endif?>
                        <p class="lk-form__title">День рождения</p>
                        <div class="lk-form__row lk-form__row_flex">
                            <div class="lk-form__select-day">
                                <div class="chosen-wrapper chosen-wrapper_light" data-js="custom-scroll">
                                    <select class="chosen-select js-chosen js-birthday" data-placeholder="День" id="birthDay">
                                        <option></option>
                                        <?php $maxDay = 31; ?>
                                        <?php for ($day = 1; $day < $maxDay; $day++) { ?>
                                            <?php $strDay = sprintf('%02d', $day); ?>
                                            <option value="<?=$strDay?>" <?=($strDay === $birthday[0]) ? 'selected' : ''?>><?=$strDay?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="lk-form__select-month">
                                <div class="chosen-wrapper chosen-wrapper_light" data-js="custom-scroll">
                                    <select class="chosen-select js-chosen js-birthday" data-placeholder="Месяц" id="birthMonth">
                                        <option></option>
                                        <?php $months = DateHelper::getAllLteralMonths(); ?>
                                        <?php foreach ($months as $monthNumber => $month) { ?>
                                            <option value="<?=$monthNumber?>" <?=((string)$monthNumber === $birthday[1]) ? 'selected' : ''?>><?=$month?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="lk-form__select-year">
                                <div class="chosen-wrapper chosen-wrapper_light" data-js="custom-scroll">
                                    <select class="chosen-select js-chosen js-birthday" data-placeholder="Год" id="birthYear">
                                        <option></option>
                                        <?php $maxYear = (int)date('Y'); ?>
                                        <?php for ($year = 1950; $year < $maxYear; $year++) { ?>
                                            <option value="<?=$year?>" <?=($year === (int)$birthday[2]) ? 'selected' : ''?>><?=$year?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="feedback__footer">
                            <input class="i-button" type="submit" name="save" value="Сохранить" />
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $(".js-birthday").on("change", function() {
            $('input[name="PERSONAL_BIRTHDAY"]').val($("#birthDay").val() + '.' + $("#birthMonth").val() + '.' + $("#birthYear").val());
        });
    });
</script>