var dashboard = $('.sidebar');
var toggler = dashboard.find('.sidebar-toggler');
var searchbar = $('.searchbar');

toggler.on('click', function() {
    dashboard.toggleClass('minimize');
    var isMinimize = dashboard.hasClass('minimize') ? "true" : "false";
    $.get('config-controller.php', {is_minimize : isMinimize});
});


if(searchbar.length > 0) {
    var searchResult = $('.searchbar-result-list');
    searchbar.on('input', function() {
        $.get('search-students.php', {search : searchbar.val()}, function(data) {
            searchResult.html(data);
        });
    });
}

var photoViewer = $('.photo-viewer');
photoViewer.each(function() {
    var self = $(this);
    var attr = self.attr('open-image-viewer');
    self.on('click', function() {
        var modalID = '#' + attr;
        console.log(modalID);
        var modalViewer = $(modalID);
        if(modalViewer.length > 0)
            $('body').css('overflow', 'hidden');
            modalViewer.addClass('shown'); 
    });
});