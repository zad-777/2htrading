<?php

    require_once('global.php');
    require_once('constants.php');
    require_once($web_tinker_path);

    GLOBAL $title;

    echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '  <meta charset="UTF-8">';
    echo '  <title>'.WT_PROJECT_TITLE.' | '. $title .'</title>';
    echo "  <link rel='icon' type='image/png' href='repository/assets/images/logo.png'>";
    
    package_manager('style');
    
    load_css('app');
    load_css('theme');

    require_once('util.php');

?>