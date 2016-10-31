<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * 所有模型需要经过统一的注册
 * 必须使用小写字母命名.php文件，注册名也必须为小写
 *
 * @author  Ryan <ryantyler423@gmail.com>
 */
$config['soco_controllers'] = array(
	'ignore' => array(
		'.',
		'..',
		'.DS_Store',
		'index.html',
		'Doc.php',
		'SocoCtr.php',
	),
	'register' => array(
		'index',
		'pay',
		'goods',
	),
);
?>