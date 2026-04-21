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
    <div class="i-section__col filter filter_left <?=$arParams['FILTER_NAME']?>">
        <?php foreach ($arResult["ITEMS"] as $arItem) { ?>
            <?php if (array_key_exists("HIDDEN", $arItem)) {continue;} ?>
            <div class="filter__item filter__item_light">
                <label class="filter__label"><?=$arItem["NAME"]?></label>
                <div class="filter__select">
                    <div class="chosen-wrapper" data-js="custom-scroll">
                        <?//=str_replace('<select ', '<select class="chosen-select js-chosen" data-placeholder="' . $arItem["NAME"] . '"', $arItem["INPUT"])?>
                        <select class="chosen-select js-chosen" data-placeholder="Дисциплина" name="arrCompetitionsListMain_pf[DISCIPLINE]">
                            <option value="">Все</option>
                            <option value="1">Выездка</option>
                            <option value="2">Конкур</option>
                            <option value="3">Троеборье</option>
                        </select>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?/*div class="i-section__col tabs tabs_light">
        <div class="tabs__links">
            <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="video">Видео</a>
            <a href="javascript:void(0);" class="tabs__link js-tabs-link" data-group="photo">Фото</a>
        </div>
    </div*/?>
</div>
<script>
    //var ajaxScriptName = '<?=$ajaxScriptName?>';
    var competitionsSelector = '.i-section__col.filter.<?=$arParams['FILTER_NAME']?> .filter__select select.js-chosen';

    function getCompetitionsFilterParams() {
        var params = {};
        $(competitionsSelector).each(function() {
            params[$(this).attr('name')] = $(this).val();
        });
        params['FILTER_NAME'] = '<?=$arParams['FILTER_NAME']?>';

        return params;
    }
    
    function getCompetitionsResult(params) {
        $.ajax({
            method: 'GET',
            url: '<?=$ajaxScriptName?>',
            data: params
        })
        .done(function( data ) {
            $('#competitions_list-container').empty().html(data);
            $('#competitions_list-container .js-cards-slider').each(function(i,item){
                $(item).initCardsSlider();
            });
        });
    }

    $( document ).ready(function() {
        $(competitionsSelector).on('change', function() {
            var params = getCompetitionsFilterParams();
            getCompetitionsResult(params);
        });
    });
</script>