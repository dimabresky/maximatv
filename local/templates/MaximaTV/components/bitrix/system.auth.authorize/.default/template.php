<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?php if ($_GET['confirm_registration'] !== 'yes') { ?>
    <p style="font-size: larger; color: red;"><br/>
    Для доступа к данной странице необходимо
    <a href="javascript:void(0);" id="auth_open" data-remodal-target="login_register" style="color: blue;">
        <span>авторизоваться</span></a>.
    </p>
<?php } ?>