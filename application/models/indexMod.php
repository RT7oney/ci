<?php
require_once 'SocoMod.php';
/**
 * 模型调用参考
 * @author Ryan <ryantyler423@gmail.com>
 */
class indexMod extends SocoMod {

	function __construct() {
		parent::__construct();
		$this->__RegMod(__CLASS__, 'test'); // 注册该模型的表
	}
	public function index() {
		$query = array(
			'select' => '*',
		);
		return $this->__GetData($query);
	}
}
?>