<?php
namespace Lib;

Class View {

    /**
     * 视图变量
     * @var vars
     */ 
	protected $vars = array();

    /**
     * 视图变量赋值
     * @param $key
     * @param $value
     */
    public function set($key, $value = null) {
        $this->vars[$key] = $value;
    }

    /**
     * 渲梁视图文件
     * @param String $name
     * @return string
     */
    public function render($name = null) {
        extract($this->vars, EXTR_SKIP);
        ob_start();
        ob_implicit_flush(0);
        require_once(ROOT.$name);
        return ob_get_clean();
    }

	/**
	 * 分页计算
	 * @param String $count 数据集条数
	 * @param String $orderField 排序字段
	 * @param String $order 排序方式
	 * @return Array 
	 */
	public function page($count, $order_field='id', $order_direction='desc') {
		// 当前页
		if(!empty($_POST['pageNum']))
			$page_num = (int) $_POST['pageNum'];
		$page_num = (empty ($page_num)) ? 0 : $page_num;
		
		// 每页条数
		if(!empty($_POST['numPerPage']))
			$num_per_page = (int) $_POST['numPerPage'];
		$num_per_page = (empty ($num_per_page)) ? 20 : $num_per_page;
		
		if(!empty($_POST['orderField']))
			$order_field =  $_POST['orderField'];
		
		if(!empty($_POST['orderDirection']))
			$order_direction = $_POST['orderDirection'];
		
		$page = array (
			'totalCount' => $count,
			'pageNumShown' => 10,
			'numPerPage' => $num_per_page,
			'pageNum' => (empty($page_num)) ? 1 : $page_num,
			'limit' => (($page_num > 0) ? ($page_num-1)*$num_per_page : $page_num),
			'orderDirection' => $order_direction,
			'orderField' => $order_field,
			'orderFieldStr' => "$order_field $order_direction"
		);
		return $page;
	}
}
