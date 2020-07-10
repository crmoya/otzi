<?php
class BusCirculaFilter implements PHPExcel_Reader_IReadFilter

{
	function __construct() {
	}

	public function readCell($column, $row, $worksheetName = '') {

		if($column == 'B' && $row == 1) // case Fecha
		{
			return true;
		}
		elseif ($column == 'B' && $row >= 5) // case Patente
		{
			return true;
		}
		elseif((($column >= 'G' && $column <= 'Z') || ($column >= 'AA' && $column <= 'AZ') || $column =='BA' || $column == 'BB') && $row >= 5) // case estado
		{
			return true;
		}
		else // another case
		{
			return false;
		}
	}
}