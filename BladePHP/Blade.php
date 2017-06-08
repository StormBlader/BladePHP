<?php

session_start();
ini_set('display_errors','Off');

defined('ROOT') or define('ROOT',str_replace('\\','/',dirname(__DIR__).'/'));
defined('BLADEPHP') or define('BLADEPHP',str_replace('\\','/',dirname(__DIR__).'/BladePHP/'));

require_once(BLADEPHP.'Common/constant.php');
require_once(BLADEPHP.'Common/config.php');
require_once(BLADEPHP.'Common/functions.php');

/**
function __autoload($obj) {
	$obj = str_replace('\\', '/', $obj); 
    $obj_file = ROOT . $obj . ".php";
    if(is_file($obj_file)) {
        require_once($obj_file);
    }else {
        $obj_file = BLADEPHP . $obj . ".class.php";
        if(is_file($obj_file)) {
            require_once($obj_file);
        }
    }
}
**/
