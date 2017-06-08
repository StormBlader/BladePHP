<?php
namespace Lib;

Class Controller {

    /**
     * 视图实例对象
     * @var view
     */    
	protected $view;

	/**
     * 构造函数 实例化视图对象
     */
	function __construct() {

		$this->view = getInstance('Lib\View');
        if(method_exists($this,'_initialize'))
            $this->_initialize();
	}

	/**
     * 视图变量赋值
     * @param $name
     * @param $value
     */
    public function assign($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $key => $data) {
                $this->view->set($key, $data);
            }
        } else {
            $this->view->set($name, $value);
        }
    }

	/**
     * 视图渲染
     * @param string $name
     */
    public function display($name = null) {
        echo $this->view->render($name);
    }

	/**
	 * 获得表单提交的数据
	 * @return 表单数据组成的数组
	 */
	protected function getFormParams(){
		$params = null;
		eval ("\$params = \$_{$_SERVER['REQUEST_METHOD']};");
		return $params;
	}
}
