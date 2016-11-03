<?php
/**
 * 速珂智能科技(上海)有限公司基础模型
 *
 * 实现模型的统一注册管理
 *
 * @author Ryan <ryantyler423@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Soco_Model extends CI_Model {

	protected $table;

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->config->load('soco');
		$this->config->load('soco_models');
		$this->load->helper('soco');
	}

	/**
	 * 模型注册
	 * @param  [string] $mod [模型名称]
	 * @param  [string] $table [模型所对应的表]
	 * @return [type]        [description]
	 */
	protected function __RegMod($mod, $table) {
		$table_prefix = '';
		if (($this->config->item('soco'))['use_table_prefix']) {
			$data = __SearchArray(($this->config->item('soco_models'))['register'], $mod);
			$table_prefix = key($data) . '_' . key($data[key($data)]) . '_';
		}
		$this->table = $table_prefix . $table;
	}

	/**
	 * 查询数据方法封装
	 * 请以http://codeigniter.org.cn/user_guide/database/query_builder.html
	 * 文档中所提供的方法传递，参数的具体形式也参阅文档
	 * @param  [array] $query [传入数组查询，目前支持
	 *                        select,where,like,group_by,having,order_by
	 *                        等方法]
	 * @param  [array] $limit [传入数组进行分页与偏移]
	 * @param  [array] $join  [传入数组进行连接]
	 * @return [array]        [以数组的形式返回查询的结果]
	 */
	public function __GetData($query, $limit = array(), $join = array()) {
		foreach ($query as $k => $val) {
			$this->db->$k($val);
		}
		if (!empty($limit)) {
			if (!isset($limit[1])) {
				$limit[1] = '';
			}
			$this->db->limit($limit[0], $limit[1]);
		}
		if (!empty($join)) {
			if (!isset($join[2])) {
				$join[2] = '';
			}
			$this->db->join($join[0], $join[1], $join[2]);
		}
		return $this->db->get($this->table)->result_array();
	}

	/**
	 * 增加数据方法封装
	 * @param  [array] $query [需要插入的数据，形式以 key/value 传递]
	 * @return [boolen]        [插入结果]
	 */
	public function __InsertData($data) {
		return $this->db->insert($this->table, $data);
	}

	/**
	 * 更新数据方法封装
	 * @param  [array] $data  [需要更新的数据]
	 * @param  [array] $where [更新条件]
	 * @return [boolen]        [更新结果]
	 */
	public function __UpdateData($data, $where) {
		return $this->db->set($data)->where($where)->update($this->table);
	}

	/**
	 * 删除数据方法封装
	 * @param  [array] $where [需要删除数据的查询条件]
	 * @return [type]        [description]
	 */
	public function __DeleteData($where) {
		return $this->db->where($where)->delete($this->table);
	}
}
?>