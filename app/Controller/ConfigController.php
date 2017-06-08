<?php
namespace App\Controller;
use BladePHP\Lib\Controller;

class ConfigController extends Controller 
{
	private $config_model;

	public function _initialize(){
		$this->config_model = getInstance('Model\ConfigModel');
	}

	public function index() {
		$title = 'Hello World!';
		$config = $this->config_model->find();
		$this->assign('title',$title);
		$this->assign('config',$config);
		$this->display('View/index.php');
	}

}
?>
