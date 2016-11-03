<?php
/**
 * 商品表，将表的颜色等使用json数组存入extra字段
 * @author Ryan <ryantyler423@gmail.com>
 */
class goodsMod extends Soco_Model {
	function __construct() {
		parent::__construct();
		$this->__RegMod(__CLASS__, 'goods'); // 注册该模型的表
	}

	/**
	 * 增加新的商品
	 * @param  [array] $data [商品数据]
	 * @return [boolen]       [插入是否成功]
	 */
	public function insertGood($data) {
		if (isset($data['extra'])) {
			$data['extra'] = json_encode($data['extra'], JSON_UNESCAPED_UNICODE);
		}
		return $this->__InsertData($data);
	}

	/**
	 * 获取所有商品信息
	 * @return [array] [所有商品数组]
	 */
	public function getAllGood() {
		$query = array(
			'select' => '*',
		);
		$data = $this->__GetData($query);
		foreach ($data as &$value) {
			if ($value['extra']) {
				$value['extra'] = json_decode($value['extra'], 1);
			}
		}
		return $data;
	}

	/**
	 * 根据商品的id获取商品的价格
	 * @param  [integer] $id [商品的id]
	 * @return [integer]     [商品价格]
	 */
	public function getGoodPrice($id) {
		$query = array(
			'select' => 'price',
			'where' => array(
				'id' => $id,
			),
		);
		$data = $this->__GetData($query);
		if ($data && isset($data[0])) {
			return $data[0]['price'];
		}
		return false;
	}
}
?>