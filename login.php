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
            $user = strtoassoc('email_address, password');
            $input = $user;
            $error = $user;

            if(is_post()) {
                post_validate();
                if(is_empty($error)) {
                    $users = sql_get_results('SELECT * FROM users WHERE is_archived = 0');
                    if(count($users) > 0) {
                        foreach($users as $tmp) {
                            if($tmp['email_address'] == $user['email_address']) {
                                if(password_verify($user['password'], $tmp['password'])) {
                                    $tmp['password'] = $user['password'];
                                    $_SESSION['session'] = $tmp;
                                    redirect_to('home');
                                } else {
                                    $error['password'] = 'Wrong username or password.';
                                }
                            } else {
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
                    <p class="app-form-meta">Login your account.</p>
                    <div class="rounded-seperator"></div>
                    <?php
                        _builtinerror();
                        _builtinsuccess();
                    ?>
                </div>
                <form action="<?php echo self() ?>" method="post" class="app-inner-form">
                    <div class="th-form-group">
                        <p class="th-input-label">Email Address</p>
                        <input type="text" 
                        class="th-input-text <?php echo generate_error('email_address') ?>" 
                        name="email_address"
                        value="<?php echo render_post('email_address') ?>">
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label">Password</p>
                        <input type="password" 
                        class="th-input-text <?php echo generate_error('password') ?>" 
                        name="password"
                        value="<?php echo render_post('password') ?>">
                    </div>
                    <div class="th-form-group">
                        <input type="submit" class="th-button th-color-blue btn-block" value="LOGIN">
                    </div>
                    <div class="th-form-group text-center">
                        <p>Don't have an account? <a href="register">Register Now</a></p>
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