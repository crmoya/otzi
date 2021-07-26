<?php
class CSVFilter implements PHPExcel_Reader_IReadFilter
{
	private $columns;
	function __construct($columns = array()) {
		//parent::__construct();
		$this->columns = $columns;
	}

	public function readCell($column, $row, $worksheetName = '') {
		if($row >= 8)
		{
			return in_array($column, $this->columns);
		}
		else
		{
			return false;
		}
		
	}

}