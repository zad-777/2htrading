<?php

    require_once('utility/helper.php');

    $session = auth_session('session', 'sign-out');
    $page_no = issetsession('admin_deliveries_no') ? session('admin_deliveries_no') : 0;

    $user = strtoassoc('view, name');

    $user['view'] = issetget('view') ? trimget('view') : 100;
    $user['name'] = issetget('name') ? trimget('name') : "";

    $total_records = ceil(sql_count_rows("SELECT * FROM orders WHERE status = 2 OR status = 3 OR status = 4 AND is_archived = 0") / $user['view']);

    switch(getaction()) {
        case "previous":
            if($page_no > 0)
                $page_no--;
            unset($_SESSION['admin_deliveries_no']);
            $_SESSION['admin_deliveries_no'] = $page_no;
            break;
        case "next":
            if($page_no < ($total_records - 1))
                $page_no++;
            unset($_SESSION['admin_deliveries_no']);
            $_SESSION['admin_deliveries_no'] = $page_no;
            break;
        case "reset":
            unset($_SESSION['admin_deliveries_no']);
            $_SESSION['admin_deliveries_no'] = 0;
            $page_no = 0;
            break;
        default:
            break;
    }

    $page_no = $user['view'] * $page_no;

    $deliveries = sql_get_results("SELECT * FROM orders WHERE status = 2 OR status = 3 OR status = 4 AND is_archived = 0 ORDER BY id DESC LIMIT $page_no, {$user['view']}");

    if(strlen($user['name']) > 0) {
        $temp = sql_get_results("SELECT * FROM orders WHERE status = 2 OR status = 3 OR status = 4 AND is_archived = 0 LIMIT 0, {$user['view']}");
        $deliveries = array();
        for($i = 0; $i < count($temp); $i++) {
            $tmp = $temp[$i];
            $product = read_product($tmp['product_id']);
            $full_name = strtolower($product['name']);
            $name = strtolower($user['name']);
            if(strpos($full_name, $name) !== false) {
                array_push($deliveries, $tmp);
            }
        }
    }

    if(count($deliveries) > 0) {
        foreach($deliveries as $order) {
            $product = read_product($order['product_id']);
            $order['product_name'] = $product['name'];
            $disabled = $order['payment_method'] == 1 ? "th-disabled" : "";
            $edit_disabled = $order['status'] == 4 ? "th-disabled" : "";
            ?>
            <tr>
                <td>
                    <img src="<?php echo $product['image_location'] ?>" class="table-image">
                </td>
                <td><?php echo $product['name'] ?></td>
                <td><?php echo $product['price'] ?></td>
                <td><?php echo $order['quantity'] ?></td>   
                <td><?php echo $c_peso.' '.peso_format($order['total']) ?></td>
                <td><?php echo $order['date_ordered'] ?></td>
                <td>
                    <?php echo $payment_method[$order['payment_method']] ?>
                </td>
                <td>
                    <?php echo $order_status[$order['status']] ?>
                </td>
                <td>
                    <a data="<?php echo htmljson($order) ?>" open-modal="viewPayment" class="th-button th-color-blue th-size-small th-modal-toggler view-btn <?php echo $disabled ?>">
                        <i class="fas fa-image"></i>
                    </a>
                    <a type="button" data="<?php echo htmljson($order) ?>" open-modal="updateStatus" class="th-button th-color-red th-size-small th-modal-toggler update-btn <?php echo $edit_disabled ?>">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="5">No deliveries found...</td>
        </tr>
        <?php
    }

?>