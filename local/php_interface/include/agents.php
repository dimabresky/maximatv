<?php
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserRegisterHandler");
//AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

function OnBeforeUserRegisterHandler(&$arFields) {
    $arUser = \CUser::GetByID($arFields['ID'])->Fetch();
    if ($arUser['ACTIVE'] !== 'Y' && $arFields['ACTIVE'] === 'Y') {
        $arEventFields= array(
            "LOGIN" => $arFields["LOGIN"],
            "NAME" => $arFields["NAME"],
            "LAST_NAME" => $arFields["LAST_NAME"],
            "EMAIL" => $arFields["EMAIL"],
        );
        CEvent::Send("NEW_USER_INFO", SITE_ID, $arEventFields, "N", 31);
    }
    $arFields['EMAIL'] = $arFields['LOGIN'];

    return $arFields;
}

/*function OnAfterUserRegisterHandler(&$arFields) {
    $arEventFields= array(
        "LOGIN" => $arFields["LOGIN"],
        "NAME" => $arFields["NAME"],
        "LAST_NAME" => $arFields["LAST_NAME"],
        "EMAIL" => $arFields["EMAIL"],
    );
    CEvent::Send("NEW_USER_INFO", SITE_ID, $arEventFields, "N", 31);
}*/