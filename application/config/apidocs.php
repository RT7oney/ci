<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * @param level [所在层级(在项目所在目录开始，*代表该目录所有文件，/代表该目录的层级)]
 * @param allowed_file [允许的文件后缀 如:.php,.js]
 * @param build_path [生成文档的目录]
 * @param template_ext[模板后缀名]
 */
$config['settings'] = array(
	'title' => 'supersoco',
	'logo' => 'http://bbs.supersoco.com/uc_server/data/avatar/000/00/01/66_avatar_middle.jpg',
	'allowed_file' => '.php',
	'output_format' => 'json',
	'build_path' => 'controllers',
	'level' => '*/',
	'template_ext' => '.md',
	'rule' => array(
		'description' => 'ApiDescription',
		'params' => 'ApiParams',
		'return' => 'ApiReturn',
		'format' => 'ApiFormat',
		'access' => 'ApiAccess',
		'notice' => 'ApiNotice',
		'example' => 'ApiExample',
		'success' => 'ApiSuccess',
	),
	'template' => 'default',
	'footer' => '',
);
?>
