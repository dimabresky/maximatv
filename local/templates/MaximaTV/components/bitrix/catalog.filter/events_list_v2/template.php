<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Maxima\Helpers\CommonHelper;

$ajaxScriptName = '/local/ajax/events_v2.php';//CommonHelper::getAjaxHandlerByFilterName($arParams['FILTER_NAME']);

$this->setFrameMode(true);
?>
<div class="i-section__header">
    <h2 class="i-section__title"><?=$arParams['FILTER_TITLE']?></h2>
    <div class="i-section__col filter <?=$arParams['FILTER_NAME']?>">
        <?php foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?php if (array_key_exists("HIDDEN", $arItem)) {continue;} ?>
            <div class="filter__item" style="margin-bottom: 16px;">
                <label class="filter__label"><?=$arItem["NAME"]?></label>
                <div class="filter__select">
                    <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                        <?=str_replace('(все)', 'Все', str_replace('<select ', '<select class="chosen-select js-chosen" data-placeholder="' . $arItem["NAME"] . '"', $arItem["INPUT"]))?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="filter__item filter__item_calendar" style="margin-bottom: 16px;">
            <label class="filter__label">Дата</label>
            <div class="filter-datepicker filter-datepicker_light js-datepicker" style="margin-left: -5px;">
                <input class="filter-datepicker__input js-datepicker-input i-input"
                       name="<?=$arParams['FILTER_NAME']?>_pf[DATES]"
                       data-range="true"
                       data-multiple-dates-separator=" - "
                       placeholder="XX.XX.XXXX - XX.XX.XXXX"
                       type="text" />
                <span class="filter-datepicker__icon js-datepicker-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="21px">
                    <path fill-rule="evenodd" d="M15.000,21.000 L3.000,21.000 C1.343,21.000 -0.000,19.657 -0.000,18.000 L-0.000,6.000 C-0.000,4.343 1.343,3.000 3.000,3.000 L3.000,1.000 C3.000,0.448 3.448,-0.000 4.000,-0.000 C4.552,-0.000 5.000,0.448 5.000,1.000 L5.000,3.000 L13.000,3.000 L13.000,1.000 C13.000,0.448 13.448,-0.000 14.000,-0.000 C14.552,-0.000 15.000,0.448 15.000,1.000 L15.000,3.000 C16.657,3.000 18.000,4.343 18.000,6.000 L18.000,18.000 C18.000,19.657 16.657,21.000 15.000,21.000 ZM16.000,6.000 C16.000,5.448 15.552,5.000 15.000,5.000 L3.000,5.000 C2.448,5.000 2.000,5.448 2.000,6.000 L2.000,18.000 C2.000,18.552 2.448,19.000 3.000,19.000 L15.000,19.000 C15.552,19.000 16.000,18.552 16.000,18.000 L16.000,6.000 ZM12.000,11.000 L14.000,11.000 L14.000,13.000 L12.000,13.000 L12.000,11.000 ZM12.000,7.000 L14.000,7.000 L14.000,9.000 L12.000,9.000 L12.000,7.000 ZM8.000,15.000 L10.000,15.000 L10.000,17.000 L8.000,17.000 L8.000,15.000 ZM8.000,11.000 L10.000,11.000 L10.000,13.000 L8.000,13.000 L8.000,11.000 ZM8.000,7.000 L10.000,7.000 L10.000,9.000 L8.000,9.000 L8.000,7.000 ZM4.000,15.000 L6.000,15.000 L6.000,17.000 L4.000,17.000 L4.000,15.000 ZM4.000,11.000 L6.000,11.000 L6.000,13.000 L4.000,13.000 L4.000,11.000 ZM4.000,7.000 L6.000,7.000 L6.000,9.000 L4.000,9.000 L4.000,7.000 Z"></path>
                </svg>
            </span>
            </div>
        </div>
    </div>
    <?/*div class="i-section__col tabs">
        <div class="tabs__links">
            <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="video">Видео</a>
            <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="photo">Фото</a>
        </div>
    </div*/?>
</div>

<script>
    var eventsSelector = '.i-section__col.filter.<?=$arParams['FILTER_NAME']?> .filter__select select.js-chosen';

    function getEventsFilterParams() {
        var params = {},
            datePicker;
        $(eventsSelector).each(function() {
            params[$(this).attr('name')] = $(this).val();
        });
        datePicker = $('.js-datepicker-input');
        params[datePicker.attr('name')] = datePicker.val();
        params['FILTER_NAME'] = '<?=$arParams['FILTER_NAME']?>';

        return params;
    }
    
    function getEventsResult(params) {
        $.ajax({
            method: 'GET',
            url: '<?=$ajaxScriptName?>',
            data: params
        })
        .done(function( data ) {
            $('#events_list-container').empty().html(data);
            $('.js-card-list').each(function(i, item) {
                $(item).initCardList();
            });
        });
    }

    $( document ).ready(function() {
        $(eventsSelector).on('change', function() {
            var params = getEventsFilterParams();
            getEventsResult(params);
        });
        $(document).on('selectPeriod', function() {
            console.log('here');
            var params = getEventsFilterParams();
            getEventsResult(params);
        });
    });
</script>