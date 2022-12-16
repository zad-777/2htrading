<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "Administrator";
            $page_index = 5;
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

            custom_validator('name', properties(WT_STRING, 1, 255));

            if(is_post()) {
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
            }

        ?>
    </head>
    <body>
        <?php render_sidebar('admin-dashboard') ?>
        <div class="wrapper">
            <?php render_navbar('admin-customers') ?>
            <div class="inner-wrapper">
                <div class="app-breadcrumbs">
                    <a href="#" class="app-breadcrumbs-item active">
                        <span class="app-breadcrumbs-item-icon">
                            <i class="fad fa-users"></i>
                        </span>
                        Customers
                    </a>
                </div>
                <?php

                    _builtinerror();
                    _builtinsuccess();

                    $users = sql_get_results("SELECT * FROM users WHERE is_archived = 0");

                    if(count($users) > 0) {
                        render_list('admin-users');
                    } else {
                        render_empty('admin-users');
                    }

                ?>
            </div>
        </div>
        <form action="<?php echo self() ?>" method="post" class="th-modal" id="registerProduct" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_product">
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
        <?php
            package_manager('script');
            load_script('admin-search-customers');
        ?>
    </body>
</html>