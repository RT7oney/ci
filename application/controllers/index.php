<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * 控制器调用参考
 * @author Ryan <ryantyler423@gmail.com>
 * @group(name="business", description="业务逻辑控制器")
 */
class index extends Soco_Controller {

	function __construct() {
		parent::__construct();
		$this->__RegCtr(__CLASS__, 0); // 注册该控制器是否需要用户登录
	}

	/**
	 * @ApiDescription(section="index", method="get", description="控制器调用参考")
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

	/**
	 * @ApiDescription(section="login", method="post", description="用户登录")
	 * @ApiRoute(name="/index/login")
	 * @ApiExample(value="{'phone' : '188xxxxxxxx', 'password'  : '123qweasd'}")
	 * @ApiSuccess(value="{'code' : '100200', 'msg'  : '登录成功', 'data' : '用户的数据'}")
	 * @ApiParams(name="account", type="string", is_selected=true, description="用户的手机号或者邮箱")
	 * @ApiParams(name="password", type="string", is_selected=true, description="用户密码")
	 * @ApiReturn(name="", type="string", description="返回json数据")
	 */
	public function login() {
		$this->load->model('userMod');
		if ($this->input->post('account') && $this->input->post('password')) {
			if (__IsPhone($this->input->post('account')) || __IsEmail($this->input->post('account'))) {
				$data = $this->userMod->getUser($this->input->post('account'));
				if (isset($data[0])) {
					if ($data[0]['password'] === sha1($this->input->post('password') . $data[0]['salt'])) {
						unset($data[0]['password']);
						unset($data[0]['salt']);
						$data[0]['phone'] = $data[0]['phone'] ? substr_replace($data[0]['phone'], '****', 3, 4) : NULL;
						$data[0]['email'] = $data[0]['email'] ? substr_replace($data[0]['email'], '****@****', 3, strlen($data[0]['email'])) : NULL;
						$this->__Response(200, '登录成功', $data[0]);
					} else {
						$this->__Response(204, '密码错误');
					}
				} else {
					$this->__Response(203, '用户信息不存在');
				}
			} else {
				$this->__Response(202, '账户必须为手机号或者邮箱');
			}
		} else {
			$this->__Response(201, '必填信息不能为空');
		}
	}

	/**
	 * @ApiDescription(section="signin", method="post", description="用户注册")
	 * @ApiRoute(name="/index/signin")
	 * @ApiExample(value="{'phone' : '188xxxxxxxx', 'password'  : '123qweasd'}")
	 * @ApiSuccess(value="{'code' : '100300', 'msg'  : '注册成功', 'data' : '这里是数据'}")
	 * @ApiParams(name="phone", type="string", is_selected=true, description="用户注册的手机号")
	 * @ApiParams(name="email", type="string", is_selected=true, description="用户注册的邮箱（手机号邮箱二选一可多选）")
	 * @ApiParams(name="password", type="string", is_selected=true, description="用户密码")
	 * @ApiParams(name="nick_name", type="string", is_selected=false, description="用户昵称")
	 * @ApiReturn(name="", type="string", description="返回json数据")
	 */
	public function signin() {
		$this->load->model('userMod');
		if ($this->input->post('phone') || $this->input->post('email')) {
			if ($this->input->post('password')) {
				if ($this->input->post('phone')) {
					if (__IsPhone($this->input->post('phone'))) {
						$indexes['手机'] = $this->input->post('phone');
					} else {
						$this->__Response(306, '请填写正确的手机号');
					}
				}
				if ($this->input->post('email')) {
					if (__IsEmail($this->input->post('email'))) {
						$indexes['邮箱'] = $this->input->post('email');
					} else {
						$this->__Response(305, '请填写正确的邮箱');
					}
				}
				foreach ($indexes as $key => $index) {
					if ($this->userMod->getUser($index)) {
						$this->__Response(304, '该' . $key . '已经注册过了');
					}
				}
				if ($this->userMod->insertUser($this->input->post())) {
					$this->__Response(300, '注册成功');
				} else {
					$this->__Response(303, '注册成功');
				}
			} else {
				$this->__Response(302, '密码不能为空');
			}
		} else {
			$this->__Response(301, '手机和邮箱必须填写一个');
		}
	}

}
?>