<?php
/**
 * 订单表，用于完成支付
 * @author Ryan <ryantyler423@gmail.com>
 */
class orderMod extends Soco_Model {
	function __construct() {
		parent::__construct();
		$this->__RegMod(__CLASS__, 'order'); // 注册该模型的表
	}

	/**
	 * 生成订单
	 * @param  [array] $data [订单数据]
	 * @return [string]       [token]
	 */
	public function makeOrder($data) {
		$query['order_id'] = $data['good_id'] . '00' . __MicrotimeFormat('YmdHisx', __Microtime());
		$query['token'] = __MakeToken($query['order_id']);
		$query['total_price'] = $data['total_price'];
		$query['good_id'] = $data['good_id'];
		$query['order_info'] = isset($data['order_info']) ? $data['order_info'] : '';
		$check = $this->__InsertData($query);
		if ($check) {
			return $query['token'];
		}
		return false;
	}

	/**
	 * 根据token获取订单详情
	 * @param  [string] $token [订单的唯一索引]
	 * @return [array]        [订单数据]
	 */
	public function getOrder($token) {
		$query['select'] = '*';
		$query['where'] = array('token' => $token);
		return $this->__GetData($query);
	}
}
?>