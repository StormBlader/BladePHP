<?php
define('isInSite',true);
require_once(__DIR__ . '/../vendor/autoload.php');
ini_set('display_errors','On');
require_once(__DIR__.'/../BladePHP/Blade.php');
if ( ! defined('isInSite')) die('No Access');

$uri = isset($_GET['_url']) ? $_GET['_url'] : '';
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

