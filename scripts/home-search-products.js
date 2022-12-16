var searchBar = $('.app-navbar-searchbar-input');
var searchResult = $('.search-result');
init();

function init() {
    $.get('home-products-filter.php', {name : searchBar.val()}, function(data) {
        searchResult.html(data);
    });
}
searchBar.on('input', function() {
    $.get('home-products-filter.php', {name : searchBar.val()}, function(data) {
        searchResult.html(data);
    });
});