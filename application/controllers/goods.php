<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require_once 'SocoCtr.php';
/**
 * 控制器调用参考
 * @author Ryan <ryantyler423@gmail.com>
 * @group(name="business", description="商品仓库组件")
 */
class goods extends SocoCtr {

	function __construct() {
		parent::__construct();
		$this->__RegCtr(__CLASS__);
	}

	/**
	 * @ApiDescription(section="insert", method="post", description="增添新的商品")
	 * @ApiRoute(name="/goods/insert")
	 * @ApiSuccess(value="{'code' : '300100', 'msg'  : '插入新商品成功', 'data' : '数据'}")
	 * @ApiExample(value="{'amount' : '100', 'price'  : '5', 'name' : '测试商品' , 'extra' : 'array('color => array('description' => '', 'data' => '具体数据'))'}")
	 * @ApiParams(name="amount", type="integer", is_selected=true, description="商品总的数量")
	 * @ApiParams(name="price", type="integer", is_selected=true, description="选购商品的数量")
	 * @ApiParams(name="name", type="string", is_selected=true, description="商品名称")
	 * @ApiParams(name="extra", type="array", description="商品购买时的附加条件（如颜色，型号等）")
	 * @ApiReturn(name="code", type="integer", description="接口返回码")
	 * @ApiReturn(name="msg", type="string", description="接口返回信息")
	 * @ApiReturn(name="data", type="array", description="接口返回数据")
	 */
	public function insert() {
		$this->load->model('goodsMod');
		if ($this->input->post('amount') && $this->input->post('price') && $this->input->post('name')) {
			$data = array(
				'amount' => $this->input->post('amount'),
				'price' => $this->input->post('price'),
				'name' => $this->input->post('name'),
			);
			if ($this->input->post('extra')) {
				if (is_array($this->input->post('extra')) && isset(($this->input->post('extra'))['description']) && isset(($this->input->post('extra'))['data'])) {
					$data['extra'] = $this->input->post('extra');
				} else {
					$this->__Response(103, 'extra参数不符合规范');
				}
			}
			if ($this->goodsMod->insertGood($data)) {
				$this->__Response(100, '添加成功');
			} else {
				$this->__Response(102, '插入数据错误');
			}
		} else {
			$this->__Response(101, '参数不完整');
		}
	}

	/**
	 * @ApiDescription(section="all", method="get", description="获取所有的商品")
	 * @ApiRoute(name="/goods/all")
	 * @ApiSuccess(value="{'code' : '300200', 'msg'  : '查询成功', 'data' : '数据'}")
	 * @ApiReturn(name="code", type="integer", description="接口返回码")
	 * @ApiReturn(name="msg", type="string", description="接口返回信息")
	 * @ApiReturn(name="data", type="array", description="接口返回数据")
	 */
	public function all() {
		$this->load->model('goodsMod');
		$data = $this->goodsMod->getAllGood();
		if ($data) {
			$this->__Response(200, '查询成功', $data);
		} else {
			$this->__Response(201, '查询失败');
		}
	}
}
?>