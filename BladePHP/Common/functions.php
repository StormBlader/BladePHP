<?php
/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function A($name=null, $value=null,$default=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtoupper($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
        return;
    }
    return null; // 避免非法参数
}

/**
 * 打开一个链接
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */

function R($url, $time=0, $message='') {
	header("location:$url");
	exit();
}

/**
 *
 * 导入文件
 * @param string $class
 * @param string $ext
 * @param string $baseUrl
 * @return mixed
 */
function import($class, $ext = '.class.php', $baseUrl = ROOT) {
    static $_importFiles = array();
    $classfile = $baseUrl.str_replace('.', '/', $class).$ext;
    if(!isset($_importFiles[$classfile])) {
        if(is_file($classfile)) {
            $_importFiles[$classfile] = require ($classfile);
        } else {
            return false;
        }
    }
    return $_importFiles[$classfile];
}

/**
 * 创建或获取实例
 */
function getInstance($class_name){
	if(!isset($GLOBALS['obj'][$class_name])) {;
		$GLOBALS['obj'][$class_name] = new $class_name();
		return $GLOBALS['obj'][$class_name];
	} else 
		return $GLOBALS['obj'][$class_name];
}

/**
 * 获取客户端IP
 */
function get_client_ip() {
	if (getenv ('HTTP_CLIENT_IP') && strcasecmp ( getenv ('HTTP_CLIENT_IP'), 'unknown' ))
		$ip = getenv ( 'HTTP_CLIENT_IP' );
	else if (getenv ('HTTP_X_FORWARDED_FOR') && strcasecmp ( getenv ('HTTP_X_FORWARDED_FOR'), 'unknown'))
		$ip = getenv ('HTTP_X_FORWARDED_FOR');
	else if (getenv ('REMOTE_ADDR') && strcasecmp ( getenv ('REMOTE_ADDR'), 'unknown'))
		$ip = getenv ('REMOTE_ADDR');
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown'))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = 'unknown';
	return ($ip);
}

/**
 * 自动转换字符集 支持数组转换
 *
 * @param string $fContents
 * @param string $from
 * @param string $to
 * @return string
 */
function auto_charset($fContents,$from='gbk',$to='utf-8')
{
    $from   =  strtoupper($from)=='UTF8'? 'utf-8':$from;
    $to       =  strtoupper($to)=='UTF8'? 'utf-8':$to;
    if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) )
    {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if(is_string($fContents) )
    {
        if(function_exists('mb_convert_encoding'))
        {
            return mb_convert_encoding ($fContents, $to, $from);
        }
        elseif(function_exists('iconv'))
        {
            return iconv($from,$to,$fContents);
        }
        else
        {
            return $fContents;
        }
    }
    elseif(is_array($fContents))
    {
        foreach ( $fContents as $key => $val )
        {
            $_key = auto_charset($key,$from,$to);
            $fContents[$_key] = auto_charset($val,$from,$to);
            if($key != $_key ) unset($fContents[$key]);
        }
        return $fContents;
    }
    else
    {
        return $fContents;
    }
}

/**
 * 获得表单提交的参数$_POST
 * @return array
 * 2015-02-01
 */
function P($name = null) {
	$params = array();
	if($name === null) $params = $_POST;
	elseif(isset($_POST[$name])) $params = $_POST[$name];
	return $params;
}

/**
 * 获得表单提交的参数$_GET
 * @return array
 * 2015-02-01
 */
function G($name = null) {
	$params = array();
	if($name === null) $params = $_GET;
	elseif(isset($_GET[$name])) $params = $_GET[$name];
	return $params;
}

/**
 * 过滤数组中的空值
 * @param string $val
 * @return bool
 */
function arrayFilterVal($val) {
	return $val === '' ? false : true;
}

/**
 * 获得表单提交的数据
 * @return 表单数据组成的数组
 * 2015-02-01
 */
function getFormParams(){
	$params = array();
	switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
		case 'POST':
			$params = $_POST;
			break;
		case 'GET':
			$params = $_GET;
	}
	return $params;
}


/**
 * where语句数据类型转换
 * @return string
 * 2015-02-01
 */
function convertWhere($type, $field, $val, $isLike = true){
	if(strpos($type, 'int') !== false){
		$sql = $field .'='. (int)$val;
	}elseif(in_array($type, array('float', 'double'))){
		$sql = $field .'='. (float)$val;
	}elseif($type == 'datetime'){
		$sql = "DATE_FORMAT($field, '%Y-%m-%d')='$val'";
	}else{
		$sql = ($isLike) ? "{$field} LIKE '%{$val}%'" : "$field='{$val}'";
	}
	return $sql;
}
	
/**
 * CURL发送请求
 *
 * @param string $url
 * @param mixed $data
 * @param string $method
 * @param string $cookieFile
 * @param array $headers
 * @param int $connectTimeout
 * @param int $readTimeout
 */
function curlRequest($url,$data='',$method='POST',$cookieFile='',$headers='',$connectTimeout = 30,$readTimeout = 30)
{ 
    $method = strtoupper($method);
    if(!function_exists('curl_init')) return socketRequest($url, $data, $method, $cookieFile, $connectTimeout);

    $option = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => $connectTimeout,
        CURLOPT_TIMEOUT => $readTimeout
    );

    if($headers) $option[CURLOPT_HTTPHEADER] = $headers;

    if($cookieFile)
    {
        $option[CURLOPT_COOKIEJAR] = $cookieFile;
        $option[CURLOPT_COOKIEFILE] = $cookieFile;
    }

    if($data && strtolower($method) == 'post')
    {
        $option[CURLOPT_POST] = 1;
        $option[CURLOPT_POSTFIELDS] = $data;
    }
	
	if(stripos($url, 'https://') !== false)
    {
    	$option[CURLOPT_SSL_VERIFYPEER] = false;
    	$option[CURLOPT_SSL_VERIFYHOST] = false;
    }
    
    $ch = curl_init();
    curl_setopt_array($ch,$option);
    $response = curl_exec($ch);
    if(curl_errno($ch) > 0) throw_exception("CURL ERROR:$url ".curl_error($ch));
    curl_close($ch);
    return $response;
}

/**
 * 检测移动端访问
 *
 */
function is_mobile_request() {  
	$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
	$mobile_browser = '0';  
	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
		$mobile_browser++;  
	if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
		$mobile_browser++;  
	if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
		$mobile_browser++; 
	if(isset($_SERVER['HTTP_PROFILE'])) 
		$mobile_browser++; 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)); 
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda','xda-'
    );  
	if(in_array($mobile_ua, $mobile_agents))
		$mobile_browser++;
	if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
		$mobile_browser++;
	// Pre-final check to reset everything if the user is on Windows 
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
		$mobile_browser=0; 
	// But WP7 is also Windows, with a slightly different characteristic 
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
		$mobile_browser++;
	if($mobile_browser>0)
		return true;
	else
		return false;
}
