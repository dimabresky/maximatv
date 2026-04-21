$(document).ready(function () {

    $('.js-cart-header').on('click', '.js-cart-item__delete', function () {
        id =  $(this).data('id');
        $.get(
            '/local/ajax/basket_heder.php',
            {
                action_type: 'basket',
                action: 'remove',
                id: id
            },
            function (html) {
                if (html) {
                    $('.js-cart-header').html(html);
                    $('.js-cart-header').initHeaderCart();
                    $('.js-cart-header .js-cart-btn').trigger('click');
                }
            });
    });

});

