<?php
$config['soco'] = array(
	'server_path' => '/soco/back-end',
	'use_table_prefix' => true,
	'token' => 'supersoco',
	'https_request' => array(
		'sslcert_path' => '路径地址',
		'sslkey_path' => '路径地址',
		'rootca_path' => '路径地址',
	),
	'doc_admin' => array(
		'name' => 'admin',
		'password' => 'admin@soco',
	),
	'wechat_pay' => array(
		'mch_id' => '1385373702',
		'notify' => '',
		'app_id' => 'wx898f13df6bf0ea41',
		'app_secret' => '8318b4d4511c90f8ba963e0b5a8b74d3',
		'sslcert_path' => '/www/code/soco/back-end/application/libraries/cert/apiclient_cert.pem',
		'sslkey_path' => '/www/code/soco/back-end/application/libraries/cert/apiclient_key.pem',
		'pay_key' => '1qazxsw23edcvfr45tgbnhy67ujmki89',
	),
);
?>