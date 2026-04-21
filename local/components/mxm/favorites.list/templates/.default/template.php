<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?php if (count($arResult['ITEMS']) > 0) { ?>
<main class="lk__main js-tabs">
    <div class="g-wrap">
        <div class="lk__header">
            <h1 class="lk__title">Избранное</h1>
        </div>
        <div class="tabs__content js-tabs-content is-active" data-group="video">
            <div class="lk-rides">
                <form class="lk-rides__form js-check-group" action="">
                    <div class="lk-rides__group lk-rides__group_nobrd lk-rides__group_pb0">
                        <div class="lk-rides__header lk-rides__header_photo">
                            <div class="lk-rides__header-col">
                                <div class="lk-rides__download js-check-download">
                                    <a href="javascript:void(0)" class="lk-rides__download-link js-del-favorites" data-uid="<?=$USER->GetID()?>">
                                            <span class="lk-rides__download-icon">
                                                <svg class="i-svg" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                                d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                        <path d="M7 10L12 15L17 10" stroke-width="2"
                                                              stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M12 15V3" stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round"></path>
                                                    </svg>
                                            </span>
                                        <span class="lk-rides__download-text">Удалить выбранное</span>
                                    </a>
                                    <?/*span class="lk-rides__download-desc js-check-size"></span*/?>
                                </div>
                            </div>
                            <div class="lk-rides__header-col">
                                <a href="javascript:void(0)" class="lk-rides__link js-check-all"><span>выбрать все</span></a>
                                <a href="javascript:void(0)"
                                   class="lk-rides__link lk-rides__link_red js-reset-all"><span>отменить выбор</span></a>
                            </div>
                        </div>
                        <div class="lk-rides__list">
                            <?php foreach ($arResult['ITEMS'] as $item) { ?>
                                <div class="card">
                                    <a href="<?=$item['LINK']?>" class="card__link-main"></a>
                                    <div class="card__check">
                                        <label class="checkbox">
                                            <span class="checkbox__box">
                                                <input type="checkbox" class="checkbox__control js-check-item"
                                                       name="cardCheckbox<?=$item['ID']?>" data-size="838860800" data-eid="<?=$item['ID']?>">
                                                <span class="checkbox__indicator checkbox__indicator_blue"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="card__row-top">
                                        <span class="card__label"><?=$item['DATE']?></span>
                                    </div>
                                    <div class="card__preview" style="background-image:url('<?=$item['PICTURE']?>')">
                                        <span class="card__btn"></span>
                                    </div>
                                    <div class="card__content">
                                        <div class="card__header">
                                            <p class="card__address"><?=$item['PLACE']?></p>
                                            <p class="card__stage"></p>
                                        </div>
                                        <p class="card__title"><?=$item['NAME']?></p>
                                    </div>
                                    <div class="card__footer">
                                        <div class="card__theme card__theme_download">
                                            <p class="card__theme-title">дисциплина:</p>
                                            <p class="card__theme-name"><?=$item['DISCIPLINE']?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php } ?>