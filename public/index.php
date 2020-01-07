<?php
session_start();
require_once "../controllers/c_users.php";
require_once "../controllers/c_courses.php";
require_once "../controllers/c_choices.php";
require_once "../controllers/c_trades.php";
require_once "../controllers/c_notifications.php";

$count = substr_count ($_SERVER["REQUEST_URI"], "/");

switch ($count) {
    case 1:
        $controller_name = explode('/', $_SERVER["REQUEST_URI"])[1];
        if ($controller_name == '') {
            $controller_name = 'courses';
        }
        $method_name = 'display';
        break;
    case 2:
        $controller_name = explode('/', $_SERVER["REQUEST_URI"])[1];
        $method_name = explode('/', $_SERVER["REQUEST_URI"])[2];

        if ($method_name == '') {
            $method_name = 'display';
        }
        break;
    case 3:
        $controller_name = explode('/', $_SERVER["REQUEST_URI"])[1];
        $method_name = explode('/', $_SERVER["REQUEST_URI"])[2];
        $param_value = explode('/', $_SERVER["REQUEST_URI"])[3];
        break;
    default:
        break;
}

$controller = new $controller_name();

if ($count == 3) {    
    $controller->$method_name($param_value);
} else {    
    $controller->$method_name();
}
?>