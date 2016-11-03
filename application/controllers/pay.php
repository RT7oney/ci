<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * 控制器调用参考
 * @author Ryan <ryantyler423@gmail.com>
 * @group(name="business", description="支付组件")
 */
class pay extends Soco_Controller {

	function __construct() {
		parent::__construct();
		$this->__RegCtr(__CLASS__, 0);
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
				$this->__Response(102, '没有查询到商品信息');
			}
		} else {
			$this->__Response(101, '参数不完整');
		}
	}

	//0.支付宝网页端
	//1.支付宝手机端
	//2.微信扫码natvie
	//3.微信jsapipay
	public function pay() {
		if ($this->input->get('pay_type') && $this->input->get('token')) {
			switch ($this->input->get('pay_type')) {
			case 0:
				break;
			case 1:
				break;
			//TODO 支付宝
			case 2:
				// 微信支付扫码支付
				$this->load->model('orderMod');
				$data = $this->orderMod->getOrder($this->input->get('token'));
				$this->load->library('WechatPay');
				$url = $this->wechatpay->nativePay($data[0]['order_id'], $data[0]['total_price']);
				$test = 'http://paysdk.weixin.qq.com/example/qrcode.php?data=' . $url; //test
				$this->__Response(200, '操作成功', $test);
				break;
			case 3:
				// 微信支付jsapi支付
				$this->load->model('orderMod');
				$data = $this->orderMod->getOrder($this->input->get('token'));
				$this->load->library('WechatPay');
				$res = $this->wechatpay->jsPay($data[0]['order_id'], $data[0]['total_price']);
				print_r($res);die;
				break;
			default:
				# code...
				break;
			}

		} else {
			$this->__Response(201, '参数不完整');
		}
	}

	public function wxnotify() {
		if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
			$XML = $GLOBALS['HTTP_RAW_POST_DATA'];
		} else {
			$XML = file_get_contents('php://input');
		}
		$this->____Response(300, '进入回调', $XML);
	}

	public function alnotify() {

	}
}
?>