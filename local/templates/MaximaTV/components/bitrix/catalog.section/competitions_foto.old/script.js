$( document ).ready(function() {
    $( ".js-show_more" ).on( "click", function() {
        $( ".hidden-photo" ).each(function( index ) {
            if (index < 5) {
                $(this).removeClass('hidden-photo');
            }
        });
        if ($( ".hidden-photo" ).length === 0) {
            $(this).hide();
        }
    });
});