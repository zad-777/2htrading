$(document).ready(function() {
    $(document).on('click', '.view-btn', function() {
        var self = $(this);
        var order = JSON.parse(self.attr('data'));
        var viewModal = $('.view-modal');

        var imageView = viewModal.find('.payment-image');

        imageView.attr('src', order.image_location);

    });
    $(document).on('click', '.cancel-btn', function() {
        var self = $(this);
        var order = JSON.parse(self.attr('data'));

        var cancelModal = $('.cancel-modal');

        var orderID = cancelModal.find('[name=id]');
        var orderName = cancelModal.find('.product-name');

        orderID.val(order.id);
        orderName.html(order.product_name);

    });
});