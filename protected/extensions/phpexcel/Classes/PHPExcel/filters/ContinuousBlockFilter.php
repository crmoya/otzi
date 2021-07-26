<?php
class ContinuousBlockFilter implements PHPExcel_Reader_IReadFilter

{
	private $init_row;
	private $final_row;
	private $init_column;
	private $final_column;
	function __construct($init_row = 1, $final_row = 9999999, $init_column = 'A', $final_column = 'ZZ' ) {
		//parent::__construct();
		$this->init_row = $init_row;
		$this->init_column = $init_column;
		$this->final_row = $final_row;
		$this->final_column = $final_column;
	}

	public function readCell($column, $row, $worksheetName = '') {

		//  Read rows 1 to 7 and columns A to E only

		if ($row >= $this->init_row && $row <= $this->final_row) {

			if (in_array($column,range($this->init_column,$this->final_column))) {

				return true;

			}

		}

		return false;

	}

}