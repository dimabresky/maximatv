$( document ).ready(function() {
    var queryString = window.location.search.slice(1);
    if (queryString) {
        queryString = queryString.split('#')[0];
        var arr = queryString.split('&');
        arr.forEach(function (item, i, arr) {
            var a = item.split('=');
            var paramName = a[0];
            var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

            if (paramName === 'foto' && paramValue === 'Y') {
                $('a[data-group="photo"]').click();
            }
        });
    }
});