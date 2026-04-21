<? if($arParams['SHOW_MORE_MODE'] == 'N'): ?>
<section class="i-section i-section_single">
<div class="g-wrap">
    <span class="js-allSearchPage" style="display: none;"><?= !empty($arResult['NAV']) ? $arResult['NAV']->getPageCount() : 0 ?></span>
    <form class="i-section__header i-section__header_wrap" action="<?= $arParams['ACTION_PAGE'] ?>" method="get">
        <div class="search-form">
            <input class="i-input search-form__input jsSearchInput" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>"  placeholder="Введите слово для поиска"/>
            <button class="i-button"><?=GetMessage("SEARCH_GO")?></button>
        </div>
        <h2 class="i-section__title">Поиск</h2>
        <div class="i-section__col filter">
            <? if(!empty($arResult['YEAR_LIST'])): ?>
                <div class="filter__item">
                    <label class="filter__label" for="year">год</label>
                    <div class="filter__select">
                        <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                            <? $currentYear = $arResult["REQUEST"]["YEAR"]; ?>
                            <select class="chosen-select js-chosen js-searchYear" id="year" name="year" data-placeholder="год" onchange="this.form.submit()">
                                <option value="" <? if(empty($currentYear)): ?>selected="selected"<? endif; ?>>Все</option>
                                <? foreach($arResult['YEAR_LIST'] as $year): ?>
                                    <option value="<?= $year ?>" <? if($year == $currentYear): ?> selected="selected" <? endif; ?>><?= $year ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            <? endif ?>
            <? if(!empty($arResult['DISCIPLINE'])): ?>
                <div class="filter__item">
                    <label class="filter__label" for="dsljhgljhg987">Дисциплина</label>
                    <div class="filter__select">
                        <div class="chosen-wrapper chosen-wrapper_dark" data-js="custom-scroll">
                            <? $curDiscipline = $arResult["REQUEST"]["DISCIPLINE"];  ?>
                            <select class="chosen-select js-chosen js-searchDiscipline" name="discipline" id="dsljhgljhg987" data-placeholder="дисциплина" onchange="this.form.submit()">
                                <option value="" <? if(empty($curDiscipline)): ?>selected="selected"<? endif; ?>>Все</option>
                                <? foreach($arResult['DISCIPLINE'] as $discipline): ?>
                                    <option value="<?= $discipline ?>" <? if($discipline == $curDiscipline): ?>selected="selected"<? endif; ?>><?=$discipline ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            <? endif ?>
            <div class="filter__item filter__item_sort">
                <label class="checkbox checkbox_sort">
							<span class="checkbox__box">
								<input type="checkbox"
                                       class="checkbox__control js-searchSort" name="sort" value="asc" <? if($arResult["REQUEST"]["SORT"] == 'asc'): ?>checked <? endif ?> onchange="this.form.submit();">
								<span class="checkbox__content">дата</span>
								<span class="checkbox__indicator  checkbox__indicator_reverse">
									<svg class="i-svg" xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15" fill="none">
										<path d="M1 5.0625L5.0625 1L9.125 5.0625" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M5.0625 1L5.0625 14" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
								</span>
							</span>
                </label>
            </div>
        </div>
    </form>
<? endif ?>
    <?if($arResult["REQUEST"]["QUERY"] === false):?>
    <?elseif($arResult["ERROR_TEXT"]!=0):?>
        <p><?=GetMessage("SEARCH_ERROR")?></p>
        <?ShowError($arResult["ERROR_TEXT"]);?>
        <p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
    <?elseif(count($arResult["SEARCH"])>0):?>
        <? if($arParams['SHOW_MORE_MODE'] == 'N'): ?>
        <div class="i-section__content program-list js-searchItems">
         <? endif ?>
                <?php foreach ($arResult['SEARCH'] as $arItem) { ?>
                    <a href="<?=$arItem['URL']?>" class="program">
                        <?
                        $preview = false;
                        if ($arItem['ADDITIONAL_FIELDS']['PICTURE']) {
                            $preview = \CFile::ResizeImageGet(
                                $arItem['ADDITIONAL_FIELDS']['PICTURE'],
                                ['width' => 315, 'height' => 213],
                                BX_RESIZE_IMAGE_EXACT
                            );
                        } elseif(!empty($arItem['ADDITIONAL_FIELDS']['DEFAULT_PICTURE'])) {
                            $preview = [
                                'src' => $arItem['ADDITIONAL_FIELDS']['DEFAULT_PICTURE']
                            ];
                        }

                        ?>
                        <div class="program__img" <? if(!empty($preview['src'])): ?> style="background-image:url(<?=$preview['src']?>)" <? endif ?>></div>
                        <div class="program__content">
                            <div class="program__header">
                                <p class="program__address"><?=$arItem['ADDITIONAL_FIELDS']['PLACE']?></p>
                                <p class="program__date program__date_big"><?= $arItem['DATE'] ?></p>
                            </div>
                            <p class="program__title program__title_sm"><?=$arItem['TITLE']?></p>
                            <p class="program__theme"><? if(!empty($arItem['ADDITIONAL_FIELDS']['COMPETITION'])):?>
                                <span>Программа:</span> <?= $arItem['ADDITIONAL_FIELDS']['COMPETITION'] ?><? endif; ?>
                            </p>
                            <p class="program__person capitalized"><? if($arItem['FILE_NAME']): ?><span>Спортсмен:</span> <?= $arItem['FILE_NAME'] ?><? endif ?></p>
                            <div class="program__footer program__footer_left">
                                <div class="program__info">
                                    <p class="program__info-title">тип:</p>
                                    <p><?php
                                        if($arItem['CONTENT_TYPE'] == 'video' || $arItem['CONTENT_TYPE'] == 'section'){
                                            echo  'Соревнование';
                                        }
                                        if($arItem['CONTENT_TYPE'] == 'element'){
                                            if($arItem['IBLOCK_CODE'] == 'broadcasts'){
                                                echo 'Трансляция';
                                            }
                                            if($arItem['IBLOCK_CODE'] == 'programs'){
                                                echo 'Программа';
                                            }
                                            if($arItem['IBLOCK_CODE'] == 'events'){
                                                echo 'Событие';
                                            }
                                        }
                                        ?></p>
                                </div>
                                <div class="program__info">
                                    <p class="program__info-title">дисциплина:</p>
                                    <p><?= $arItem['DISCIPLINE'] ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
        <? if($arParams['SHOW_MORE_MODE'] == 'N'): ?>
        </div>
        <? if($arResult['NAV']->getPageCount() > 1): ?>
            <div class="photo__footer">
                <a href="javascript:void(0);" class="i-button js-show_more">Показать ещё</a>
            </div>
            <? /*$APPLICATION->IncludeComponent('bitrix:main.pagenavigation', '', [
                'NAV_OBJECT' => $arResult['NAV'],
            ]); */?>
        <? endif ?>
        <? endif ?>
    <?else:?>
        <p><?=GetMessage("SEARCH_NOTHING_TO_FOUND");?></p>
    <?endif;?>
<? if($arParams['SHOW_MORE_MODE'] == 'N'): ?>
</div>
</section>
<? endif; ?>