$(document).ready(function(){

    $('.js-later').click(function(){
        var date = new Date();
        date.setTime(date.getTime()+(24 * 60 * 60 * 1000));
        var expires = "; expires="+date.toGMTString();
        document.cookie = "_maxima_wait_info_banner=Y"+expires+"; path=/";

        $('.js-info-banner').removeClass('is-active');
    });

    $('.js-favorite').click(function(){
        //вызываем чуть позже, чтобы успел отработать обработчик из маркапа (флаг активности)
        setTimeout(function(btn) {
            var uId = btn.data('uid'),
                eId = btn.data('eid');
            if (btn.hasClass('is-active')) {
                $.ajax({
                    method: 'POST',
                    url: '/local/ajax/favorites.php',
                    data: {'a':'add','uid':uId,'eid':eId}
                });
            } else {
                $.ajax({
                    method: 'POST',
                    url: '/local/ajax/favorites.php',
                    data: {'a':'del','uid':uId,'eid':eId}
                });
            }
        }, 100, $(this));
    });

    $('.js-del-favorites').click(function(){
        var uId = $(this).data('uid'),
            eIds = [];

        $('.js-check-item:checked').each(function( index ) {
            eIds.push($(this).data('eid'));
        });

        $.ajax({
            method: 'POST',
            url: '/local/ajax/favorites.php',
            data: {'a':'del','uid':uId,'eid':eIds}
        })
        .done(function( data ) {
            window.location.reload(true);
        });
    });

});





