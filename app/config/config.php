<?php
    // DB Params
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'fileshare');

    // ProjectRoot (relative path)
    define('PROJECTROOT', basename(dirname(__FILE__, 3)));
    
    // Example: htdocs folder for apache server
    define('SERVERPUBLICROOT', dirname(__FILE__, 4));

    // App Root
    define('APPROOT', dirname(__FILE__, 2));

    // Url Root
    define('URLROOT', 'http://localhost/fileshare');

    // Site Name
    define('SITENAME', 'FileShare');

    // App Settings
    // Sizes in Byte
    define("FREE_STORAGE_SIZE", '21474836480'); // 20 GB
    define("FREE_FILE_SIZE", '2147483648'); // 2GB

    // Files Root Directory
    define('FILESROOT', dirname(APPROOT) . '\uploadedfiles');