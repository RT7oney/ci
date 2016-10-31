<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require_once 'SocoCtr.php';
/**
 * 控制器调用参考
 * @author Ryan <ryantyler423@gmail.com>
 * @group(name="business", description="支付组件（100）")
 */
class pay extends SocoCtr {

	function __construct() {
		parent::__construct();
		$this->__RegCtr(__CLASS__);
	}

	/**
	 * @ApiDescription(section="index", method="post", description="支付生成订单")
	 * @ApiRoute(name="/pay/index")
	 * @ApiSuccess(value="{'code' : '200100', 'msg'  : '生成订单成功', 'data' : '数据'}")
	 * @ApiExample(value="{'good' : '1', 'amount'  : '2', 'extra' : 'array('color => 'red')'}")
	 * @ApiParams(name="good_id", type="integer", is_selected=true, description="商品对应的id")
	 * @ApiParams(name="amount", type="integer", is_selected=true, description="选购商品的数量")
	 * @ApiParams(name="extra", type="array", description="商品购买时的附加条件（如颜色，型号等）")
	 * @ApiReturn(name="code", type="integer", description="接口返回码")
	 * @ApiReturn(name="msg", type="string", description="接口返回信息")
	 * @ApiReturn(name="data", type="array", description="接口返回数据")
	 */
	public function index() {
		$this->load->model('goodsMod');
		if ($this->input->post('good_id') && $this->input->post('amount') > 0) {
			$price = $this->goodsMod->getGoodPrice($this->input->post('good_id'));
			if ($price) {
				$data = array(
					'total_price' => $this->input->post('amount') * $price,
					'good_id' => $this->input->post('good_id'),
				);
				if ($this->input->post('extra') && is_array($this->input->post('extra'))) {
					$data['order_info'] = json_encode($this->input->post('extra'), JSON_UNESCAPED_UNICODE);
				}
				$this->load->model('orderMod');
				$token = $this->orderMod->makeOrder($data);
				if ($token) {
					$this->__Response(100, '生成订单成功', $token);
				} else {
					$this->__Response(103, '内部错误');
				}
			} else {
				$this->__Response(102, '没有查询到用户信息');
			}
		} else {
			$this->__Response(101, '参数不完整');
		}
	}

	public function pay() {
		if ($this->input->post('pay_type') && $this->input->post('token')) {
			if ($this->input->post('pay_type') == 2) {
				// 微信支付扫码支付
				$this->load->model('orderMod');
				$data = $this->orderMod->getOrder($this->input->post('token'));
				$this->load->library('WechatPay');
				$url = $this->wechatpay->nativePay($data[0]['order_id'], $data[0]['total_price']);
				echo 'http://paysdk.weixin.qq.com/example/qrcode.php?data=' . $url; //test
			}
		} else {
			$this->__Response(201, '参数不完整');
		}
	}
}
?>