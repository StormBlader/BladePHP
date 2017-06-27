<?php
namespace App\Controller;

class IndexController extends BaseController 
{
	public function index() {
		$title = 'Hello World!';
		$this->assign('title', $title);
		$this->display('View/index.php');
	}

}
?>
