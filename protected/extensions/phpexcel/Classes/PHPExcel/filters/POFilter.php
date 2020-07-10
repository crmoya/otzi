<?php
class POFilter implements PHPExcel_Reader_IReadFilter

{
	private $columns;
	function __construct($columns = array()) {
		//parent::__construct();
		$this->columns = $columns;
	}

	public function readCell($column, $row, $worksheetName = '') {

		if (!(strtoupper($worksheetName) == 'CAPACIDADES') && !(strtoupper($worksheetName) == 'CAPACIDAD') && !(strtoupper($worksheetName) == 'WORKSHEET'))
		{
			if($column == 'D' && $row == 1)
				return true;
			if($column == 'D' && $row == 2)
				return true;
			return $row >= 10 && in_array($column, $this->columns);
		}
		return false;	
	}

}