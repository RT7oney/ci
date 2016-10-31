<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Doc extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->config->load('soco');
		$this->load->library('phpdoc/apidoc');
	}

	function build() {
		if ($this->input->post('name') && $this->input->post('password')) {
			if ($this->input->post('name') == ($this->config->item('soco'))['doc_admin']['name'] && $this->input->post('password') == ($this->config->item('soco'))['doc_admin']['password']) {
				$this->apidoc->build_doc();
				echo '<font style="font-size:30px;">文档生成成功</font>';
			} else {
				echo '用户名或者密码错误';
			}
			echo '<br>三秒后自动跳转......';
			$url = base_url('doc/build');
			header("refresh:3;url=$url");
			exit();
		} else {
			$this->load->view('doc.php');
		}
	}

}