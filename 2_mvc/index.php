<?php
//$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
//$script_name = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
//define ('__ROOT__', $root.$script_name);
spl_autoload_register(function ($className) {
    if (file_exists('System/' . $className . '.php')) {
        require_once 'System/' . $className . '.php';
    }
    else if (file_exists('Controllers/' . $className . '.php')) {
        require_once 'Controllers/' . $className . '.php';
    }
    else if (file_exists('Models/' . $className . '.php')) {
        require_once 'Models/' . $className . '.php';
    }
    else if (file_exists($className . '.php')) {
        require_once $className . '.php';
    }
});

new Bootstrap();
