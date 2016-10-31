<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require_once 'SocoCtr.php';
/**
 * 控制器调用参考
 * @author Ryan <ryantyler423@gmail.com>
 * @group(name="business", description="业务逻辑控制器")
 */
class index extends SocoCtr {

	function __construct() {
		parent::__construct();
		$this->__RegCtr(__CLASS__);
	}

	/**
	 * @ApiDescription(section="index(100)", method="get", description="控制器调用参考")
	 * @ApiRoute(name="/index/index")
	 * @ApiSuccess(value="{'code' : '1000', 'msg'  : '请求成功', 'data' : '这里是数据'}")
	 * @ApiParams(name="timestamp", type="string", is_selected=true, description="调用接口的时间戳")
	 * @ApiParams(name="sign", type="string", is_selected=true, description="调用接口的签名")
	 * @ApiReturn(name="", type="string", description="返回json数据")
	 */
	public function index() {
		echo 'this is index controller <hr>';
		$this->load->model('indexMod');
		$data = $this->indexMod->index();
		if ($data) {
			$this->__Response(100, '请求成功', $data);
		} else {
			$this->__Response(101, '请求失败');
		}
	}
}
?>