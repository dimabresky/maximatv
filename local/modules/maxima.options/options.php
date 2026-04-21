<?
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

$sModuleId = "maxima.options";
CModule::IncludeModule($sModuleId);

Loc::loadMessages(__FILE__);
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 **/

if ($USER->IsAdmin()):

    /*
     * Основные настройки
     */
    $arAllOptions['INFO'] = [
        ['NAME' => "mxm_info_general_phone", 'TEXT' =>  Loc::getMessage("MXM_GENERAL_PHONE_LABEL"), "TYPE" =>  ['VALUE' => "text",'SIZE' =>  60]],
		['NAME' => "mxm_info_general_email", 'TEXT' =>  Loc::getMessage("MXM_GENERAL_EMAIL_LABEL"), "TYPE" =>  ['VALUE' => "text",'SIZE' =>  60]],
        ['NAME' =>"mxm_info_copyright", 'TEXT' => Loc::getMessage("MXM_CORYRIGHT_LABEL"), "TYPE" => ['VALUE' => "text",'SIZE' =>  60]],
        ['NAME' =>"mxm_info_license",'TEXT'=> Loc::getMessage("MXM_LICENSE_LABEL"),  "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
        ['NAME' =>"mxm_info_developer",'TEXT'=> Loc::getMessage("MXM_SITE_DEVELOPER_LABEL"),  "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
		['NAME' => "mxm_info_general_address", 'TEXT' =>  Loc::getMessage("MXM_GENERAL_ADDRESS_LABEL"), "TYPE" =>  ['VALUE' => "text",'SIZE' =>  100]],
    ];
    $arAllOptions['SOCIAL']    = [
        ['NAME' =>"mxm_social_fb", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_FB"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
        ['NAME' =>"mxm_social_vk", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_VK"),"TYPE" => ['VALUE' => "text", 'SIZE' =>  60]],
        ['NAME' =>"mxm_social_ig", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_IG"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
		['NAME' =>"mxm_social_yt", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_YT"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
        ];
    $arAllOptions['SOCIAL_SORT']    = [
        ['NAME' =>"mxm_social_sort_fb", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_SORT_FB"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
        ['NAME' =>"mxm_social_sort_vk", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_SORT_VK"),"TYPE" => ['VALUE' => "text", 'SIZE' =>  60]],
        ['NAME' =>"mxm_social_sort_ig", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_SORT_IG"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
		['NAME' =>"mxm_social_sort_yt", 'TEXT'=>Loc::getMessage("MXM_SOCIAL_SORT_YT"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  60]],
    ];
    $arAllOptions['SUBSCRIPTION_CHECK']    = [
        ['NAME' =>"mxm_subscription_check_1", 'TEXT'=>Loc::getMessage("MXM_SUBSCRIPTION_CHECK_1"), "TYPE" =>['VALUE' => "text", 'SIZE' =>  10]],
        ['NAME' =>"mxm_subscription_check_2", 'TEXT'=>Loc::getMessage("MXM_SUBSCRIPTION_CHECK_2"), "TYPE" => ['VALUE' => "text", 'SIZE' =>  10]],
    ];



    $aTabs = [
        [
            "DIV" => "edit1",
            "TAB" => Loc::getMessage("MAIN_TAB_SET"),
            "ICON" => "main_settings",
            "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET")
        ],
    ];

    $tabControl = new CAdminTabControl("tabControl", $aTabs);
    $request = Application::getInstance()->getContext()->getRequest();
    if ($request->isPost() && $request->get('Update') . $request->get('Apply') . $request->get('RestoreDefaults') != '' && check_bitrix_sessid()) {
        if ($request->get('RestoreDefaults') != "") {
            COption::RemoveOption($sModuleId);
        } else {
            switch ($request->get('tabControl_active_tab')){
                case "edit1":
                    foreach ($arAllOptions as $type => $arOptions) {
                        foreach ($arOptions as $arOption){
                            $sName = $arOption['NAME'];
                            $sValue = trim($_REQUEST[$sName], " \t\n\r");
                            $sType = $arOption['TYPE']['VALUE'];
                            COption::SetOptionString($sModuleId, $sName, $sValue, $arOption['TEXT']);
                        }

                    }
                    break;
            }
        }

        if ($_REQUEST["back_url_settings"] != "") {
            if ($request->get('Update') != "") {
                LocalRedirect($_REQUEST["back_url_settings"]);
            }

            $sReturnUrl = $_GET["return_url"] ? urlencode($_GET["return_url"]) : "";
            LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($sModuleId) . "&lang=" . urlencode(LANGUAGE_ID) . "&back_url_settings=" . $sReturnUrl . "&" .                $tabControl->ActiveTabParam());
        } else {
            LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($sModuleId) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $tabControl->ActiveTabParam());
        }
    }
    ?>
    <form method="post"
          action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($sModuleId) ?>&amp;lang=<?= LANGUAGE_ID ?>">
        <?= bitrix_sessid_post(); ?>
        <?
        $tabControl->Begin();
        $tabControl->BeginNextTab();?>
        <?foreach ($arAllOptions as $type => $arOptions):?>
            <tr class="heading">
                <td colspan="2"><b><?= Loc::getMessage('MXM_'.$type.'_OPTIONS')?></b></td>
            </tr>
                <? foreach ($arOptions as $arOption):?>
                <?$note = $arOption['NOTE'] ?: null; ?>
                <? $oValue = COption::GetOptionString($sModuleId, $arOption['NAME']); ?>
                <tr>
                    <td width="40%">
                        <label for="<?= htmlspecialcharsbx($arOption['NAME']) ?>"><?= $arOption["TEXT"] ?>
                            <? if ($note !== null): ?>
                                <span class="required"><sup><?= $note ?></sup></span>
                            <? endif; ?>
                            :</label>
                    </td>
                    <td width="60%">
                        <input type="<?=$arOption['TYPE']['VALUE'] ?>" size="<?= $arOption['TYPE']["SIZE"] ?>" maxlength="255"
                               value="<? echo htmlspecialcharsbx($oValue) ?>"
                               name="<? echo htmlspecialcharsbx($arOption['NAME']) ?>"
                               id="<? echo htmlspecialcharsbx($arOption['NAME']) ?>">

                    </td>
                </tr>
            <? endforeach ?>
        <? endforeach ?>
        <? $tabControl->Buttons(); ?>
        <input type="submit" name="Update" value="<?= Loc::getMessage("MAIN_SAVE") ?>" title="<?= Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>">
        <input type="submit" name="Apply" value="<?= Loc::getMessage("MAIN_OPT_APPLY") ?>"
               title="<?= GetMessage("MAIN_OPT_APPLY_TITLE") ?>">
        <? if ($_REQUEST["back_url_settings"] != ""):?>
            <input type="button" name="Cancel" value="<?= Loc::getMessage("MAIN_OPT_CANCEL") ?>"
                   title="<?= GetMessage("MAIN_OPT_CANCEL_TITLE") ?>"
                   onclick="window.location='<? echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"])) ?>'">
            <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($_REQUEST["back_url_settings"]) ?>">
        <? endif ?>
        <input type="submit" name="RestoreDefaults" title="<?= Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
               onclick="return confirm('<?= AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING")) ?>')"
               value="<?= Loc::getMessage("MAIN_RESTORE_DEFAULTS") ?>">
        <? $tabControl->End(); ?>
    </form>
<? endif; ?>