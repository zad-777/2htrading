<!DOCTYPE html>
<html>
    <head>
        <?php
        
            $title = "H2 Trading The Best Hardware Store";
            $index = 2;
            require_once('utility/header.php');

            load_css('index-navbar');
            load_css('home');
            load_css('app-empty-wrapper');
            load_css('app-cart');

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
            }

        ?>
    </head>
    <body>
        <?php render_navbar('user-no'); ?>
        <div class="wrapper">
            <?php
                _builtinerror();
                _builtinsuccess();

                $cart_list = sql_get_results("SELECT * FROM cart_list WHERE user_id = {$session['id']} AND is_archived = 0 ORDER BY id DESC");

            ?>
            <h1 class="page-title">My Cart</h1>
            <p class="page-meta">You have <b><?php echo count($cart_list) ?></b> product(s) in your cart.</p>
            <br/>
            <div class="row">
            <?php
                if(count($cart_list) > 0) {
                    render_list('user-cart');
                } else {
                    render_empty('user-cart');
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
        <?php 
            package_manager('script');
            load_script('home-search-products');
        ?>
    </body>
</html>