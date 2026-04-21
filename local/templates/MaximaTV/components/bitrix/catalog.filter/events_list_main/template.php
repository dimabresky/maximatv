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

$ajaxScriptName = CommonHelper::getAjaxHandlerByFilterName($arParams['FILTER_NAME']);

$this->setFrameMode(true);
?>
<div class="i-section__header">
    <h2 class="i-section__title"><?=$arParams['FILTER_TITLE']?></h2>
    <div class="i-section__col filter <?=$arParams['FILTER_NAME']?>">
        <?php foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?php if (array_key_exists("HIDDEN", $arItem)) {continue;} ?>
            <div class="filter__item">
                <label class="filter__label"><?=$arItem["NAME"]?></label>
                <div class="filter__select">
                    <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                        <?=str_replace('(все)', 'Все', str_replace('<select ', '<select class="chosen-select js-chosen" data-placeholder="' . $arItem["NAME"] . '"', $arItem["INPUT"]))?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?/*div class="i-section__col tabs">
        <div class="tabs__links">
            <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="video">Видео</a>
            <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="photo">Фото</a>
        </div>
    </div*/?>
</div>

<script>
    //var ajaxScriptName = '<?=$ajaxScriptName?>';
    var eventsSelector = '.i-section__col.filter.<?=$arParams['FILTER_NAME']?> .filter__select select.js-chosen';

    function getEventsFilterParams() {
        var params = {};
        $(eventsSelector).each(function() {
            params[$(this).attr('name')] = $(this).val();
        });
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
        });
    }

    $( document ).ready(function() {
        $(eventsSelector).on('change', function() {
            var params = getEventsFilterParams();
            getEventsResult(params);
        });
    });
</script>