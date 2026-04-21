$(document).ready(function () {

    var currentSearchPage = 1;
    var allSearchPage = parseInt($('.js-allSearchPage').text());

    $('.js-show_more').on('click', function(){
        currentSearchPage++;
        var url = '/search/?q=' + encodeURIComponent($('.jsSearchInput').val())  +
            '&year=' + $('.js-searchYear').val() + '&discipline=' + encodeURIComponent($('.js-searchDiscipline').val());

        if($(".js-searchSort").is(':checked')){
            url = url + '&sort=asc';
        }
        url = url + '&search_page=page-' + currentSearchPage + '&show_more=Y';

        $.get(url, {}, function (html) {
            $('.js-searchItems').append(html);
            if(currentSearchPage == allSearchPage){
                $('.js-show_more').hide();
            }
        });

    });


});