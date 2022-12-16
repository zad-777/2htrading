<!DOCTYPE html>
<html>
    <head>
        <?php
        
            $title = "H2 Trading The Best Hardware Store";
            $index = 3;
            require_once('utility/header.php');

            load_css('index-navbar');
            load_css('checkout');
            load_css('app-empty-wrapper');
            load_css('checkout-card');
            load_css('app-modal-override'); 

            auth_user();

            $session = auth_session('session', 'sign-out');
        
            if(!issetsession('product')) {
                $_SESSION['product'] = array('id' => auth_get('id', 'home'), 'quantity' => auth_get('quantity', 'home'));
            }

            $tmp = session('product');
            $product = read_product($tmp['id']);
            $is_out_of_stock = $product['stocks'] == 0 ? "th-disabled" : "";

            $user = strtoassoc('payment_method');
            $input = $user;
            $error = $user;

            if(is_post()) {
                post_validate();
                if(is_empty($error)) {
                    if($product['stocks'] == 0)
                        redirect_to('home');

                    $customer = read_user($session['id']);

                    $user['user_id'] = $session['id'];
                    $user['product_id'] = $product['id'];
                    $user['quantity'] = $tmp['quantity'];
                    $user['total'] = $product['price'] * $tmp['quantity'];
                    $user['date_ordered'] = date('m-d-y');
                    $user['date_arrived'] = "N/A";
                    $user['status'] = 1;
                    $user['unique_key'] = sql_unique_key('SELECT * FROM orders');
                    $user['is_archived'] = 0;
                    $user['image_location'] = '';
                    if($user['payment_method'] == 1) {
                        if(insert_order($user)) {
                            $is_in_cart = is_added_to_cart($session['id'], $product['id']);
                            if($is_in_cart == 'is-added') {
                                sql_execute("DELETE  FROM cart_list WHERE product_id = {$product['id']}");
                            }
                            $_SESSION['success'] = array("Added to your order, please wait for confirmation.");
                            redirect_to('orders');
                        }
                    } else {
                        if(upload_file("uploads/{$customer['unique_key']}", "image_location")) {
                            if(insert_order($user)) {
                                $is_in_cart = is_added_to_cart($session['id'], $product['id']);
                                if($is_in_cart == 'is-added') {
                                    sql_execute("DELETE  FROM cart_list WHERE product_id = {$product['id']}");
                                }
                                $_SESSION['success'] = array("Added to your order, please wait for confirmation.");
                                redirect_to('orders');
                            }
                        }
                    }
                }
            }

        ?>
    </head>
    <body>
        <?php render_navbar('user-no'); ?>
        <div class="wrapper">
            <?php
                _builtinerror();
                _builtinsuccess();
            ?>
            <br/>
            <form action="<?php echo self() ?>" method="post" enctype="multipart/form-data" class="checkout-card">
                <div class="image-wrapper">
                    <img src="<?php echo $product['image_location'] ?>">
                </div>
                <h1 class="checkout-card-title"><?php echo $product['name'] ?></h1>
                <p class="checkout-card-price"><?php echo $c_peso." ".peso_format($product['price']) ?></p>
                <p class="checkout-card-description">
                    <?php echo $product['description'] ?>
                </p>
                <hr>
                <p class="data-label">Delivery Address</p>
                <p class="data-label-value">
                    <span>
                        <i class="fas fa-map"></i>
                    </span>
                    <?php echo $session['home_address'] ?>
                </p>
                <p class="data-label">Contact Number</p>
                <p class="data-label-value">
                    <span>
                        <i class="fas fa-phone"></i>
                    </span>
                    <?php echo $session['contact_number'] ?>
                </p>
                <p class="data-label">Email Address</p>
                <p class="data-label-value">
                    <span>
                        <i class="fas fa-envelope"></i>
                    </span>
                    <?php echo $session['email_address'] ?>
                </p>
                <hr>
                <p class="data-label">PRODUCT PRICE</p>
                <p class="data-label-value">
                    <span>
                        <i class="fas fa-coin"></i>
                    </span>
                    <?php echo $c_peso." ".peso_format($product['price']) ?>
                </p>
                <p class="data-label">QUANTITY</p>
                <p class="data-label-value">
                    <span>
                        <i class="fas fa-boxes"></i>
                    </span>
                    <?php echo $tmp['quantity'] ?>pc(s)
                </p>
                <p class="data-label">OVERALL TOTAL</p>
                <p class="data-label-value">
                    <span>
                        <i class="fas fa-peso-sign"></i>
                    </span>
                    <?php echo $c_peso." ".peso_format($tmp['quantity'] * $product['price']); ?>
                </p>
                <hr>
                <p class="data-label">PAYMENT METHOD</p>
                <div class="d-inline mr-2">
                    <input type="radio" name="payment_method" id="cod" value="1">
                    <label for="cod">COD</label>
                </div>
                <div class="d-inline">
                    <input type="radio" name="payment_method" id="scan_to_pay" value="2">
                    <label for="scan_to_pay">SCAN TO PAY</label>
                </div>
                <hr>
                <img src="repository/assets/images/qr_code.jpg" class="qr_code scan-to-pay">
                <hr class="scan-to-pay">
                <div class="th-upload override checkout scan-to-pay">
                    <input type="file" name="image_location" id="thumbnail">
                    <label for="thumbnail" class="th-upload-wrapper">
                        <div class="th-upload-message">
                            <span class="icon"><i class="far fa-cloud-arrow-up"></i></span>
                            <span class="title">Upload Payment</span>
                        </div>
                    </label>
                    <img class="image-container">
                    <a type="button" class="remove-button">
                        <i class="far fa-times-circle"></i>
                    </a>
                </div>
                <hr class="scan-to-pay">
                <input type="submit" class="th-button th-color-blue btn-block <?php echo $is_out_of_stock ?>" value="CHECKOUT">
            </form>
        </div>
        <footer>
            <p>2-H Trading Allright Reserved &copy; 2022</p>
            <div>
                <a href="#">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </footer>
        <?php 
            package_manager('script');
            load_script('check-out');
        ?>
    </body>
</html>