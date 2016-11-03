<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * mongodb工具类封装
 * 由于php7和php5对mongodb的支持不一样，所以请开发者自主选择
 * 请把mongodb的collection理解成sql中的table
 *
 * @author  Ryan <ryantyler423@gmail.com>
 */
class MongoTool7 {

	protected $CI;
	public $mongo;
	public $db;
	public $collection;

	function __construct($host, $port, $db, $collection) {
		$this->CI = &get_instance();
		$this->CI->config->load('soco');
		$this->CI->load->helper('soco');
		$this->mongo = new MongoDB\Driver\Manager("mongodb://$host:$port");
		$this->db = $db;
		$this->collection = $collection;
	}

	/**
	 * 插入一条数据
	 * @param  [string] $key   [插入数据的key值]
	 * @param  [string] $value [数据值]
	 * @return [type]        [description]
	 */
	public function insertData($key, $value) {
		$bulk = new MongoDB\Driver\BulkWrite;
		$document = ['_id' => new MongoDB\BSON\ObjectID, $key => $value];
		$_id = $bulk->insert($document);
		// var_dump($_id);
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		return $this->mongo->executeBulkWrite($this->db . '.' . $this->collection, $bulk, $writeConcern);
	}

	/**
	 * 获取数据
	 * @param  [array] $filter  [查询过滤器]
	 * @param  [array] $options [查询的附属选项]
	 * @return [array]          [查询结果]
	 */
	public function getData($filter, $options) {
		$query = new MongoDB\Driver\Query($filter, $options);
		return $this->mongo->executeQuery($this->db . '.' . $this->collection, $query);
	}

	/**
	 * 更新数据
	 * @param  [array] $where [更新条件]
	 * @param  [array] $data  [需要更新的数据]
	 * @param  [array] $multi [查询附属条件]
	 * @return [type]        [description]
	 */
	public function updateData($where, $data, $multi) {
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->update($where, $data, $multi);
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		$result = $this->mongo->executeBulkWrite($this->db . '.' . $this->collection, $bulk, $writeConcern);
	}

	/**
	 * 删除数据
	 * @param  [array] $where [条件]
	 * @return [type]        [description]
	 */
	public function deleteData($where) {
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->delete($where);
		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		$result = $this->mongo->executeBulkWrite($this->db . '.' . $this->collection, $bulk, $writeConcern);
	}

}

class MongoTool5 {

	protected $CI;
	public $mongo;
	private $db;
	private $table = NULL;

	/**
	 * 初始化类，得到mongo的实例对象
	 */
	public function __construct($host, $port, $dbname, $table) {

		$this->CI = &get_instance();
		$this->CI->config->load('soco');
		$this->CI->load->helper('soco');

		if (NULL === $dbname) {
			$this->throwError('集合不能为空！');
		}

		$this->host = $host;
		$this->port = $port;
		$this->table = $table;
		$this->mongo = new MongoClient($this->host . ':' . $this->port);
		if ($this->getVersion() >= '0.9.0') {
			$db = $this->mongo->selectDB($dbname);
			$this->db = $db->selectCollection($table);
		} else {
			$this->db = $this->mongo->$dbname->$table;
		}
	}

	/**
	 * 插入一条数据
	 * @param array $doc
	 */
	public function insert($doc = array()) {
		if (empty($doc)) {
			$this->throwError('插入的数据不能为空！');
		}
		//保存数据信息
		try {
			if (!$this->db->insert($doc)) {
				throw new MongoException('插入数据失败');
			}
		} catch (MongoException $e) {
			$this->throwError($e->getMessage());
		}
	}

	/**
	 * 插入多条数据信息
	 * @param array $doc
	 */
	public function insertMulti($doc = array()) {
		if (empty($doc)) {
			$this->throwError('插入的数据不能为空！');
		}
		//插入数据信息
		foreach ($doc as $key => $val) {
			//判断$val是不是数组
			if (is_array($val)) {
				$this->insert($val);
			}
		}
	}

	/**
	 * 查找一条记录
	 * @return array|null
	 */
	public function findOne($where = NULL) {
		if (NULL === $where) {
			try {
				if ($result = $this->db->findOne()) {
					return $result;
				} else {
					throw new MongoException('查找数据失败');
				}
			} catch (MongoException $e) {
				$this->throwError($e->getMessage());
			}
		} else {
			try {
				if ($result = $this->db->findOne($where)) {
					return $result;
				} else {
					throw new MongoException('查找数据失败');
				}
			} catch (MongoException $e) {
				$this->throwError($e->getMessage());
			}
		}

	}

	/**
	 * 查找所有的文档
	 * @return MongoCursor
	 */
	public function find($where = NULL) {
		if (NULL === $where) {

			try {
				if ($result = $this->db->find()) {

				} else {
					throw new MongoException('查找数据失败');
				}
			} catch (MongoException $e) {
				$this->throwError($e->getMessage());
			}
		} else {
			try {
				if ($result = $this->db->find($where)) {

				} else {
					throw new MongoException('查找数据失败');
				}
			} catch (MongoException $e) {
				$this->throwError($e->getMessage());
			}
		}

		$arr = array();
		foreach ($result as $id => $val) {
			$arr[] = $val;
		}

		return $arr;
	}

	/**
	 * 获取记录条数
	 * @return int
	 */
	public function getCount() {
		try {
			if ($count = $this->db->count()) {
				return $count;
			} else {
				throw new MongoException('查找总数失败');
			}
		} catch (MongoException $e) {
			$this->throwError($e->getMessage());
		}
	}

	/**
	 * 获取所有的数据库
	 * @return array
	 */
	public function getDbs() {
		return $this->mongo->listDBs();
	}

	/**
	 * 删除数据库
	 * @param null $dbname
	 * @return mixed
	 */
	public function dropDb($dbname = NULL) {
		if (NULL !== $dbname) {
			$retult = $this->mongo->dropDB($dbname);
			if ($retult['ok']) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		$this->throwError('请输入要删除的数据库名称');
	}

	/**
	 * 强制关闭数据库的链接
	 */
	public function closeDb() {
		$this->mongo->close(TRUE);
	}

	/**
	 * 输出错误信息
	 * @param $errorInfo 错误内容
	 */
	public function throwError($errorInfo = '') {
		die("出错了：" . $errorInfo);
	}
}
?>