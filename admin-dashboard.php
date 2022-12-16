<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "Administrator";
            $page_index = 1;
            require_once('utility/header.php');
            load_css('admin-dashboard');
            load_css('app-modal-override');
            load_css('app-empty-wrapper');

            auth_admin();

            $session = auth_session('session', 'sign-out');

            $orders = sql_count_rows("SELECT * FROM orders WHERE status = 1 OR status = 2 OR status = 3 AND is_archived = 0");
            $deliveries = sql_count_rows("SELECT * FROM orders WHERE status = 2 OR status = 3 AND is_archived = 0");
            $products = sql_get_results("SELECT * FROM products WHERE is_archived = 0");
            $stocks = 0;
            foreach($products as $product) {
                $stocks += $product['stocks'];
            }

        ?>
    </head>
    <body>
        <?php render_sidebar('admin-dashboard') ?>
        <div class="wrapper">
            <?php render_navbar('admin-dashboard') ?>
            <div class="inner-wrapper">
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="cute-card blue">
                            <img src="repository/assets/images/wave.svg" class="cute-card-bg">
                            <p class="cute-card-label">TOTAL ORDERS</p>
                            <p class="cute-card-amount"><?php echo $orders ?></p>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <div class="cute-card orange">
                            <img src="repository/assets/images/wave2.svg" class="cute-card-bg">
                            <p class="cute-card-label">TOTAL DELIVERIES</p>
                            <p class="cute-card-amount"><?php echo $deliveries ?></p>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <div class="cute-card green">
                            <img src="repository/assets/images/wave3.svg" class="cute-card-bg">
                            <p class="cute-card-label">TOTAL STOCKS</p>
                            <p class="cute-card-amount"><?php echo $stocks ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="analytic-card">
                            <div class="analytic-card-header">
                                <h1 class="analytic-card-header-title">New Orders</h1>
                                <p><?php echo date('M/d/Y'); ?></p>
                            </div>
                            <div class="analytic-card-body">
                                <?php
                                    $orders = sql_get_results("SELECT * FROM orders WHERE status = 1 AND is_archived = 0");
                                    if(count($orders) > 0) {
                                        echo "<table class='th-table override'>";
                                        echo "    <thead>";
                                        echo "        <tr>";
                                        echo "            <th>CUSTOMER NAME</th>";
                                        echo "            <th>PRODUCT NAME</th>";
                                        echo "            <th>PAYMENT TYPE</th>";
                                        echo "            <th>ACTION</th>";
                                        echo "        </tr>";
                                        echo "    </thead>";
                                        echo "    <tbody>";
                                        foreach($orders as $order) {
                                            $customer = read_user($order['user_id']);
                                            $product = read_product($order['product_id']);
                                            $disabled = $order['payment_method'] == 1 ? "th-disabled" : "";
                                            $cancel_disabled = $order['status'] == 1 ? "" : "th-disabled";
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo to_name($customer) ?>
                                                </td>
                                                <td><?php echo $product['name'] ?></td>
                                                <td>
                                                    <?php echo $payment_method[$order['payment_method']] ?>
                                                </td>
                                                <td>
                                                    <a data="<?php echo htmljson($order) ?>" type="button" open-modal="viewPayment" class="th-button th-color-blue th-size-small th-modal-toggler <?php echo $disabled ?> view-btn">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        echo "    </tbody>";
                                        echo "</table>";
                                    } else {
                                        render_empty('orders');
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="analytic-card critical">
                            <div class="analytic-card-header">
                                <h1 class="analytic-card-header-title">Critical Stocks</h1>
                            </div>
                            <div class="analytic-card-body">
                                <?php
                                    $products = sql_get_results("SELECT * FROM products WHERE stocks < 11 AND is_archived = 0");
                                    if(count($products) > 0) {
                                        echo "<table class='th-table override'>";
                                        echo "    <thead>";
                                        echo "        <tr>";
                                        echo "            <th>THUMB</th>";
                                        echo "            <th>PRICE</th>";
                                        echo "            <th>STOCKS</th>";
                                        echo "        </tr>";
                                        echo "    </thead>";
                                        echo "    <tbody>";
                                        foreach($products as $product) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <img class="table-image" src="<?php echo $product['image_location'] ?>">
                                                </td>
                                                <td><?php echo $product['price'] ?></td>
                                                <td>
                                                    <?php echo $product['stocks'] ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        echo "    </tbody>";
                                        echo "</table>";
                                    } else {
                                        render_empty('products');
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        <?php
            package_manager('script');
            load_script('admin-dashboard');
            load_script('admin-dashboard-settings');
        ?>
    </body>
</html>