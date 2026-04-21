$( document ).ready(function() {
    $( ".js-other-competition-list-block .js-card-list-more" ).on( "click", function() {
        $( ".is-hidden" ).each(function( index ) {
            if (index < 3) {
                $(this).removeClass('is-hidden');
            }
        });
        if ($( ".is-hidden" ).length === 0) {
            $(this).hide();
        }
    });
});
