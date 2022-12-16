<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "Administrator";
            $page_index = 2;
            require_once('utility/header.php');
            load_css('admin-dashboard');
            load_css('app-breadcrumbs');
            load_css('app-empty-wrapper');
            load_css('app-modal-override');
            load_css('app-drop-zone');

            auth_admin();

            $session = auth_session('session', 'sign-out');

            $user = strtoassoc('name, description, brand, price, stocks');
            $input = $user;
            $error = $user;

        ?>
    </head>
    <body>
        <?php render_sidebar('admin-dashboard') ?>
        <div class="wrapper">
            <?php render_navbar('admin-products') ?>
            <div class="inner-wrapper">
                <div class="app-breadcrumbs">
                    <a href="admin-orders" class="app-breadcrumbs-item">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fad fa-shopping-cart"></i>
                        </span>
                        Orders
                    </a>
                    <span class="app-breadcrumbs-seperator">
                        <i class="far fa-chevron-right"></i>
                    </span>
                    <a href="admin-cancel-orders" class="app-breadcrumbs-item active">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fas fa-cancel"></i>
                        </span>
                        Cancel Products
                    </a>
                </div>
                <?php

                    _builtinerror();
                    _builtinsuccess();

                    $orders = sql_get_results("SELECT * FROM orders WHERE status = 5 AND is_archived = 0");

                    if(count($orders) > 0) {
                        render_list('admin-cancel-orders');
                    } else {
                        render_empty('admin-cancel-orders');
                    }

                ?>
            </div>
        </div>
        <?php
            package_manager('script');
            load_script('admin-dashboard');
            load_script('admin-search-cancel-orders');
            load_script('admin-cancel-orders');
        ?>
    </body>
</html>