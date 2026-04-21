

$(document).ready(function() {

    function clearBasketPage(){
        $('.js-cart-page-item').remove();
        $('.js-cart-page-footer').after('<div class="cart__item-row">Ваша корзина пуста</div>');
        $('.js-cart-page-footer').remove();
        $('.js-clean-cart-page').remove();
        $('.js-cart-page-count').remove();
    }

    if($('.js-cart-img').length){
        $('.js-cart-img').fancybox({
            //loop: true,
           //hash: false,
            baseTpl:'<div class="fancybox-container" role="dialog" tabindex="-1">' +
            '<div class="fancybox-bg"></div>' +
            '<div class="fancybox-inner">' +
            '<div class="fancybox-infobar"><span data-fancybox-index></span>&nbsp;/&nbsp;<span data-fancybox-count></span></div>' +
            '<div class="fancybox-toolbar">{{buttons}}</div>' +
            '<div class="fancybox-navigation">{{arrows}}</div>' +
            '<div class="fancybox-stage">' +
            '</div>' +
            '</div></div>'

        });
    }

    $('.js-cart-page-btn').click(function(){
        id =  $(this).data('id');
        $.get(
            '/local/ajax/basket_page.php',
            {
                action_type: 'basket_page',
                action: 'remove',
                id: id
            },
            function (data) {
                if (data) {
                  data = JSON.parse(data);

                  if(data.ITEMS.length > 0) {
                      $('.js-all-cart-sum').text(data.SUM);
                      $('.js-cart-page-item').each(function(){
                            var item = $(this);
                            var curId = $(this).data('id');
                            var notExist = true;
                            for ( var i in data.ITEMS){
                                if(data.ITEMS[i].ID == curId){
                                 item.find('.js-photo-price').text(data.ITEMS[i].PRICE);
                                 notExist = false;
                                }
                            }
                            if(notExist){
                                item.remove();
;                            }
                      });
                  }
                  else {
                      clearBasketPage();
                  }
                }
            });
    });

    $('.js-clean-cart-page').click(function(){
        $.get(
            '/local/ajax/basket_page.php',
            {
                action_type: 'basket_page',
                action: 'clear',
            },
            function (data) {
                if (data) {
                    clearBasketPage();
                }
            });
    });
});



