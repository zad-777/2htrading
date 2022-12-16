<?php
    require_once('utility/helper.php');
    
    auth_user();

    $session = auth_session('session', 'sign-out');

    $user = strtoassoc('name');
    $user['name'] = trimget('name') ?? "";

    $products = sql_get_results("SELECT * FROM products WHERE name LIKE '%{$user['name']}%' AND is_archived = 0");

    if(count($products) > 0) {
        echo "<div class='row'>";
        foreach($products as $product) {
            $is_added = is_added_to_cart($session['id'], $product['id']);
            $anchor = condition('', $is_added, "home?req_action=add_to_cart&id={$product['id']}");
            $icon = $is_added ? 'fas fa-basket-shopping-simple' : 'far fa-basket-shopping-simple';
            $is_out_of_stock = $product['stocks'] == 0 ? "th-disabled" : "";
            $stocks = $product['stocks'] > 0 ? "(".$product['stocks']." stocks)" : "(Out of Stock)";
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="product-card">
                    <a href="<?php echo $anchor ?>" class="product-card-cart <?php echo $is_added ?>">
                        <i class="<?php echo $icon ?>"></i>
                    </a>
                    <div class="img-wrapper">
                        <img src="<?php echo $product['image_location'] ?>">
                    </div>
                    <p class="name"><?php echo $product['name'] ?></p>
                    <p class="description"><?php echo $product['description'] ?> <?php echo $stocks ?></p>
                    <p class="price">
                        <span>₱</span>
                        <span><?php echo peso_format($product['price']) ?></span>
                    </p>
                    <a href="check-out?id=<?php echo $product['id'] ?>&quantity=1" class="th-button th-color-blue <?php echo $is_out_of_stock ?>">
                        <i class="far fa-shopping-cart mr-2"></i>
                        CHECK OUT
                    </a>
                </div>
            </div>
            <?php
        }
        echo "</div>";
    } else {
        render_empty('user-products');
    }

?>
