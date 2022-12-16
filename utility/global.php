<?php

    /**
    *
    * PROJECT STATE
    *
    * 1 - Development
    * 2 - Standalone Application
    * 3 - Online Application 
    *
    **/

    /**
     * 
     * TAKE NOTE:
     * 
     * hostinger : remove project name 
     * 
     * 
     */

    # Project

    define('WT_PROJECT_NAME', 'webtinker_project');
    define('WT_PROJECT_TITLE', '2H Trading'); # use for web title
    define('WT_PROJECT_FRAMEWORK_VERSION', '3.7');
    define('WT_PROJECT_STATE', 2);

    # Database

    define('WT_DATABASE_HOST', 'localhost'); # don't change
    define('WT_DATABASE_USERNAME', 'root');
    define('WT_DATABASE_PASSWORD', '');
    define('WT_DATABASE_NAME', '2htrading');

    $web_tinker_path = "";
    $res_path = "";

    if(WT_PROJECT_STATE == 1) {
        $web_tinker_path = "../repository/web-tinker-v".WT_PROJECT_FRAMEWORK_VERSION."/web-tinker.php";
        $res_path = "/repository/libraries/";
    }
    elseif(WT_PROJECT_STATE == 2) {
        $web_tinker_path = "repository/web-tinker-v".WT_PROJECT_FRAMEWORK_VERSION."/web-tinker.php";
        $res_path = "/repository/libraries/";
    }
    elseif(WT_PROJECT_STATE == 3) {
        $web_tinker_path = "repository/web-tinker-v".WT_PROJECT_FRAMEWORK_VERSION."/web-tinker.php"; // put all files in the public_html : best in hostinger
        $res_path = "repository/libraries/";
    }
         
    date_default_timezone_set('Asia/Manila');
    
?>