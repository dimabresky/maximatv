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
use Maxima\Helpers\DateHelper;

$ajaxScriptName = CommonHelper::getAjaxHandlerByFilterName($arParams['FILTER_NAME']);

$this->setFrameMode(true);
?>
<div class="i-section__header">
    <h2 class="i-section__title"><?=$arParams['FILTER_TITLE']?></h2>
    <form class="i-section__col filter">
        <div class="filter__item">
            <label class="filter__label" for="year">год</label>
            <div class="filter__select">
                <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                    <select class="chosen-select js-chosen" name="arrProgramsList[YEAR]" id="year" data-placeholder="год">
                        <?php $year = (int)date('Y'); ?>
                        <?php for ($counter = 0; $counter < 10; $counter++) { ?>
                            <option value="<?=$year?>"><?=$year?></option>
                            <?php $year--;?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="filter__item">
            <label class="filter__label" for="month">месяц</label>
            <div class="filter__select">
                <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                    <?php
                        $months = DateHelper::getAllLteralMonths();
                        $currentMonth = date('m');
                    ?>
                    <select class="chosen-select js-chosen" name="arrProgramsList[MONTH]" id="month" data-placeholder="год">
                        <?php foreach ($months as $key => $value) { ?>
                            <option value="<?=$key?>" <?=($key === $currentMonth) ? 'selected' : ''?>><?=$value?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="filter__item filter__item_sort">
            <label class="checkbox checkbox_sort">
                            <span class="checkbox__box">
                                <input type="checkbox"
                                       class="checkbox__control" checked>
                                <span class="checkbox__content">дата</span>
                                <span class="checkbox__indicator">
                                    <svg class="i-svg" xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15" fill="none">
                                        <path d="M1 5.0625L5.0625 1L9.125 5.0625" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M5.0625 1L5.0625 14" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </span>
            </label>
        </div>
    </form>
</div>

<script>
    //var ajaxScriptName = '<?=$ajaxScriptName?>';
    var itemsSelector = '.filter__select select.js-chosen';

    function getItemsFilterParams() {
        var params = {};
        $(itemsSelector).each(function() {
            params[$(this).attr('name')] = $(this).val();
        });
        params['FILTER_NAME'] = '<?=$arParams['FILTER_NAME']?>';

        return params;
    }

    function getItemsResult(params) {
        $.ajax({
            method: 'GET',
            url: '<?=$ajaxScriptName?>',
            data: params
        })
            .done(function( data ) {
                $('#programs_list-container').empty().html(data);
            });
    }

    $( document ).ready(function() {
        $(itemsSelector).on('change', function() {
            var params = getItemsFilterParams();
            getItemsResult(params);
        });
    });
</script>
<?/*div class="i-section__header">
    <h2 class="i-section__title"><?=$arParams['FILTER_TITLE']?></h2>
    <div class="i-section__col filter">
        <?php foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?php if (array_key_exists("HIDDEN", $arItem)) {continue;} ?>
            <div class="filter__item">
                <label class="filter__label"><?=$arItem["NAME"]?></label>
                <div class="filter__select">
                    <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                        <?=str_replace('<select ', '<select class="chosen-select js-chosen" data-placeholder="' . $arItem["NAME"] . '"', $arItem["INPUT"])?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    //var ajaxScriptName = '<?=$ajaxScriptName?>';
    var itemsSelector = '.filter__select select.js-chosen';

    function getItemsFilterParams() {
        var params = {};
        $(itemsSelector).each(function() {
            params[$(this).attr('name')] = $(this).val();
        });
        params['FILTER_NAME'] = '<?=$arParams['FILTER_NAME']?>';

        return params;
    }
    
    function getItemsResult(params) {
        $.ajax({
            method: 'GET',
            url: '<?=$ajaxScriptName?>',
            data: params
        })
        .done(function( data ) {
            $('#news_list-container').empty().html(data);
        });
    }

    $( document ).ready(function() {
        $(itemsSelector).on('change', function() {
            var params = getItemsFilterParams();
            getItemsResult(params);
        });
    });
</script*/?>