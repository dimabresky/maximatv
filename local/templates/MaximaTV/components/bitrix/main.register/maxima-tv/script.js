$( document ).ready(function() {
   /* $('.js-register-login').on('blur', function() {
        $('#js-register-email').val($(this).val());
    });*/
   $(document).on('keyup', '.js-register-login', function () {
       $('#js-register-email').val($(this).val());
   })
});