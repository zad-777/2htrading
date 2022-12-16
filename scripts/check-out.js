$(document).ready(function() {
    $('[name=payment_method]').each(function() {
        var self = $(this);
        var scanToPay = $('.scan-to-pay');
        self.change(function() {
            if(self.val() == 2) {
                scanToPay.addClass('shown');
            } else {
                scanToPay.removeClass('shown');
            }
        });
    });
});