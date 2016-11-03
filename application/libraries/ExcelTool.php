<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * excel工具类封装
 *
 * @author  Ryan <ryantyler423@gmail.com>
 */
class ExcelTool {

	protected $CI;

	function __construct() {
		$this->CI = &get_instance();
		$this->CI->config->load('soco');
		$this->CI->load->helper('soco');
	}

	/**
	 * 把数组写入excel.xls
	 * @param  [array] $head [excel的标题行]
	 * @param  [array] $data [表格数据]
	 * @param  [string] $name [excel文件的名称]
	 * @return [string]       [保存成功的文件名]
	 */
	public function writeExcel($head, $data, $name) {
		require_once "excel/Excel.php";
		$date = date("YmdHis", time());
		$path = APPPATH . 'data/excel/';
		$name .= "_{$date}.xls";
		$filename = $path . $name;

		//创建新的PHPExcel对象
		$objPHPExcel = new PHPExcel();
		$objProps = $objPHPExcel->getProperties();

		//设置表头
		$key = ord("A");
		foreach ($head as $v) {
			$colum = chr($key);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
			$key += 1;
		}

		$column = 2;
		$objActSheet = $objPHPExcel->getActiveSheet();
		foreach ($data as $key => $rows) {
			//行写入
			$span = ord("A");
			foreach ($rows as $keyName => $value) {
				// 列写入
				$j = chr($span);
				$objActSheet->setCellValueExplicit($j . $column, $value, PHPExcel_Cell_DataType::TYPE_STRING);
				$objActSheet->getStyle($j . $column)->getNumberFormat()->setFormatCode("@");
				$span++;
			}
			$column++;
		}

		$filename = iconv("utf-8", "gb2312", $filename);
		//重命名表
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$res = $objWriter->save($filename); //脚本方式运行，保存在当前目录
		if ($res == NULL) {
			return $filename;
		} else {
			return false;
		}
	}

	/**
	 * 读取excel文件
	 * @param  [file] $tmp [$_FILES获取到的临时文件]
	 * @return [array]      [转化成功的数组]
	 */
	public function readExcel($tmp) {
		require_once 'excel/Excel.php';
		if (!file_exists(APPPATH . 'data/excel/tmp/')) {
			mkdir(APPPATH . 'data/excel/tmp/');
			chmod(APPPATH . 'data/excel/tmp/', 0777);
		}
		$save_path = APPPATH . 'data/excel/tmp/';
		$file_name = $save_path . date('Ymdhis') . ".xls";
		if (copy($tmp, $file_name)) {
			$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
			$PHPExcel = $reader->load($tmp); // 载入excel文件
			$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumm = $sheet->getHighestColumn(); // 取得总列数
			/** 循环读取每个单元格的数据 */
			for ($column = 'A'; $column <= $highestColumm; $column++) {
				//列数是以A列开始
				$head = $sheet->getCell($column . 1)->getValue();
				for ($row = 2; $row <= $highestRow; $row++) {
					//行数是以第1行开始
					$data[$head][] = $sheet->getCell($column . $row)->getValue();
				}
			}
			return $data;
		}
		return false;
	}

}
?>