<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('portal.options', array(
        '\GazFond\Portal\Options\Options' => 'lib/options.php'
    )
);