$(document).ready(function(){

    $(document).on('click', '.js-download-selected-photo', function () {
        if($('.photo-preview__checkbox .js-check-item:checked').length > 0 ){
            $(this).parents('form').submit();
        }
    });

});