<!DOCTYPE html>
<html>
    <head>
        <?php
        
            $title = "H2 Trading The Best Hardware Store";
            require_once('utility/header.php');

            load_css('index-navbar');
            load_css('home');
            load_css('app-empty-wrapper');

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
        <?php render_navbar('user'); ?>
        <div class="wrapper">
            <?php
                _builtinerror();
                _builtinsuccess();
            ?>
            <div class="row">
            <?php
                $products = sql_get_results("SELECT * FROM products WHERE is_archived = 0");
                if(count($products) > 0) {
                    render_list('user-products');
                } else {
                    render_empty('user-products');
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