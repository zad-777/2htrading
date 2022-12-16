<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "H2 Trading The Best Hardware Store";
            require_once('utility/header.php');
            load_css('index-navbar');
            load_css('index');
            load_css('contacts');
      


            auth_outside();

        ?>
    </head>
    <body>
        <?php render_navbar('index'); ?>
        <div class="wrapper">
            
          <h2>Contact Us</h2>
          <ul>
            <li>Email : twohtrading@yahoo.com</li>
            <li>Cellphone No. : 09228459029</li>
            <li>Telephone No. : 87889302</li>
        </div>
        <footer>
            <p>2-H Trading Allright Reserved &copy; 2022</p>
            
        </footer>
        <?php package_manager('script') ?>
    </body>
</html>