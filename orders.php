<!DOCTYPE html>
<html>
    <head>
        <?php
        
            $title = "H2 Trading The Best Hardware Store";
            $index = 1;
            require_once('utility/header.php');

            load_css('index-navbar');
            load_css('home');
            load_css('app-empty-wrapper');
            load_css('app-orders');

            auth_user();
            
            $session = auth_session('session', 'sign-out');

            $user = strtoassoc('id');
            $input = $user;
            $error = $user;

            switch(reqaction()) {
                case 'add_to_cart':
                    get_validate();
                    if(is_empty($error)) {
                        $product = read_product($user['id']);
                        $user['user_id'] = $session['id'];
                        $user['product_id'] = $user['id'];
                        $user['unique_key'] = sql_unique_key('cart_list');
                        $user['quantity'] = 1;
                        $user['is_archived'] = 0;
                        if(insert_cart($user)) {
                            $_SESSION['success'] = array("<b>{$product['name']}</b> successfully added to your cart.");
                            redirect_to("home");
                        }
                    }
                break;
                case 'cancel_order':
                    post_validate();
                    if(is_empty($error)) {
                        $order = read_order($user['id']);
                        $product = read_product($order['product_id']);
                        if(delete_order($user['id'])) {
                            $_SESSION['success'] = array("<b>{$product['name']}</b> order cancelled.");
                            redirect_to("orders");
                        }
                    }
                break;
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
            <div class="order-card">
                <?php
                    $orders = sql_get_results("SELECT * FROM orders WHERE user_id = {$session['id']} AND is_archived = 0 ORDER BY id DESC");
                ?>
                <h1 class="page-title">My Orders</h1>
                <p class="page-meta">You have <b><?php echo count($orders) ?></b> product(s) in your orders.</p>
                <hr/>
                <?php

                    if(count($orders) > 0) {
                        render_list('user-orders');
                    } else {
                        render_empty('user-orders');
                    }
                ?>
            </div>
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
        <form action="<?php echo self() ?>" method="post" class="th-modal view-modal" id="viewPayment">
            <input type="hidden" name="req_action" value="create">
            <div class="th-modal-content" style="width: 512px">
                <div class="th-modal-content-header">
                    <h1 class="th-modal-content-header-title">
                        View Payment
                    </h1>
                </div>
                <div class="th-modal-content-body">
                    <img src="" class="payment-image">
                </div>
                <div class="th-modal-content-footer">
                    <a type="button" class="th-modal-content-footer-close">
                        Close
                    </a>
                </div>
            </div>
        </form>
        <form action="<?php echo self() ?>" method="post" class="th-modal cancel-modal" id="cancelOrder">
            <input type="hidden" name="req_action" value="cancel_order">
            <input type="hidden" name="id" value="">
            <div class="th-modal-content" style="width: 512px">
                <div class="th-modal-content-header">
                    <h1 class="th-modal-content-header-title">
                        Cancel Order
                    </h1>
                </div>
                <div class="th-modal-content-body">
                    <p>Do you want to cancel <b class="product-name"></b>?</p>
                </div>
                <div class="th-modal-content-footer">
                    <a type="button" class="th-modal-content-footer-close">
                        Close
                    </a>
                    <button type="submit" class="th-modal-content-footer-confirm">
                        Confirm
                    </button>
                </div>
            </div>
        </form>
        <?php 
            package_manager('script');
            load_script('orders');
        ?>
    </body>
</html>