$(document).ready(function() {
    $(document).on('click', '.view-btn', function() {
        var self = $(this);
        var order = JSON.parse(self.attr('data'));
        var viewModal = $('.view-modal');

        var imageView = viewModal.find('.payment-image');

        imageView.attr('src', order.image_location);

    });
    $(document).on('click', '.update-btn', function() {
        var self = $(this);
        var order = JSON.parse(self.attr('data'));

        var updateModal = $('.update-modal');

        var orderID = updateModal.find('[name=id]');
        var orderName = updateModal.find('[name=name]');
        var orderStatus = updateModal.find('[name=status]');

        orderID.val(order.id);
        orderName.val(order.product_name);
        orderStatus.val(order.status);

    });
});