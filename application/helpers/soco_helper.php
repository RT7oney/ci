<?php
/**
 * 公共辅助函数
 * @author Ryan <ryantyler423@gmail.com>
 */
if (!function_exists('__GetIp')) {
	/**
	 * 获取客户端ip方法
	 * @return [type] [用户IP地址]
	 */
	function __GetIp() {
		static $realIP;
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$realIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$realIP = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$realIP = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$realIP = getenv("HTTP_X_FORWARDED_FOR");
			} else if (getenv("HTTP_CLIENT_IP")) {
				$realIP = getenv("HTTP_CLIENT_IP");
			} else {
				$realIP = getenv("REMOTE_ADDR");
			}
		}
		return $realIP;
	}
}

if (!function_exists('__RandStr')) {
	/**
	 * 获取定长的随机字符串
	 * @param  integer $length [需要获取的随机字符串的长度]
	 * @return [string]          [随机字符串]
	 */
	function __RandStr($length = 10) {
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol) - 1;

		for ($i = 0; $i < $length; $i++) {
			$str .= $strPol[rand(0, $max)];
		}
		return $str;
	}
}

if (!function_exists('__Microtime')) {
	/**
	 * 获取当前时间戳，精确到毫秒
	 * @return [type] [毫秒化的时间戳]
	 */
	function __Microtime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float) $usec + (float) $sec);
	}
}

if (!function_exists('__MicrotimeFormat')) {
	/**
	 * 格式化时间戳，精确到毫秒，x代表毫秒
	 * __MicrotimeFormat('Y年m月d日 H时i分s秒 x毫秒', 1270626578.66000000)
	 * @param  [string] $tag  [格式化的标准格式]
	 * @param  [string] $time [时间，传入毫秒时间]
	 * @return [type]       [description]
	 */
	function __MicrotimeFormat($tag, $time) {
		list($usec, $sec) = explode(".", $time);
		$date = date($tag, $usec);
		return str_replace('x', $sec, $date);
	}
}

if (!function_exists('__SearchArray')) {
	/**
	 * 搜索数组高级方法，可以搜索多维数组
	 * @param  [array] $array [查询基准数组]
	 * @param  [string] $v     [需要查询的值]
	 * @return [array]        [查询结果]
	 */
	function __SearchArray($array, $v) {
		$data = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = search($value, $v);
				if (!empty($result)) {
					$data[$key] = $result;
				}
			} else {
				if ($value == $v) {
					$data[$key] = $v;
				}
			}
		}
		return $data;
	}
}

if (!function_exists('__IsPhone')) {
	/**
	 * 判断是否是手机号
	 * @param  [string] $phone [手机号]
	 * @return [boolen]        [检查结果]
	 */
	function __IsPhone($phone) {
		return preg_match("/^1[34578]\d{9}$/", $phone) ? true : false;
	}
}

if (!function_exists('__IsEmail')) {
	/**
	 * 判断是否是合法的邮箱
	 * @param  [string] $email [用户输入的邮箱]
	 * @return [boolen]        [检查结果]
	 */
	function __IsEmail($email) {
		if (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $email)) {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('__IsIdCard')) {
	/**
	 * 判断是否是身份证号码
	 * @param  [string] $id_card [身份证号码]
	 * @return [boolen]          [检查结果]
	 */
	function __IsIdCard($id_card) {
		$City = array(
			'11', '12', '13', '14', '15', '21', '22',
			'23', '31', '32', '33', '34', '35', '36',
			'37', '41', '42', '43', '44', '45', '46',
			'50', '51', '52', '53', '54', '61', '62',
			'63', '64', '65', '71', '81', '82', '91',
		);

		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $Str)) {
			return false;
		}

		if (!in_array(substr($Str, 0, 2), $City)) {
			return false;
		}

		$Str = preg_replace('/[xX]$/i', 'a', $Str);
		$Length = strlen($Str);

		if ($Length == 18) {
			$Birthday = substr($Str, 6, 4) . '-' . substr($Str, 10, 2) . '-' . substr($Str, 12, 2);
		} else {
			$Birthday = '19' . substr($Str, 6, 2) . '-' . substr($Str, 8, 2) . '-' . substr($Str, 10, 2);
		}

		if (date('Y-m-d', strtotime($Birthday)) != $Birthday) {
			return false;
		}

		if ($Length == 18) {
			$Sum = 0;

			for ($i = 17; $i >= 0; $i--) {
				$SubStr = substr($Str, 17 - $i, 1);
				$Sum += (pow(2, $i) % 11) * (($SubStr == 'a') ? 10 : intval($SubStr, 11));
			}

			if ($Sum % 11 != 1) {
				return false;
			}

		}

		return true;
	}
}

if (!function_exists('__SaltPassword')) {
	/**
	 * 对密码进行加盐hash
	 * @param  [string] $register_password [用户的提交的原始密码]
	 * @return [array]                    [盐值，和加盐后的密码，请开发者将这两个值存入数据库]
	 */
	function __SaltPassword($password) {
		$salt = base64_encode(mcrypt_create_iv(32, MCRYPT_DEV_RANDOM));
		$password = sha1($password . $salt);
		return array($salt, $password);
	}
}

if (!function_exists('__MakeToken')) {
	/**
	 * 生成token
	 * @param  [string] $string [需要产生token的字符串]
	 * @return [string]         [token]
	 */
	function __MakeToken($string) {
		return sha1(md5(uniqid($string)));
	}
}

if (!function_exists('__Log')) {
	/**
	 * 记录日志方法封装
	 * @param  string $commit    [日志的注释]
	 * @param  string $log_data  [日志数据]
	 * @param  [string] $log_path [日志的路径]
	 * @return [type]           [description]
	 */
	function __Log($commit = '', $log_data = '', $log_path = APPPATH . 'logs/') {
		error_log('[@###' . date('Y-m-d H:i:s') . '###@]-----' . $commit . '---' . $log_data . "\r\n", 3, $log_path . date('Ym') . ".log");
	}
}

if (!function_exists('__HttpRequest')) {
	/**
	 * http请求（支持GET和POST）
	 * @param  [string] $url  [请求的url]
	 * @param  [array] $data [如果是POST方法，传入请求数组]
	 * @param  [array] $cert [如果是http请求参数留空，https传入数组如下
	 *                        array(
	 *                        	sslcert_path => '路径地址',
	 *                        	sslkey_path => '路径地址',
	 *                        	rootca_path => '路径地址',
	 *                        )
	 *                       ]
	 * @return [type]       [请求的响应结果]
	 */
	function __HttpRequest($url, $data = null, $cert = array()) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		// https
		if ($cert) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格校验
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
			curl_setopt($curl, CURLOPT_SSLCERT, env('WECHAT_SSLCERT_PATH'));
			curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
			curl_setopt($curl, CURLOPT_SSLKEY, env('WECHAT_SSLKEY_PATH'));
			curl_setopt($curl, CURLOPT_CAINFO, env('WECHAT_ROOTCA_PATH'));
		}
		// POST
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		$output = curl_exec($curl);
		$error = curl_error($curl);
		curl_close($curl);
		if (!$error) {
			return $output;
		} else {
			return $error;
		}
	}
}

if (!function_exists('__SocketRequest')) {
	/**
	 * Socket请求方法封装
	 * @param  [string]  $host [请求的主机]
	 * @param  [int]  $port [请求主机的端口]
	 * @param  [type]  $data [请求写入的数据]
	 * @param  integer $size [缓冲区大小]
	 * @return [type]        [请求结果]
	 */
	function __SocketRequest($host, $port, $data, $size = 8192) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $host, $port);
		$in = json_encode($data);
		socket_write($socket, $in, strlen($in));
		$out = '';
		while ($tmp = socket_read($socket, $size)) {
			if (strlen($tmp) == 0) {
				break;
			} else {
				$out .= $tmp;
			}
		}
		socket_close($socket);
		return $out;
	}
}

if (!function_exists('__Response')) {
	/**
	 * 返回方法(支持ajax)
	 * 用来返回的所需参数
	 * @param  [type] $code [返回值的状态码]
	 * @param  [type] $msg  [返回消息]
	 * @param  [type] $data [返回数据]
	 * @return [type]       [json数据]
	 */
	function __Response($code, $msg, $data = array()) {
		$return_data = array(
			'code' => $code,
			'msg' => $msg,
			'data' => $data,
		);
		exit(json_encode($return_data, JSON_UNESCAPED_UNICODE));
	}
}

?>