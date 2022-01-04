<?php
    // Load Config
    require_once 'config/config.php';

    // Load Helpers
    require_once 'helpers/session_helper.php';
    require_once 'helpers/sizing_helper.php';
    require_once 'helpers/url_helper.php';
    require_once 'helpers/validate_helper.php';
    require_once 'helpers/file_helper.php';
    require_once 'helpers/sort_helper.php';
    require_once 'helpers/model_helper.php';
    require_once 'helpers/helper_functions.php';

    // Autoload Core Libraries
    spl_autoload_register(function($className) {
        require_once 'libraries/' . $className . '.php';
    });