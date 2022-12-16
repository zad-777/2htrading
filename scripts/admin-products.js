$(document).ready(function() {
    $(document).on('click', '.delete-btn', function() {
        var self = $(this);
        var product = JSON.parse(self.attr('data'));

        var deleteModal = $('.delete-modal');

        var productID = deleteModal.find('[name=id]');
        var productName = deleteModal.find('.product-name');

        productID.val(product.id);
        productName.html(product.name);

    });
    $(document).on('click', '.update-btn', function() {
        var self = $(this);
        var product = JSON.parse(self.attr('data'));

        var updateModal = $('.update-modal');

        var productID = updateModal.find('[name=id]');
        var productName = updateModal.find('[name=name]');
        var productPrice = updateModal.find('[name=price]');
        var productBrand = updateModal.find('[name=brand]');
        var productStocks = updateModal.find('[name=stocks]');
        var productDescription = updateModal.find('[name=description]');

        productID.val(product.id);
        productName.val(product.name);
        productPrice.val(product.price);
        productBrand.val(product.brand);
        productStocks.val(product.stocks);
        productDescription.val(product.description);

    });
});