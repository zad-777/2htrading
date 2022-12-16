<!DOCTYPE html>
<html>
    <head>
        <?php
        
            $title = "H2 Trading The Best Hardware Store";
            $index = 3;
            require_once('utility/header.php');

            load_css('index-navbar');
            load_css('profile');
            load_css('app-empty-wrapper');
            load_css('profile-card');

            auth_user();
            
            $session = auth_session('session', 'sign-out');

            $user = strtoassoc('first_name, middle_name, last_name, contact_number, home_address, password, re_type_password');
            $input = $user;
            $error = $user;

            if(is_post()) {
                post_validate();
                if(is_empty($error)) {
                    if($user['password'] == $user['re_type_password']) {
                        $sql = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, contact_number = ?, home_address = ?, password = ? WHERE id = ?";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ssssssi", $param_first_name, $param_middle_name, $param_last_name, $param_contact_number, $param_home_address, $param_password, $param_id);
                            $param_first_name = $user["first_name"];
                            $param_middle_name = $user["middle_name"];
                            $param_last_name = $user["last_name"];
                            $param_contact_number = $user["contact_number"];
                            $param_home_address = $user["home_address"];
                            $param_password = password_hash($user["password"], PASSWORD_DEFAULT);
                            $param_id = $session["id"];
                            if(mysqli_stmt_execute($stmt)) {
                                $id = $session['id'];
                                unset($_SESSION['session']);
                                $tmp = read_user($id);
                                $tmp['password'] = $user['password'];
                                $_SESSION['session'] = $tmp;
                                $_SESSION["success"] = array("Profile successfully updated.");
                                redirect_to('profile');
                            }
                        }
                    } else {
                        $error['err'] = "Password doesn't match.";
                    }
                }
            }

        ?>
    </head>
    <body>
        <?php render_navbar('user'); ?>
        <div class="wrapper">
            <h1 class="page-title">My Profile</h1>
            <br/>
            <?php
                _builtinerror();
                _builtinsuccess();
            ?>
            <br/>
            <form action="<?php echo self() ?>" method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="th-form-group">
                            <p class="th-input-label">First Name</p>
                            <input type="text" 
                            class="th-input-text <?php echo generate_error('first_name') ?>" 
                            name="first_name"
                            value="<?php echo $session['first_name'] ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="th-form-group">
                            <p class="th-input-label">Middle Name</p>
                            <input type="text" 
                            class="th-input-text <?php echo generate_error('middle_name') ?>" 
                            name="middle_name"
                            value="<?php echo $session['middle_name'] ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="th-form-group">
                            <p class="th-input-label">Last Name</p>
                            <input type="text" 
                            class="th-input-text <?php echo generate_error('last_name') ?>" 
                            name="last_name"
                            value="<?php echo $session['last_name'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="th-form-group">
                            <p class="th-input-label">Contact Number</p>
                            <input type="text" 
                            class="th-input-text <?php echo generate_error('contact_number') ?>" 
                            name="contact_number"
                            value="<?php echo $session['contact_number'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="th-form-group">
                            <p class="th-input-label">Home Address</p>
                            <input type="text" 
                            class="th-input-text <?php echo generate_error('home_address') ?>" 
                            name="home_address"
                            value="<?php echo $session['home_address'] ?>">
                        </div>
                    </div>
                </div>
                <div class="th-form-group">
                    <p class="th-input-label">Email Address</p>
                    <input type="text" 
                    class="th-input-text <?php echo generate_error('email_address') ?>" 
                    name="email_address"
                    value="<?php echo $session['email_address'] ?>" disabled>
                </div>
                <div class="th-form-group">
                    <p class="th-input-label">Password</p>
                    <div class="th-password-wrapper">
                        <input type="password" 
                        class="th-input-password <?php echo generate_error('password') ?>" 
                        name="password"
                        value="<?php echo $session['password'] ?>">
                    </div>
                </div>
                <div class="th-form-group">
                    <p class="th-input-label">Re-type Password</p>
                    <div class="th-password-wrapper">
                        <input type="password" 
                        class="th-input-password <?php echo generate_error('re_type_password') ?>" 
                        name="re_type_password"
                        value="">
                    </div>
                </div>
                <div class="th-form-group">
                    <input type="submit" class="th-button th-color-blue btn-block" value="UPDATE PROFILE">
                </div>
            </form>
        </div>
        <footer>
            <p>2-H Trading Allright Reserved &copy; 2022</p>
            <div>
                <a href="#">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </footer>
        <?php 
            package_manager('script');
            load_script('home-search-products');
        ?>
    </body>
</html>