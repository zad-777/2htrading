var searchBar = $('.app-navbar-searchbar-input');
var searchResult = $('.search-result');
var pageView = $('[name=page_view]');
var pagePrevious = $('.page-previous');
var pageNext = $('.page-next');


init();

function init() {
    $.get('admin-customers-filter.php', {view : pageView.val(), name : searchBar.val()}, function(data) {
        searchResult.html(data);
    });
}
searchBar.on('input', function() {
    $.get('admin-customers-filter.php', {view : pageView.val(), name : searchBar.val()}, function(data) {
        searchResult.html(data);
    });
});
pageView.on('change', function() {
    $.get('admin-customers-filter.php', {
        view : pageView.val(),
        req_action : 'reset',
    }, function(data) {
        searchResult.html(data);
    });
});
pagePrevious.on('click', function() {
    $.get('admin-customers-filter.php', {
        view : pageView.val(),
        name : searchBar.val(),
        req_action : 'previous'
    }, function(data) {
        searchResult.html(data);
    });
});
pageNext.on('click', function() {
    $.get('admin-customers-filter.php', {
        view : pageView.val(),
        name : searchBar.val(),
        req_action : 'next'
    }, function(data) {
        searchResult.html(data);
    });
});