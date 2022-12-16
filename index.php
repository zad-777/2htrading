<!DOCTYPE html>
<html>
    <head>
        <?php
            $title = "H2 Trading The Best Hardware Store";
            require_once('utility/header.php');
            load_css('index-navbar');
            load_css('index');

            auth_outside();

        ?>
    </head>
</div>

    <body>
        <?php render_navbar('index'); ?>
        <div class="wrapper">
            <div class="svg-card">
                <div class="text-wrapper">
                    <h1>2H - Trading</h1>
                    <p> 
                    2H TRADING strives to be the country's primary source of high quality yet affordable brands of Hospital, Medical and Laboratory equipment & supplies.
        We are committed to satisfy applicable requirements, thus all employees are responsible for continual improvement of Quality Management System.
        We are dedicated to exceeding customer's expectation by providing the best services from our competent and highly motivated employees. Our goal is 100% customer satisfaction all the time.             </p>
                    <div class="round-seperator"></div>
                </div>
                <img src="repository/assets/images/scientist.svg">
            </div>
            <h1 class="item-title">Products</h1>
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card">
                        <div class="img-wrapper">
                            <img src="repository/assets/images/Yellow Top (4ml, 6ml).jpg">
                        </div>
                        <p class="name">Yellow Top (4ml, 6ml)</p>
                        <p class="description">For blood collecting tubes</p>
                        <p class="price">
                            <span>₱</span>
                            500
                        </p>
                        <a href="login" class="th-button th-button-outline th-color-blue w-25">
                            <i class="far fa-shopping-cart mr-2"></i>
                            SHOW
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card">
                        <div class="img-wrapper">
                            <img src="repository/assets/images/Red Top (With Clot Acvtivator 5ml).jpg">
                        </div>
                        <p class="name">Red Top (With Clot Acvtivator 5ml)</p>
                        <p class="description">For blood collecting tubes</p>
                        <p class="price">
                            <span>₱</span>
                            500
                        </p>
                        <a href="login" class="th-button th-button-outline th-color-blue w-25">
                            <i class="far fa-shopping-cart mr-2"></i>
                            SHOW
                        </a>
                        </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card">
                        <div class="img-wrapper">
                            <img src="repository/assets/images/Green top, Heparin lithium, 5ml PET (100 PiecesTray).jpg">
                        </div>
                        <p class="name">Green top, Heparin lithium, 5ml PET</p>
                        <p class="description">For blood collecting tubes</p>
                        <p class="price">
                            <span>₱</span>
                            500
                        </p>
                        <a href ="login" class="th-button th-button-outline th-color-blue w-25">
                            <i class="far fa-shopping-cart mr-2"></i>
                            SHOW
                        </a>
                        </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card">
                        <div class="img-wrapper">
                            <img src="repository/assets/images/EDTA Microtainer 0.5ml.jpg">
                        </div>
                        <p class="name">EDTA Microtainer 0.5ml</p>
                        <p class="description">For blood collecting tubes</p>
                        <p class="price">
                            <span>₱</span>
                            500
                        </p>
                        <a href = "login" class="th-button th-button-outline th-color-blue w-25">
                            <i class="far fa-shopping-cart mr-2"></i>
                            SHOW
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <p>2-H Trading Allright Reserved &copy; 2022</p>
            
        </footer>
        <?php package_manager('script') ?>
    </body>
</html>