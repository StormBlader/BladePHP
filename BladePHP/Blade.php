<?php
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

session_start();
ini_set('display_errors','Off');

defined('ROOT') or define('ROOT',str_replace('\\','/',dirname(__DIR__).'/'));
defined('BLADEPHP') or define('BLADEPHP',str_replace('\\','/',dirname(__DIR__).'/BladePHP/'));

require_once(BLADEPHP.'Common/constant.php');
require_once(BLADEPHP.'Common/config.php');
require_once(BLADEPHP.'Common/functions.php');

$capsule = new Capsule;

// 创建链接
$capsule->addConnection($GLOBALS['db']);

// 设置全局静态可访问DB
$capsule->setAsGlobal();

// 启动Eloquent
$capsule->bootEloquent();

