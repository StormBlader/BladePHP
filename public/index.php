<?php
ini_set('display_errors','On');
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__.'/../BladePHP/Blade.php');

$uri = $_SERVER['REQUEST_URI'];
$query_str = $_SERVER['QUERY_STRING'];
$uri = str_replace('?' . $query_str, '', $uri);
$uri = trim($uri, '/');

if(empty($uri)) {
    $controller = 'App\\Controller\\IndexController';
    $action = 'index';
}else {
    $uriArr = explode('/', $uri);
    $controller = ucfirst($uriArr[0]);

    $controller = 'App\\Controller\\' . $controller . "Controller";
    $action = !isset($uriArr[1]) || is_null($uriArr[1]) ? 'index' : $uriArr[1];
}

if(class_exists($controller)) {
    $controller_obj = getInstance($controller);
    $controller_obj->$action();
}
