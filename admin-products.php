<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "Administrator";
            $page_index = 4;
            require_once('utility/header.php');
            load_css('admin-dashboard');
            load_css('app-breadcrumbs');
            load_css('app-empty-wrapper');
            load_css('app-modal-override');
            load_css('app-drop-zone');

            auth_admin();

            $session = auth_session('session', 'sign-out');

            $user = strtoassoc('id');
            $input = $user;
            $error = $user;

            switch(postaction()) {
                case 'create':

                    $user = strtoassoc('name, description, brand, price, stocks');
                    $input = $user;
                    $error = $user;
        
                    custom_validator('name', properties(WT_STRING, 1, 255));

                    post_validate();

                    if(is_empty($error)) {
                        $user['date_added'] = date($c_date_format);
                        $user['unique_key'] = sql_unique_key('products');
                        $user['is_archived'] = 0;
                        if(upload_file("uploads/{$user['unique_key']}", 'image_location')) {
                            if(insert_product($user)) {
                                $_SESSION['success'] = array("<b>{$user['name']}</b> successfully added.");
                                redirect_to('admin-products');
                            }
                        } else {
                            $error['err'] = "Failed to upload image.";
                        }
                    }

                break;

                case 'update':
                    $user = strtoassoc('id, name, price, brand, stocks, description');
                    $input = $user;
                    $error = $user;
                    custom_validator('name', properties(WT_STRING, 1, 255));
                    post_validate();
                    if(is_empty($error)) {
                        $sql = "UPDATE products SET name = ?, price = ?, brand = ?, stocks = ?, description = ? WHERE id = ?";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "sssisi", $param_name, $param_price, $param_brand, $param_stocks, $param_description, $param_id);
                            $param_name = $user["name"];
                            $param_price = $user["price"];
                            $param_brand = $user["brand"];
                            $param_stocks = $user["stocks"];
                            $param_description = $user["description"];
                            $param_id = $user['id'];
                            if(mysqli_stmt_execute($stmt)) {
                                $_SESSION['success'] = array("Succesfully updated.");
                                redirect_to('admin-products');
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
                    <a href="#" class="app-breadcrumbs-item active">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fad fa-shopping-bag"></i>
                        </span>
                        Products
                    </a>
                    <span class="app-breadcrumbs-seperator">
                        <i class="far fa-chevron-right"></i>
                    </span>
                    <a type="button" open-modal="registerProduct" class="app-breadcrumbs-item th-modal-toggler">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fas fa-cowbell-circle-plus"></i>
                        </span>
                        Register Product
                    </a>
                </div>
                <?php

                    _builtinerror();
                    _builtinsuccess();

                    $products = sql_get_results("SELECT * FROM products WHERE stocks > 0 AND is_archived = 0");

                    if(count($products) > 0) {
                        render_list('admin-products');
                    } else {
                        render_empty('admin-products');
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
        <form action="<?php echo self() ?>" method="post" class="th-modal delete-modal" id="deleteProduct">
            <input type="hidden" name="req_action" value="delete">
            <input type="hidden" name="id" value="">
            <div class="th-modal-content" style="width: 512px">
                <div class="th-modal-content-header">
                    <h1 class="th-modal-content-header-title">
                        Delete Product
                    </h1>
                </div>
                <div class="th-modal-content-body">
                    Do you want to delete <span class="th-modal-content-body-strong product-name">//PRODUCT NAME//</span>?
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
        <?php
            package_manager('script');
            load_script('admin-dashboard');
            load_script('admin-search-products');
            load_script('admin-products');
        ?>
    </body>
</html>