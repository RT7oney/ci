<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
/**
 * 文件系统控制器
 * @author Ryan <ryantyler423@gmail.com>
 * @group(name="business", description="文件控制器")
 */
class file extends Soco_Controller {

	function __construct() {
		parent::__construct();
		$this->__RegCtr(__CLASS__, 0); // 注册该控制器是否需要用户登录
	}

	public function upimg() {
		// 上传文件测试
		if ($this->input->post('file')) {
			$name = __SaveImg($this->input->post('file'), uniqid(time()));
			if ($name) {
				$this->__Response(200, '图片保存成功', $name);
			} else {
				$this->__Response(202, '保存失败');
			}
		} else {
			$this->__Response(201, '参数不完整');
		}
	}

	public function arr2xls() {
		#######test#######
		$arr = array(
			array('phone' => 123123123),
			array('phone' => 1231235453),
			array('phone' => 123123234233),
			array('phone' => 142624624623),
			array('phone' => 734563453123),
		);
		$file_data = array();
		foreach ($arr as $k => $val) {
			$file_data[$k] = array();
			array_push($file_data[$k], (string) $val['phone']);
		}
		$file_head = array("手机号", "积分");
		#######test#######
		$this->load->library('ExcelTool');
		$name = $this->exceltool->writeExcel($file_head, $file_data, 'test');
		if ($name) {
			$this->__Response(300, '数组转化成excel成功', $name);
		} else {
			$this->__Response(301, '失败');
		}
	}

	public function xls2arr() {
		$tmp = $_FILES['file']['tmp_name'];
		$this->load->library('ExcelTool');
		$data = $this->exceltool->readExcel($tmp);
		if ($data) {
			$this->__Response(400, 'excel转化成数组成功', $data);
		} else {
			$this->__Response(401, '失败');
		}
	}

	public function down() {
		$filename = APPPATH . 'data/' . $this->input->get('type') . '/' . $this->input->get('name');
		if ($this->input->get('type') == 'image') {
			header('Content-type: image/png');
			header("Content-Disposition: attachment; filename=$filename");
			@readfile($filename);
		} elseif ($this->input->get('type') == 'excel') {
			$file = fopen($filename, "r");
			Header("Content-type:application/octet-stream");
			Header("Accept-Ranges:bytes");
			header("Content-Type:application/msexcel");
			Header("Accept-Length:" . filesize($filename));
			Header("Content-Disposition:attachment;filename=$filename");
			echo fread($file, filesize($filename));
			fclose($file);
		}
	}

}
?>