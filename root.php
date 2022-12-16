<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "H2 Trading The Best Hardware Store";
            require_once('utility/header.php');
            load_css('login');

            auth_outside();

        ?>
    </head>
    <body>
        <?php
            $user = strtoassoc('username, password');
            $input = $user;
            $error = $user;

            if(is_post()) {
                post_validate();
                if(is_empty($error)) {
                    $admins = sql_get_results('SELECT * FROM admins');
                    if(count($admins) > 0) {
                        foreach($admins as $admin) {
                            if($admin['username'] == $user['username']) {
                                if(password_verify($user['password'], $admin['password'])) {
                                    $admin['password'] = $user['password'];
                                    $_SESSION['session'] = $admin;
                                    redirect_to('admin-dashboard');
                                } else {
                                    $error['username'] = 'Wrong username or password.';
                                    $error['password'] = 'Wrong username or password.';
                                }
                            } else {
                                $error['username'] = 'Wrong username or password.';
                                $error['password'] = 'Wrong username or password.';
                            }
                        }
                    } else {
                        $error['username'] = 'Undefined username';
                        $error['password'] = 'Underfined password';
                        $error['err'] = "No accounts registered";
                    }
                }
            }
        ?>
        <div class="wrapper">
            <div class="app-form">
                <div class="th-form-group">
                    <h1 class="app-form-title">Login</h1>
                    <p class="app-form-meta">Login as administrator.</p>
                    <div class="rounded-seperator"></div>
                    <?php
                        _builtinerror();
                        _builtinsuccess();
                    ?>
                </div>
                <form action="<?php echo self() ?>" method="post" class="app-inner-form">
                    <div class="th-form-group">
                        <p class="th-input-label">Username</p>
                        <input type="text" 
                        class="th-input-text <?php echo generate_error('username') ?>" 
                        name="username"
                        value="root">
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label">Password</p>
                        <input type="password" 
                        class="th-input-text <?php echo generate_error('password') ?>" 
                        name="password"
                        value="admin">
                    </div>
                    <div class="th-form-group">
                        <input type="submit" class="th-button th-color-blue btn-block" value="LOGIN">
                    </div>
                    <div class="th-form-group text-center">
                        <p>Back to <a href="index">2H-Trading</a></p>
                    </div>
                    <div class="social-links th-form-group text-center">
                        <a href="#">
                            <img src="repository/assets/images/facebook.png">
                        </a>
                        <a href="#">
                            <img src="repository/assets/images/twitter.png">
                        </a>
                        <a href="#">
                        <img src="repository/assets/images/instagram.png">
                        </a>
                    </div>
                    <div class="th-form-group text-center">
                        <p>2-H Trading Allright Reserved &copy; 2022</p>
                    </div>
                </form>
            </div>
        </div>
        <?php package_manager('script') ?>
        <?php load_script('theme') ?>
    </body>
</html>