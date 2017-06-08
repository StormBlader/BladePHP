<?php
namespace App\Controller;
use Lib\Controller;

class IndexController extends Controller 
{
	private $config_model;

	public function _initialize(){
		$this->config_model = getInstance('App\Model\ConfigModel');
	}

	public function index() {
		$title = 'Hello World!';
		$config = $this->config_model->find(2);
		$this->assign('title',$title);
		$this->display('View/index.php');
	}

}
?>
