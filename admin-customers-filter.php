<?php

    require_once('utility/helper.php');

    $session = auth_session('session', 'sign-out');
    $page_no = issetsession('admin_customers_no') ? session('admin_customers_no') : 0;

    $user = strtoassoc('view, name');

    $user['view'] = issetget('view') ? trimget('view') : 100;
    $user['name'] = issetget('name') ? trimget('name') : "";

    $total_records = ceil(sql_count_rows("SELECT * FROM users WHERE is_archived = 0") / $user['view']);

    switch(getaction()) {
        case "previous":
            if($page_no > 0)
                $page_no--;
            unset($_SESSION['admin_customers_no']);
            $_SESSION['admin_customers_no'] = $page_no;
            break;
        case "next":
            if($page_no < ($total_records - 1))
                $page_no++;
            unset($_SESSION['admin_customers_no']);
            $_SESSION['admin_customers_no'] = $page_no;
            break;
        case "reset":
            unset($_SESSION['admin_customers_no']);
            $_SESSION['admin_customers_no'] = 0;
            $page_no = 0;
            break;
        default:
            break;
    }

    $page_no = $user['view'] * $page_no;

    $users = sql_get_results("SELECT * FROM users WHERE is_archived = 0 ORDER BY id DESC LIMIT $page_no, {$user['view']}");

    if(strlen($user['name']) > 0) {
        $temp = sql_get_results("SELECT * FROM users WHERE is_archived = 0 LIMIT 0, {$user['view']}");
        $users = array();
        for($i = 0; $i < count($temp); $i++) {
            $tmp = $temp[$i];
            $full_name = strtolower(to_name($tmp));
            $name = strtolower($user['name']);
            if(strpos($full_name, $name) !== false) {
                array_push($users, $tmp);
            }
        }
    }

    if(count($users) > 0) {
        foreach($users as $user) {
        ?>
        <tr>
            <td><?php echo to_name($user) ?></td>
            <td><?php echo $user['home_address'] ?></td>
            <td><?php echo $user['contact_number'] ?></td>
            <td><?php echo $user['email_address'] ?></td>
            <td class="text-right">
                <a type="button" data="<?php echo htmljson($product) ?>" open-modal="deleteProduct" class="th-button th-color-red th-size-small th-modal-toggler delete-btn th-disabled">
                    <i class="far fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="5">No customer name result found...</td>
        </tr>
        <?php
    }

?>