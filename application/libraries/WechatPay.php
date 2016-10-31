<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 微信Pay封装
 *
 * @author  Ryan <ryantyler423@gmail.com>
 */
class WechatPay {

	protected $CI;

	function __construct() {
		$this->CI = &get_instance();
		$this->CI->config->load('soco');
		$this->CI->load->helper('soco');
		$GLOBALS['wechat_pay'] = ($this->CI->config->item('soco'))['wechat_pay'];
	}

	/**
	 * 微信扫码支付
	 * 流程：
	 * 1、调用统一下单，取得code_url，生成二维码
	 * 2、用户扫描二维码，进行支付
	 * 3、支付完成之后，微信服务器会通知支付成功
	 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
	 */
	public function nativePay($order_id, $total_price) {
		require_once 'wechat_pay/WxPay.Api.php';
		require_once "wechat_pay/WxPay.NativePay.php";
		$notify = new NativePay();
		// $url1 = $notify->GetPrePayUrl("123456789");//预支付链接
		$input = new WxPayUnifiedOrder();
		$input->SetBody("速珂智能订单");
		// $input->SetAttach("test");
		// $input->SetOut_trade_no(($this->CI->config->item('soco'))['wechat_pay']['mch_id'] . date("YmdHis"));
		$input->SetOut_trade_no($order_id . '-' . time());
		$input->SetTotal_fee($total_price * 100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 60000));
		// $input->SetGoods_tag("test");
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		// $input->SetNotify_url(($this->CI->config->item('soco'))['wechat_pay']['notify']);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id('速珂智能产品');
		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];
		return $url2;
	}

	/**
	 * 微信jsapi支付
	 * 注意：
	 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
	 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
	 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
	 */
	public function jsPay($order_id, $total_price) {
		require_once 'wechat_pay/WxPay.Api.php';
		require_once 'wechat_pay/WxPay.JsApiPay.php';
		//①、获取用户openid
		$tools = new JsApiPay();
		$openId = $tools->GetOpenid();
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("速珂智能");
		// $input->SetAttach("");
		$input->SetOut_trade_no(($this->CI->config->item('soco'))['wechat_pay']['mch_id'] . date("YmdHis"));
		$input->SetTotal_fee($total_price * 100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 60000));
		// $input->SetGoods_tag("");
		$input->SetNotify_url(($this->CI->config->item('soco'))['wechat_pay']['notify']);
		// $input->SetNotify_url("http://192.168.1.113/php/my-web-pay/wxpay-of/example/notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		//获取共享收货地址js函数参数
		// $editAddress = $tools->GetEditAddressParameters();
		return $res = array(
			'jsApiParameters' => $jsApiParameters,
			// 'editAddress' => $editAddress,
		);
	}
}
?>