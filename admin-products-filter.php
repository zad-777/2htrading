<?php

    require_once('utility/helper.php');

    $session = auth_session('session', 'sign-out');
    $page_no = issetsession('admin_products_no') ? session('admin_products_no') : 0;

    $user = strtoassoc('view, name');

    $user['view'] = issetget('view') ? trimget('view') : 100;
    $user['name'] = issetget('name') ? trimget('name') : "";

    $total_records = ceil(sql_count_rows("SELECT * FROM products WHERE is_archived = 0") / $user['view']);

    switch(getaction()) {
        case "previous":
            if($page_no > 0)
                $page_no--;
            unset($_SESSION['admin_products_no']);
            $_SESSION['admin_products_no'] = $page_no;
            break;
        case "next":
            if($page_no < ($total_records - 1))
                $page_no++;
            unset($_SESSION['admin_products_no']);
            $_SESSION['admin_products_no'] = $page_no;
            break;
        case "reset":
            unset($_SESSION['admin_products_no']);
            $_SESSION['admin_products_no'] = 0;
            $page_no = 0;
            break;
        default:
            break;
    }

    $page_no = $user['view'] * $page_no;

    $products = sql_get_results("SELECT * FROM products WHERE is_archived = 0 ORDER BY id DESC LIMIT $page_no, {$user['view']}");

    if(strlen($user['name']) > 0) {
        $temp = sql_get_results("SELECT * FROM products WHERE is_archived = 0 LIMIT 0, {$user['view']}");
        $products = array();
        for($i = 0; $i < count($temp); $i++) {
            $tmp = $temp[$i];
            $product = read_product($tmp['product_id']);
            $full_name = strtolower($product['name']);
            $name = strtolower($user['name']);
            if(strpos($full_name, $name) !== false) {
                array_push($products, $tmp);
            }
        }
    }

    if(count($products) > 0) {
        foreach($products as $product) {
            ?>
            <tr>
                <td>
                    <img src="<?php echo $product['image_location'] ?>" class="table-image">
                </td>
                <td><?php echo $product['name'] ?></td>
                <td><?php echo $product['description'] ?></td>
                <td><?php echo $product['brand'] ?></td>
                <td><?php echo $product['price'] ?></td>
                <td><?php echo $product['date_added'] ?></td>
                <td><?php echo $product['stocks'] ?></td>
                <td>
                    <a type="button" data="<?php echo htmljson($product) ?>" open-modal="updateProduct" class="th-button th-color-blue th-size-small th-modal-toggler update-btn">
                        <i class="far fa-edit"></i>
                    </a>
                    <a type="button" data="<?php echo htmljson($product) ?>" open-modal="deleteProduct" class="th-button th-color-red th-size-small th-modal-toggler delete-btn">
                        <i class="far fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="8">No products found...</td>
        </tr>
        <?php
    }

?>