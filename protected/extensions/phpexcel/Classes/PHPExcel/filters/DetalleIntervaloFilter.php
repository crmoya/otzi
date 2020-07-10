<?php
class DetalleIntervaloFilter implements PHPExcel_Reader_IReadFilter
{
	public function readCell($column, $row, $worksheetName = '') {
		if($column == 'B' && $row == 1)
		{
			return true;
		}
		elseif($row >= 5 && ($row-6) % 4 == 0 && ($column == 'B' || ($column >= 'F' && $column <= 'Z') || ($column >= 'AA' && $column <= 'AZ') || $column == 'BA'))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

}