function BackendGallery(opts) {
    var self = this;
    self.opts = opts;

    self.currentId = undefined;
    self.firstInit = true;

    self.gallery = $('.js-gallery');
    self.cartBtnClassName = 'js-gallery-cart';
    self.contentSelector = '.fancybox-content';

    self.init = function () {

        self.gallery.fancybox({
            loop: true,
            hash: false,
            afterShow: function (instance, slide) {
                self.currentId = slide.opts.$orig.data('id');
                if (self.firstInit)
                    self.initCartButton();

                $(document).trigger('afterShowGallery', [$('[' + 'data-id="' + self.currentId + '"' + ']')]);
                $('.' + self.cartBtnClassName).addClass('is-active');
            },
            afterClose: function () {
                self.firstInit = true;
            },
            baseTpl: opts.template
        });
    };

    self.initCartButton = function () {
        self.firstInit = false;
        $('.' + self.cartBtnClassName).on('click', function (e) {
            e.preventDefault();
            $(document).trigger('addToCartGalleryImage', [self.currentId]);
        });
    };

    self.init();
}



function PhotoUploadPage(opts) {
    var self = this;
    self.opts = opts;

    self.isUploading = false;
    self.offset = opts.list.data('offset') ? opts.list.data('offset') : 200;

    self.init = function() {
        $(window).on('scroll', function(){

            if(self.isUploading)
                return false;
            if((window.scrollY + window.innerHeight) >= opts.list.innerHeight() - self.offset) {
                self.isUploading = true;
                var nextPage = self.opts.page + 1;
                $('.photo-list__preloader').addClass('is-active');
                $.get('/local/ajax/photo_list.php',
                    {
                        photo_page: nextPage,
                        photo_type: self.opts.type,
                        photo_id: self.opts.id
                    },
                    function (data){
                        self.opts.page++;
                        opts.wrap.append(data);
                        $('.js-gallery').data('gallery', new BackendGallery({
                            template: MaximaTvGalleryTpl
                        }));

                        if(!$('.js-end-photo-list').length){
                            self.isUploading = false;
                        }

                        $('.photo-list__preloader').removeClass('is-active');
                    }
                );
            }
        })
    };
    self.init();
}



$( document ).ready(function() {

    if($('.js-gallery').length){
        $('.js-gallery').data('gallery', new BackendGallery({
            template: MaximaTvGalleryTpl
        }));
    }

    if($('.js-end-photo-list').length > 0){
        return false;
    }

    var photoList = $('.js-photo-list');

    if(!photoList.length)
        return false;

    photoList.data('photoList', new PhotoUploadPage({
        list: photoList,
        wrap: photoList.find('.js-photo-wrap'),
        type: photoList.data('type'),
        id: photoList.data('id'),
        block: '.js-end-photo-list',
        page: 1
    }))

    $(document).on('addToCartGalleryImage', function(event, id) {
        if(id == '-1'){
                return;
        }
        if($('.js-gallery-cart').hasClass('js-gallery-cart-to-basket')){
            window.location.href = '/lk/cart/';
            return;
        }

        $.get(
            '/local/ajax/basket_heder.php',
            {
                action_type: 'basket',
                action: 'add',
                type: 'photo',
                id: id
            },
            function (html) {
                if (html) {

                    if($(document).width() >= 768) {
                        var currWrap = $('.fancybox-slide--current');
                        var currContent = currWrap.find('.fancybox-content');
                        var img = currWrap.find('.fancybox-image').clone();
                        var btn = $('.js-cart-btn');

                        var X = btn.offset().left - currContent.offset().left;

                        img.toggleClass('fancybox-image fancy-animate-img');
                        currContent.append(img);

                        setTimeout(function () {
                            img.addClass('is-alive')
                        }, 200);

                        img.animate({
                            left: X + 'px',
                            top: -img.height() + 'px'
                        }, 100);

                        btn.addClass('is-alive');

                        setTimeout(function () {
                            img.remove();
                            btn.removeClass('is-alive');
                        }, 2000);
                    }

                    $('.js-cart-header').html(html);
                    $('.js-cart-header').initHeaderCart();
                    $('.js-cart-btn').addClass('is-alive');
                    setTimeout(function () {
                         $('.js-cart-btn').removeClass('is-alive');
                    }, 2000);
                    $('.js-info-message').data('message').show();
                    $('.js-gallery-cart').text('Перейти в корзину');
                    $('.js-gallery-cart').addClass('js-gallery-cart-to-basket');
                    $('a[data-id="'+ id +'"]').attr('data-basket', 1);
                }
            });

    })


    $(document).on('afterShowGallery', function (e, galleryItem) {
        if(galleryItem.data('id') == '-1'){
            return;
        }
        if(galleryItem.attr('data-basket') == '1'){
            $('.js-gallery-cart').text('Перейти в корзину');
            $('.js-gallery-cart').addClass('js-gallery-cart-to-basket');
        }
        else{
            $('.js-gallery-cart').text('Добавить в корзину');
            $('.js-gallery-cart').removeClass('js-gallery-cart-to-basket');
        }
    });

});



