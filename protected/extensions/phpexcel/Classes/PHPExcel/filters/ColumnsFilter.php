<?php
class ColumnsFilter implements PHPExcel_Reader_IReadFilter

{
	private $columns;
	function __construct($columns = array()) {
		//parent::__construct();
		$this->columns = $columns;
	}

	public function readCell($column, $row, $worksheetName = '') {
		return in_array($column, $this->columns);
	}

}