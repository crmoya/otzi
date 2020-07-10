<?php
class TransaccionesFilter implements PHPExcel_Reader_IReadFilter

{
	function __construct() {
	}

	public function readCell($column, $row, $worksheetName = '') {

		if ((strtoupper($worksheetName) == 'TRANSACCIONES')) // only read the Transacciones worksheet
		{
			if($column == 'B' && $row == 1) // case Fecha
			{
				return true;
			}
			elseif ($column == 'B' && $row >= 5) // case Patente
			{
				return true;
			}
			elseif((($column >= 'E' && $column <= 'Z') || ($column >= 'AA' && $column <= 'AZ')) && $row >= 5) // case Transacciones
			{
				return true;
			}
			else // another case
			{
				return false;
			}
		}
		return false;
	}

}