<?php
/**
 * {基础}_{用户中心}_user表，b_uc_user
 * @author Ryan <ryantyler423@gmail.com>
 */
class userMod extends Soco_Model {
	function __construct() {
		parent::__construct();
		$this->__RegMod(__CLASS__, 'user'); // 注册该模型的表
	}

	/**
	 * 增加新的用户
	 * @param  [array] $data [用户数据]
	 * @return [boolen]       [插入是否成功]
	 */
	public function insertUser($data) {
		$salt_password = __SaltPassword($data['password']);
		$data['salt'] = $salt_password[0];
		$data['password'] = $salt_password[1];
		$data['uuid'] = isset($data['phone']) ? $this->makeUuid($data['phone']) : $this->makeUuid($data['email']);
		return $this->__InsertData($data);
	}

	/**
	 * 通过索引获取用户信息
	 * @param  [string] $index [索引的值]
	 * @return [type]        [description]
	 */
	public function getUser($index) {
		if (__IsEmail($index)) {
			$query = array(
				'select' => '*',
				'where' => array(
					'email' => $index,
				),
			);
		} elseif (__IsPhone($index)) {
			$query = array(
				'select' => '*',
				'where' => array(
					'phone' => $index,
				),
			);
		} else {
			$query = array(
				'select' => '*',
				'where' => array(
					'uuid' => $index,
				),
			);
		}
		$data = $this->__GetData($query);
		return $data;
	}

	/**
	 * 生成用户唯一表示索引
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	private function makeUuid($str) {
		return sha1(md5(uniqid($str . time())));
	}
}
?>