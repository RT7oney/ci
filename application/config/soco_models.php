<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * 所有模型需要经过统一的注册
 * 使用小写+Mod注册名称
 *
 * @author  Ryan <ryantyler423@gmail.com>
 */
$config['soco_models'] = array(
	'ignore' => array(
		'.',
		'..',
		'.DS_Store',
		'index.html',
		'SocoMod.php',
	),
	'register' => array(
		'rt' => array(
			'test' => array(
				'indexMod',
				'goodsMod',
				'orderMod',
			), //按照带前缀的表名进行注册
		),
		'b' => array(
			'uc' => array(
				'userMod',
			),
		),
	),
);
?>