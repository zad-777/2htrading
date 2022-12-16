<?php

if(!file_exists('uploads'))
    mkdir('uploads', 0777, TRUE);

session_start();

$person_type = array(
    1 => 'Citizen',
    2 => 'Student',
    3 => 'Senior',
    4 => 'PWD'
);

$status = array(
    1 => 'Pending',
    2 => 'Accepted',
    3 => 'Declined'
);


$payment_method = array(
    1 => 'COD',
    2 => 'SCAN TO PAY'
);

$order_status = array(
    1 => 'Pending',
    2 => 'Delivering Ready', 
    3 => 'Delivering',
    4 => 'Delivered',
    5 => 'Cancelled'
);

session_stamp('verification', array('verification'));
session_stamp('admin_products_no', array('admin-products', 'admin-products-filter'));
session_stamp('admin_customers_no', array('admin-customers', 'admin-customers-filter'));
session_stamp('admin_orders_no', array('admin-orders', 'admin-orders-filter'));
session_stamp('admin_cancel_orders_no', array('admin-cancel-orders', 'admin-cancel-orders-filter'));
session_stamp('admin_deliveries_no', array('admin-deliveries', 'admin-deliveries-filter'));
session_stamp('product', array('check-out'));


//START: METHOD

function _builtinerror() {

    GLOBAL $error;
    if(!is_empty($error) || issetsession('session_error')) {
        echo "<div class='th-alert th-alert-danger'>";
        echo "    <a type='button' class='th-alert-close'>";
        echo "        <i class='fas fa-window-minimize'></i>";
        echo "    </a>";
        echo "    <div class='th-alert-message'>";
        echo "        <h1 class='th-alert-message-title'>Your input has an error, please check it</h1>";
        echo "        <ul class='th-alert-message-list'>";
        foreach($error as $key => $value) {
            if(!empty($value))
                echo '    <li><i class="fas fa-exclamation-circle mr-1"></i>'.$value.'</li>';
        }
        if(issetsession('session_error')) {
    
            $session_error = session('session_error');
    
            foreach($session_error as $key => $value) {
                if(!empty($value))
        echo '        <li><i class="fas fa-exclamation-circle mr-1"></i>'.$value.'</li>';	
            }
            
            unset($_SESSION['session_error']);
    
        }
        echo "        </ul>";
        echo "    </div>";
        echo "</div>";
    }
}
function _builtinsuccess($has_margin=false) {
    if(issetsession('success')) {
        $success = session('success');
        if(!is_empty($success)) {
            foreach($success as $value) {
                $margin = $has_margin == false ? "" : "mb-2";
                echo "<div class='th-info th-info-success {$margin}'>";
                echo "    <a type='button' class='th-info-close'>";
                echo "        <i class='fas fa-window-minimize'></i>";
                echo "    </a>";
                echo "    <p class='th-info-text'>";
                echo "        <i class='fas fa-check-circle mr-1'></i>";
                echo          $value;
                echo "    </p>";
                echo "</div>";
            }
        }
        unset($_SESSION['success']);
    }

}
function write_to_config($variable, $value) {
    $str = file_get_contents('my_store.config');
    $handler = fopen('my_store.config', 'w+');
    $result = '';
    if($handler) {
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $str) as $line){
            $name = preg_replace("(=([A-Za-z_]+\s?))", "", "$line");
            if($variable == $name) {
                $result .= $name;
                $result .= '=';
                $result .= $value;
            } else {
                $result .= $line;
            }
        }
        fwrite($handler, $result);
        fclose($handler);
    }
}
function read_to_config($variable) {
    $result = "";
    $handler = fopen('my_store.config', 'r');
    if($handler) {
        while(($line = fgets($handler)) !== false) {
            $name = preg_replace("(=([A-Za-z_]+\s?))", "", "$line");
            if($variable == $name) {
                $result = preg_replace("(([A-Za-z_]+\s?)=)", "", "$line");
               break; 
            }
        }
        fclose($handler);
    }
    return $result;
}
function is_added_to_cart($user_id, $product_id) {
    $is_added = sql_count_rows("SELECT * FROM cart_list WHERE user_id = {$user_id} AND product_id = {$product_id}") > 0;
    return $is_added ? "is-added" : "";
}

function is_added_to_cart2($user_id, $product_id) {
    $is_added = sql_count_rows("SELECT * FROM cart_list WHERE user_id = {$user_id} AND product_id = {$product_id}") > 0;
    return $is_added;
}

//END: METHODS

//START: AUTH

function auth_outside() {
    $session = not_required_session('session');
    if($session) {
        if($session['type'] == 1) {
            redirect_to('admin-dashboard');
        } else if($session['type'] == 2) {
            redirect_to('home');
        }
    }
}
function auth_admin() {
    $session = not_required_session('session');
    if($session) {
        if($session['type'] == 2) {
            redirect_to('home');
        }
    } else {
        redirect_to('index');
    }
}
function auth_user() {
    $session = not_required_session('session');
    if($session) {
        if($session['type'] == 1) {
            redirect_to('admin-dashboard');
        }
    } else {
        redirect_to('index');
    }
}

//END: AUTH

//START: ADMIN

function insert_admin($admin) {
    GLOBAL $link;
    $sql = "INSERT admins (full_name, username, password, type, is_archived) VALUES (?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssii", $param_full_name, $param_username, $param_password, $param_type, $param_is_archived);
        $param_full_name = $admin["full_name"];
        $param_username = $admin["username"];
        $param_password = $admin["password"];
        $param_type = $admin["type"];
        $param_is_archived = $admin["is_archived"];

        if(mysqli_stmt_execute($stmt)) {
            return true;
        }
    }
    return false;
}
function read_admin($id) {
    GLOBAL $link;
    $admin = to_associative(array("id", "full_name", "username", "password", "type", "is_archived"));
    $sql = 'SELECT * FROM admins WHERE id = "'. $id . '"';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($admin as $key => $value) {
    	        $admin[$key] = $row[$key];
            }
        }
    }
    return $admin;
}
function read_admins() {
    GLOBAL $link;
    $admins = array();
    $index = 0;
    $sql = 'SELECT id, full_name, username, password, type, is_archived FROM admins WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $admins[$index] = $row;
            $index++;
        }
    }
    return $admins;
}
function count_admins() {
    GLOBAL $link;
    $sql = 'SELECT * FROM admins WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        return mysqli_num_rows($result);
    }
    return 0;
}
function delete_admin($id) {
    GLOBAL $link;
    if(sql_data_exist('admins', 'id', $id)) {
        $sql = 'DELETE FROM admins WHERE id = "'.$id.'"';
        if(mysqli_query($link, $sql)) {
            return true;
        }
    }
    return false;
}
function last_admin() {
    GLOBAL $link;
    $admin = to_associative(array("full_name", "username", "password", "type", "is_archived"));
    $sql = 'SELECT * FROM admins ORDER BY id DESC LIMIT 1';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($admin as $key => $value) {
    	        $admin[$key] = $row[$key];
            }
        }
    }
    return $admin;
}

//END: ADMIN

//START: USER

function insert_user($user) {
    GLOBAL $link;
    $sql = "INSERT users (first_name, middle_name, last_name, contact_number, home_address, email_address, password, type, unique_key, is_archived) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssisi", $param_first_name, $param_middle_name, $param_last_name, $param_contact_number, $param_home_address, $param_email_address, $param_password, $param_type, $param_unique_key, $param_is_archived);
        $param_first_name = $user["first_name"];
        $param_middle_name = $user["middle_name"];
        $param_last_name = $user["last_name"];
        $param_contact_number = $user["contact_number"];
        $param_home_address = $user["home_address"];
        $param_email_address = $user["email_address"];
        $param_password = $user["password"];
        $param_type = $user["type"];
        $param_unique_key = $user["unique_key"];
        $param_is_archived = $user["is_archived"];

        if(mysqli_stmt_execute($stmt)) {
            return true;
        }
    }
    return false;
}
function read_user($id) {
    GLOBAL $link;
    $user = to_associative(array("id", "first_name", "middle_name", "last_name", "contact_number", "home_address", "email_address", "password", "type", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM users WHERE id = "'. $id . '"';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($user as $key => $value) {
    	        $user[$key] = $row[$key];
            }
        }
    }
    return $user;
}
function read_users() {
    GLOBAL $link;
    $users = array();
    $index = 0;
    $sql = 'SELECT id, first_name, middle_name, last_name, contact_number, home_address, email_address, password, type, unique_key, is_archived FROM users WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $users[$index] = $row;
            $index++;
        }
    }
    return $users;
}
function count_users() {
    GLOBAL $link;
    $sql = 'SELECT * FROM users WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        return mysqli_num_rows($result);
    }
    return 0;
}
function delete_user($id) {
    GLOBAL $link;
    if(sql_data_exist('users', 'id', $id)) {
        $sql = 'DELETE FROM users WHERE id = "'.$id.'"';
        if(mysqli_query($link, $sql)) {
            return true;
        }
    }
    return false;
}
function last_user() {
    GLOBAL $link;
    $user = to_associative(array("first_name", "middle_name", "last_name", "contact_number", "home_address", "email_address", "password", "type", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM users ORDER BY id DESC LIMIT 1';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($user as $key => $value) {
    	        $user[$key] = $row[$key];
            }
        }
    }
    return $user;
}

//END: USER

//START: PRODUCT

function insert_product($product) {
    GLOBAL $link;
    $sql = "INSERT products (name, description, brand, price, date_added, stocks, image_location, unique_key, is_archived) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssissi", $param_name, $param_description, $param_brand, $param_price, $param_date_added, $param_stocks, $param_image_location, $param_unique_key, $param_is_archived);
        $param_name = $product["name"];
        $param_description = $product["description"];
        $param_brand = $product["brand"];
        $param_price = $product["price"];
        $param_date_added = $product["date_added"];
        $param_stocks = $product["stocks"];
        $param_image_location = $product["image_location"];
        $param_unique_key = $product["unique_key"];
        $param_is_archived = $product["is_archived"];

        if(mysqli_stmt_execute($stmt)) {
            return true;
        }
    }
    return false;
}
function read_product($id) {
    GLOBAL $link;
    $product = to_associative(array("id", "name", "description", "brand", "price", "date_added", "stocks", "image_location", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM products WHERE id = "'. $id . '"';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($product as $key => $value) {
    	        $product[$key] = $row[$key];
            }
        }
    }
    return $product;
}
function read_products() {
    GLOBAL $link;
    $products = array();
    $index = 0;
    $sql = 'SELECT id, name, description, brand, price, date_added, stocks, image_location, unique_key, is_archived FROM products WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $products[$index] = $row;
            $index++;
        }
    }
    return $products;
}
function count_products() {
    GLOBAL $link;
    $sql = 'SELECT * FROM products WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        return mysqli_num_rows($result);
    }
    return 0;
}
function delete_product($id) {
    GLOBAL $link;
    if(sql_data_exist('products', 'id', $id)) {
        $sql = 'DELETE FROM products WHERE id = "'.$id.'"';
        if(mysqli_query($link, $sql)) {
            return true;
        }
    }
    return false;
}
function last_product() {
    GLOBAL $link;
    $product = to_associative(array("name", "description", "brand", "price", "date_added", "stocks", "image_location", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM products ORDER BY id DESC LIMIT 1';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($product as $key => $value) {
    	        $product[$key] = $row[$key];
            }
        }
    }
    return $product;
}

//END: PRODUCT

//START: CART

function insert_cart($cart) {
    GLOBAL $link;
    $sql = "INSERT cart_list (user_id, product_id, quantity, unique_key, is_archived) VALUES (?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiisi", $param_user_id, $param_product_id, $param_quantity, $param_unique_key, $param_is_archived);
        $param_user_id = $cart["user_id"];
        $param_product_id = $cart["product_id"];
        $param_quantity = $cart["quantity"];
        $param_unique_key = $cart["unique_key"];
        $param_is_archived = $cart["is_archived"];

        if(mysqli_stmt_execute($stmt)) {
            return true;
        }
    }
    return false;
}
function read_cart($id) {
    GLOBAL $link;
    $cart = to_associative(array("id", "user_id", "product_id", "quantity", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM cart_list WHERE id = "'. $id . '"';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($cart as $key => $value) {
    	        $cart[$key] = $row[$key];
            }
        }
    }
    return $cart;
}
function read_cart_list() {
    GLOBAL $link;
    $cart_list = array();
    $index = 0;
    $sql = 'SELECT id, user_id, product_id, quantity, unique_key, is_archived FROM cart_list WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $cart_list[$index] = $row;
            $index++;
        }
    }
    return $cart_list;
}
function count_cart_list() {
    GLOBAL $link;
    $sql = 'SELECT * FROM cart_list WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        return mysqli_num_rows($result);
    }
    return 0;
}
function delete_cart($id) {
    GLOBAL $link;
    if(sql_data_exist('cart_list', 'id', $id)) {
        $sql = 'DELETE FROM cart_list WHERE id = "'.$id.'"';
        if(mysqli_query($link, $sql)) {
            return true;
        }
    }
    return false;
}
function last_cart() {
    GLOBAL $link;
    $cart = to_associative(array("user_id", "product_id", "quantity", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM cart_list WHERE is_archived = 0 ORDER BY id DESC LIMIT 1';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($cart as $key => $value) {
    	        $cart[$key] = $row[$key];
            }
        }
    }
    return $cart;
}

//END: CART

//START: ORDER

function insert_order($order) {
    GLOBAL $link;
    $sql = "INSERT orders (user_id, product_id, quantity, total, date_ordered, date_arrived, image_location, status, payment_method, unique_key, is_archived) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiissssiisi", $param_user_id, $param_product_id, $param_quantity, $param_total, $param_date_ordered, $param_date_arrived, $param_image_location, $param_status, $param_payment_method, $param_unique_key, $param_is_archived);
        $param_user_id = $order["user_id"];
        $param_product_id = $order["product_id"];
        $param_quantity = $order["quantity"];
        $param_total = $order["total"];
        $param_date_ordered = $order["date_ordered"];
        $param_date_arrived = $order["date_arrived"];
        $param_image_location = $order["image_location"];
        $param_status = $order["status"];
        $param_payment_method = $order["payment_method"];
        $param_unique_key = $order["unique_key"];
        $param_is_archived = $order["is_archived"];

        if(mysqli_stmt_execute($stmt)) {
            return true;
        }
    }
    return false;
}
function read_order($id) {
    GLOBAL $link;
    $order = to_associative(array("id", "user_id", "product_id", "quantity", "total", "date_ordered", "date_arrived", "image_location", "status", "payment_method", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM orders WHERE id = "'. $id . '"';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($order as $key => $value) {
    	        $order[$key] = $row[$key];
            }
        }
    }
    return $order;
}
function read_orders() {
    GLOBAL $link;
    $orders = array();
    $index = 0;
    $sql = 'SELECT id, user_id, product_id, quantity, total, date_ordered, date_arrived, image_location, status, payment_method, unique_key, is_archived FROM orders WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $orders[$index] = $row;
            $index++;
        }
    }
    return $orders;
}
function count_orders() {
    GLOBAL $link;
    $sql = 'SELECT * FROM orders WHERE is_archived = 0';
    if($result = mysqli_query($link, $sql)) {
        return mysqli_num_rows($result);
    }
    return 0;
}
function delete_order($id) {
    GLOBAL $link;
    $sql = 'DELETE FROM orders WHERE id = "'.$id.'"';
    if(mysqli_query($link, $sql)) {
        return true;
    }
    return false;
}
function last_order() {
    GLOBAL $link;
    $order = to_associative(array("user_id", "product_id", "quantity", "total", "date_ordered", "date_arrived", "image_location", "status", "payment_method", "unique_key", "is_archived"));
    $sql = 'SELECT * FROM orders ORDER BY id DESC LIMIT 1';
    if($result = mysqli_query($link, $sql)) {
        while($row = mysqli_fetch_array($result)) {
    	    foreach($order as $key => $value) {
    	        $order[$key] = $row[$key];
            }
        }
    }
    return $order;
}

//END: ORDER

?>