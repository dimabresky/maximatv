<?php
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/log.txt");
session_set_cookie_params(43200);

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if(file_exists(__DIR__."/include/agents.php")) {
    include_once(__DIR__."/include/agents.php");
}
if(file_exists(__DIR__."/include/constants.php")) {
    include_once(__DIR__."/include/constants.php");
}

if (is_file(__DIR__.'/vendor/autoload.php')) require_once __DIR__.'/vendor/autoload.php';


if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y") {
    function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='') {
        if(CModule::IncludeModule("webprostor.smtp")) {
            $smtp = new CWebprostorSmtp("s1");
            $result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);

            if($result) {
                return true;
            } else {
                return false;
            }
        }
    }
}

AddEventHandler('main', 'OnAfterUserAuthorize', [new CUserLoginChecker(), 'saveSID']);

// класс с обработчиками
class CUserLoginChecker
{
    public function saveSID()
    {
        global $USER;
        $userId = $USER->GetID();
        if ($userId && $userId > 0) {
            $USER->Update($userId, [
               // 'UF_SESSION_ID' => $USER->GetSessionHash()
			   'UF_SESSION_ID' => session_id()
            ]);
        }
    }
}