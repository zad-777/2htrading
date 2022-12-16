<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "H2 Trading The Best Hardware Store";
            require_once('utility/header.php');
            load_css('register');

            auth_outside();
            
        ?>
    </head>
    <body>
        <?php
            $user = strtoassoc('first_name, middle_name, last_name, contact_number, home_address, email_address, password, re_type_password');
            $input = $user;
            $error = $user;

            not_required('middle_name');

            if(is_post()) {
                post_validate();
                if(is_empty($error)) {
                    $has_same = false;
                    $users = sql_get_results("SELECT * FROM users WHERE is_archived = 0");
                    foreach($users as $tmp) {
                        if($tmp['email_address'] == $user['email_address']) {
                            $has_same = true;
                            $error['err'] = "The <b>{$user['email_address']}</b> is already taken.";
                            break;
                        }
                    }
                    if(!$has_same) {
                        $user['type'] = 2;
                        $user['unique_key'] = sql_unique_key('users');
                        $user['is_archived'] = 0;
                        $code = random_int(100000, 999999);
                        $sender = array(
                            'email' => '2htrading2022@gmail.com',
                            'password' => 'plcfaagdeufgkvlx',
                            'from' => 'H2 Trading',
                            'subject' => 'Verification Code',
                            'message' => 
                            "
                            Hi, this is your verification code: <b>$code</b>

                            "
                        );
                        if(send_email($sender, $user['email_address'])) {
                            $_SESSION['verification'] = array(
                                'email' => $user['email_address'],
                                'code' => $code,
                                'user' => $user
                            );
                            $_SESSION['success'] = array("The verification code is sent to your email address, please check it.");
                            redirect_to('verification');
                        } else {
                            $error['email'] = 'Something error has an occur in sending email';
                        }
                    }
                }
            }
        ?>
        <div class="wrapper">
            <div class="app-form">
                <div class="th-form-group">
                    <h1 class="app-form-title">Register</h1>
                    <p class="app-form-meta">Create a new account.</p>
                    <div class="rounded-seperator"></div>
                    <?php
                        _builtinerror();
                        _builtinsuccess();
                    ?>
                </div>
                <form action="<?php echo self() ?>" method="post" class="app-inner-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="th-form-group">
                                <p class="th-input-label">First Name</p>
                                <input type="text" 
                                class="th-input-text <?php echo generate_error('first_name') ?>" 
                                name="first_name"
                                value="<?php echo render_post('first_name') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="th-form-group">
                                <p class="th-input-label">Middle Name</p>
                                <input type="text" 
                                class="th-input-text <?php echo generate_error('middle_name') ?>" 
                                name="middle_name"
                                value="<?php echo render_post('middle_name') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="th-form-group">
                                <p class="th-input-label">Last Name</p>
                                <input type="text" 
                                class="th-input-text <?php echo generate_error('last_name') ?>" 
                                name="last_name"
                                value="<?php echo render_post('last_name') ?>">
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
                                value="<?php echo render_post('contact_number') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="th-form-group">
                                <p class="th-input-label">Home Address</p>
                                <input type="text" 
                                class="th-input-text <?php echo generate_error('home_address') ?>" 
                                name="home_address"
                                value="<?php echo render_post('home_address') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label">Email Address</p>
                        <input type="text" 
                        class="th-input-text <?php echo generate_error('email_address') ?>" 
                        name="email_address"
                        value="<?php echo render_post('email_address') ?>">
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label">Password</p>
                        <div class="th-password-wrapper">
                            <input type="password" 
                            class="th-input-password <?php echo generate_error('password') ?>" 
                            name="password"
                            value="<?php echo render_post('password') ?>">
                        </div>
                    </div>
                    <div class="th-form-group">
                        <p class="th-input-label">Re-type Password</p>
                        <div class="th-password-wrapper">
                            <input type="password" 
                            class="th-input-password <?php echo generate_error('re_type_password') ?>" 
                            name="re_type_password"
                            value="<?php echo render_post('re_type_password') ?>">
                        </div>
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