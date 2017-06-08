<?php
namespace Lib;
		
Class Db {

	//用于存储数据库连接    
	protected $conn; 
	
	//连接数据库
	public function __construct() {
		$this->connect();
	}

    /**
     * 连接数据库
     */
	function connect() {
		$db = $GLOBALS['db'];
		$this->conn = mysqli_connect($db['host'].':'.$db['port'],$db['user'], $db['password'],$db['name']);
		mysqli_query($this->conn,'set names utf8');
	}

    /**
     * 执行sql语句
     * @access public
     * @param string $sql  sql语句
     * @return mixed
     */
    public function execute($sql) {
		if($this->conn!=null){
			$result = mysqli_query($this->conn,$sql);
		}
		if(!$result) {
			return false;
		}else if(isset($result->num_rows)) {
			if($result->num_rows == 0) {
				return array();
			}elseif($result->num_rows >= 1) {
				while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
					$rows[] = $row;
				}
				return $rows;
			}
		}else {
			return true;
		}
	}

    /**
     * 执行sql语句
     * @access public
     * @param string $sql  sql语句
     * @return mixed
     */
    public function query($sql) {
		if($this->conn!=null){
			$result = mysqli_query($this->conn,$sql);
		}
		if(!$result) {
			return false;
		}else{
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			return $row;
		}
	}

	/**
     * 查询数据表的字段
     * @access public
     * @param string $sql  sql语句
     * @return mixed
     */
    public function getFields($sql) {
		if($this->conn!=null){
			$result = mysqli_query($this->conn,$sql);
			while($result!==false&&($row=mysqli_fetch_array($result))!=null){
				//$columns[] = $row[0];
				$columns[] = $row;
			}
            foreach($columns as $k=>$field) {
                $fileds[$field['Field']] = array(
					'name' => $field['Field'],
					'type' => preg_replace('/\(\d+\)/', '', $field['Type']),
					'notnull' => (strtolower($field['Null']) == 'yes'),
					'default' => $field['Default'],
					'primary' => (strtolower($field['Key']) == 'pri'),
					'autoinc' => (strtolower($field['Extra']) == 'auto_increment')
                );
            }
			return $fileds;
		}
	}

	/**
     * 析构方法
     * @access public
     */
    public function __destruct() {
		$this->conn = null;
	}
		
}
