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

            $user = strtoassoc('id, status');
            $input = $user;
            $error = $user;

            switch(postaction()) {
                case 'update':
                    post_validate();
                    if(is_empty($error)) {
                        $order = read_order($user['id']);
                        $product = read_product($order['product_id']);
                        $sql = "UPDATE orders SET status = ? WHERE id = ?";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ii", $param_status, $param_id);
                            $param_status = $user["status"];
                            $param_id = $user["id"];
                            if(mysqli_stmt_execute($stmt)) {
                                if($user['status'] == 2 || $user['status'] == 3 || $user['status'] == 4) {
                                    $sql = "UPDATE products SET stocks = ? WHERE id = ?";
                                    if($stmt = mysqli_prepare($link, $sql)) {
                                        mysqli_stmt_bind_param($stmt, "ii", $param_stocks, $param_id);
                                        $param_stocks = $product["stocks"] - $order['quantity'];
                                        $param_id = $product["id"];
                                        if(mysqli_stmt_execute($stmt)) {
                                            $_SESSION["success"] = array("<b>{$product['name']}</b> Successfully Updated!");
                                            redirect_to('admin-orders');
                                        }
                                    }
                                }
                            }
                        }
                    }
                break;

                case 'delete':
                    post_validate();
                    if(is_empty($error)) {
                        $product = read_product($user['id']);
                        $sql = "UPDATE products SET is_archived = ? WHERE id = ?";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ii", $param_is_archived, $param_id);
                            $param_is_archived = 1;
                            $param_id = $product['id'];
                            if(mysqli_stmt_execute($stmt)) {
                                $_SESSION["success"] = array("<b>{$product['name']}</b> Successfully deleted!");
                                redirect_to('admin-products');
                            }
                        }
                    }
                break;
            }

        ?>
    </head>
    <body>
        <?php render_sidebar('admin-dashboard') ?>
        <div class="wrapper">
            <?php render_navbar('admin-products') ?>
            <div class="inner-wrapper">
                <div class="app-breadcrumbs">
                    <a href="admin-orders" class="app-breadcrumbs-item active">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fad fa-shopping-cart"></i>
                        </span>
                        Orders
                    </a>
                    <span class="app-breadcrumbs-seperator">
                        <i class="far fa-chevron-right"></i>
                    </span>
                    <a href="admin-cancel-orders" class="app-breadcrumbs-item">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fas fa-cancel"></i>
                        </span>
                        Cancel Products
                    </a>
                </div>
                <?php

                    _builtinerror();
                    _builtinsuccess();

                    $orders = sql_get_results("SELECT * FROM orders WHERE status = 1 AND is_archived = 0");

                    if(count($orders) > 0) {
                        render_list('admin-orders');
                    } else {
                        render_empty('admin-orders');
                    }

                ?>
            </div>
        </div>
        <form action="<?php echo self() ?>" method="post" class="th-modal" id="registerProduct" enctype="multipart/form-data">
            <input type="hidden" name="req_action" value="create">
            <div class="th-modal-content" style="width: 512px">
                <div class="th-modal-content-header">
                    <h1 class="th-modal-content-header-title">
                        Register Product
                    </h1>
                </div>
                <div class="th-modal-content-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">NAME</p>
                                <input type="text" 
                                class="th-input-text override" 
                                name="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">PRICE</p>
                                <input type="text" 
                                class="th-input-text override" 
                                name="price">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">BRAND</p>
                                <input type="text" 
                                class="th-input-text override" 
                                name="brand">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">STOCKS</p>
                                <select name="stocks" class="th-input-select override">
                                    <?php
                                        for($i = 1; $i <= 200; $i++) {
                                            echo "<option value='{$i}'>{$i}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label override">DESCRIPTION</p>
                        <input type="text" 
                        class="th-input-text override" 
                        name="description">
                    </div>
                    <div class="th-upload override">
                        <input type="file" name="image_location" id="thumbnail">
                        <label for="thumbnail" class="th-upload-wrapper">
                            <div class="th-upload-message">
                                <span class="icon"><i class="far fa-cloud-arrow-up"></i></span>
                                <span class="title">Upload Thumbnail</span>
                            </div>
                        </label>
                        <img class="image-container">
                        <a type="button" class="remove-button">
                            <i class="far fa-times-circle"></i>
                        </a>
                    </div>
                </div>
                <div class="th-modal-content-footer">
                    <a type="button" class="th-modal-content-footer-close">
                        Close
                    </a>
                    <button type="submit" class="th-modal-content-footer-confirm">
                        Confirm
                    </a>
                </div>
            </div>
        </form>
        <form action="<?php echo self() ?>" method="post" class="th-modal update-modal" id="updateProduct">
            <input type="hidden" name="req_action" value="update">
            <input type="hidden" name="id" value="">
            <div class="th-modal-content" style="width: 512px">
                <div class="th-modal-content-header">
                    <h1 class="th-modal-content-header-title">
                        Edit Product
                    </h1>
                </div>
                <div class="th-modal-content-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">NAME</p>
                                <input type="text" 
                                class="th-input-text override" 
                                name="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">PRICE</p>
                                <input type="text" 
                                class="th-input-text override" 
                                name="price">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">BRAND</p>
                                <input type="text" 
                                class="th-input-text override" 
                                name="brand">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label override">STOCKS</p>
                                <select name="stocks" class="th-input-select override">
                                    <?php
                                        for($i = 1; $i <= 200; $i++) {
                                            echo "<option value='{$i}'>{$i}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label override">DESCRIPTION</p>
                        <input type="text" 
                        class="th-input-text override" 
                        name="description">
                    </div>
                </div>
                <div class="th-modal-content-footer">
                    <a type="button" class="th-modal-content-footer-close">
                        Close
                    </a>
                    <button type="submit" class="th-modal-content-footer-confirm">
                        Confirm
                    </a>
                </div>
            </div>
        </form>
        <form action="<?php echo self() ?>" method="post" class="th-modal update-modal" id="updateStatus">
            <input type="hidden" name="req_action" value="update">
            <input type="hidden" name="id" value="">
            <div class="th-modal-content" style="width: 512px">
                <div class="th-modal-content-header">
                    <h1 class="th-modal-content-header-title">
                        Update Status
                    </h1>
                </div>
                <div class="th-modal-content-body">
                    <div class="th-form-group">
                        <p class="th-input-label override">Product Name</p>
                        <input type="text" class="th-input-text override" name="name" readonly>
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label override">Order Status</p>
                        <select class="th-input-select override" name="status">
                            <?php
                            foreach($order_status as $key => $value) {
                                echo "<option value='{$key}'>{$value}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="th-modal-content-footer">
                    <a type="button" class="th-modal-content-footer-close">
                        Close
                    </a>
                    <button type="submit" class="th-modal-content-footer-confirm">
                        Confirm
                    </a>
                </div>
            </div>
        </form>
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
            load_script('admin-search-orders');
            load_script('admin-orders');
        ?>
    </body>
</html>