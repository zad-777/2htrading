<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "H2 Trading The Best Hardware Store";
            require_once('utility/header.php');
            load_css('register');

            auth_outside();

            $verification = auth_session('verification', 'index');
            
        ?>
    </head>
    <body>
        <?php
            $user = strtoassoc('code');
            $input = $user;
            $error = $user;
            if(server('REQUEST_METHOD') === 'POST') {
                post_validate();
                if(is_empty($error)) {
                    $code = $user['code'];
                    $user = $verification['user'];
                    $user['code'] = $code;
                    $student = sql_get_result("SELECT * FROM users WHERE unique_key = '{$user['unique_key']}'");
                    if($verification['code'] == $user['code']) {
                        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
                        if(insert_user($user)) {
                            $_SESSION['success'] = array("Your account is successfully registered!");
                            redirect_to("login");
                        }
                    } else {
                        $error['code'] = "The code you've been input does not match";
                    }
                }
            }
        ?>
        <div class="wrapper">
            <div class="app-form verification">
                <div class="th-form-group">
                    <h1 class="app-form-title">Verification</h1>
                    <p class="app-form-meta">Hi, one step to your register your account.</p>
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
                        value="<?php echo $verification['email'] ?>" readonly>
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label">Verification Code</p>
                        <input type="text" 
                        class="th-input-text <?php echo generate_error('email_address') ?>" 
                        name="code"
                        value="">
                    </div>
                    <div class="th-form-group">
                        <input type="submit" class="th-button th-color-blue btn-block" value="REGISTER">
                    </div>
                    <div class="th-form-group text-center">
                        <p>Already have an account? <a href="login">Login Now</a></p>
                    </div>
                </form>
            </div>
        </div>
        <?php package_manager('script') ?>
        <?php load_script('theme') ?>
    </body>
</html>