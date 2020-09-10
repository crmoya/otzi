<?php
class Tools
{
	public static $XS_CELL = 30;
	public static $SM_CELL = 50;
	public static $MD_CELL = 100;
	public static $LG_CELL = 150;

	public static $UNIDADES_TIEMPO = [
		['id'=>1, 'nombre'=>'HORAS',],
		['id'=>2, 'nombre'=>'DÍAS',],
		['id'=>3, 'nombre'=>'SEMANAS',],
		['id'=>4, 'nombre'=>'MINUTOS',],
		['id'=>5, 'nombre'=>'MESES',],
	];

	public static function removerApostrofes($text){
		$text = htmlspecialchars($text);
		$text = str_replace("'","",$text);
		return str_replace('"',"",$text);
	}

	public static function dirToArray($dir) {
		$result = array();
		if(!is_dir($dir)) return array();
		$cdir = scandir($dir);
		foreach ($cdir as $key => $value)
		{
		   if (!in_array($value,array(".","..")))
		   {
			  if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
			  {
				 $result[$value] = Tools::dirToArray($dir . DIRECTORY_SEPARATOR . $value);
			  }
			  else
			  {
				 $result[] = $value;
			  }
		   }
		}
		return $result;
	 }


	public static function reverseDate($date){
		$fechaArr = explode("-", $date);
		if (count($fechaArr) == 3) return $fechaArr[2] . "-" . $fechaArr[1] . "-" . $fechaArr[0];
		else return "";
	}

	public static function getExpenses($page)
	{
		header('Content-Type: application/json'); // Specify the type of data
		$ch = curl_init('https://api.rindegastos.com/v1/getExpenses?Page=' . $page); // Initialise cURL
		$authorization = "Authorization: Bearer " . "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMzA2NDIiLCJjb21wYW55X2lkIjoiMzY0NiIsInJhbmRvbSI6InJhbmRBUEk1ZjEwNTdmYzVjOWU0MC4zNzY0MjU0MSJ9.Y3YjaG4SaO0SY9LPE_Uwuf809J4d_1lTTVgX8yCaQ5k"; // Prepare the authorisation token
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)); // Inject the token into the header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
		$result = curl_exec($ch); // Execute the cURL statement
		curl_close($ch); // Close the cURL connection
		return json_decode($result); // Return the received data
	}

	public static function getReports($page)
	{
		header('Content-Type: application/json'); // Specify the type of data
		$ch = curl_init('https://api.rindegastos.com/v1/getExpenseReports?Page=' . $page); // Initialise cURL
		$authorization = "Authorization: Bearer " . "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMzA2NDIiLCJjb21wYW55X2lkIjoiMzY0NiIsInJhbmRvbSI6InJhbmRBUEk1ZjEwNTdmYzVjOWU0MC4zNzY0MjU0MSJ9.Y3YjaG4SaO0SY9LPE_Uwuf809J4d_1lTTVgX8yCaQ5k"; // Prepare the authorisation token
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)); // Inject the token into the header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
		$result = curl_exec($ch); // Execute the cURL statement
		curl_close($ch); // Close the cURL connection
		return json_decode($result); // Return the received data
	}

	public static function cleanDirectory($path)
	{
		$dir = $path . "/";
		$files = scandir($dir);
		foreach ($files as $f) {
			if ($f != '.' && $f != '..') {
				if (is_file($dir . $f)) {
					unlink($dir . $f);
				}
			}
		}
		$files = scandir($dir . "Faena/");
		foreach ($files as $f) {
			if ($f != '.' && $f != '..') {
				if (is_file($dir . "Faena/" . $f)) {
					unlink($dir . "Faena/" . $f);
				}
			}
		}
	}

	public static function cleanExport($data, $tipo = 'string')
	{
		$devolver = $data;
		$devolver = str_replace('"', '', $devolver);
		$devolver = str_replace('*', '', $devolver);
		$devolver = str_replace(':', '', $devolver);
		$devolver = str_replace('<', '', $devolver);
		$devolver = str_replace('>', '', $devolver);
		$devolver = str_replace('?', '', $devolver);
		$devolver = str_replace('/', '', $devolver);
		$devolver = str_replace('\\', '', $devolver);
		$devolver = str_replace('|', '', $devolver);
		$devolver = str_replace('~', '', $devolver);
		$devolver = str_replace('#', '', $devolver);
		$devolver = str_replace('%', '', $devolver);
		$devolver = str_replace('&', '', $devolver);
		$devolver = str_replace(':', '', $devolver);
		$devolver = str_replace('{', '', $devolver);
		$devolver = str_replace('}', '', $devolver);
		if ($tipo == 'string') {
			$devolver = str_replace('.', '', $devolver);
		}
		return $devolver;
	}

	public static function getTipoDocumento($id)
	{
		$dev = "";
		switch ($id) {
			case 'B':
				$dev = "BOLETA";
				break;
			case 'N':
				$dev = "NOTA DE CRÉDITO";
				break;
			case 'F':
				$dev = "FACTURA";
				break;
			default:
				break;
		}
		return $dev;
	}

	public static function getTipoDocumentoComb($id)
	{
		$dev = "";
		switch ($id) {
			case 'B':
				$dev = "BOLETA";
				break;
			case 'G':
				$dev = "GUÍA";
				break;
			case 'F':
				$dev = "FACTURA";
				break;
			default:
				break;
		}
		return $dev;
	}

	public static function listarTiposDocumentos()
	{
		/*return array(
				array('id'=>'Rendición fondo por rendir','nombre'=>'Rendición fondo por rendir'),
				array('id'=>'Rendición Anticipo Proveedores','nombre'=>'Rendición Anticipo Proveedores'),
				array('id'=>'Remesa','nombre'=>'Remesa'),);
                */
		return array(
			array('id' => 'B', 'nombre' => 'Boleta'),
			array('id' => 'F', 'nombre' => 'Factura'),
			array('id' => 'N', 'nombre' => 'Nota de Crédito'),
		);
	}

	public static function listarTiposDocumentosComb()
	{
		/*return array(
				array('id'=>'Rendición fondo por rendir','nombre'=>'Rendición fondo por rendir'),
				array('id'=>'Rendición Anticipo Proveedores','nombre'=>'Rendición Anticipo Proveedores'),
				array('id'=>'Remesa','nombre'=>'Remesa'),);
                */
		return array(
			array('id' => 'B', 'nombre' => 'Boleta'),
			array('id' => 'F', 'nombre' => 'Factura'),
			array('id' => 'G', 'nombre' => 'Guía'),
		);
	}

	public static function listarHoras()
	{
		$horas = array();
		$horas[0] = array('id' => '08:00', 'nombre' => '08:00');
		$horas[1] = array('id' => '08:30', 'nombre' => '08:30');
		$horas[2] = array('id' => '09:00', 'nombre' => '09:00');
		$horas[3] = array('id' => '09:30', 'nombre' => '09:30');
		$horas[4] = array('id' => '10:00', 'nombre' => '10:00');
		$horas[5] = array('id' => '10:30', 'nombre' => '10:30');
		$horas[6] = array('id' => '11:00', 'nombre' => '11:00');
		$horas[7] = array('id' => '11:30', 'nombre' => '11:30');
		$horas[8] = array('id' => '12:00', 'nombre' => '12:00');
		$horas[9] = array('id' => '12:30', 'nombre' => '12:30');
		$horas[10] = array('id' => '13:00', 'nombre' => '13:00');
		$horas[11] = array('id' => '13:30', 'nombre' => '13:30');
		$horas[12] = array('id' => '14:00', 'nombre' => '14:00');
		$horas[13] = array('id' => '14:30', 'nombre' => '14:30');
		$horas[14] = array('id' => '15:00', 'nombre' => '15:00');
		$horas[15] = array('id' => '15:30', 'nombre' => '15:30');
		$horas[16] = array('id' => '16:00', 'nombre' => '16:00');
		$horas[17] = array('id' => '16:30', 'nombre' => '16:30');
		$horas[18] = array('id' => '17:00', 'nombre' => '17:00');
		$horas[19] = array('id' => '17:30', 'nombre' => '17:30');
		$horas[20] = array('id' => '18:00', 'nombre' => '18:00');
		$horas[21] = array('id' => '18:30', 'nombre' => '18:30');
		$horas[22] = array('id' => '19:00', 'nombre' => '19:00');
		$horas[23] = array('id' => '19:30', 'nombre' => '19:30');
		$horas[24] = array('id' => '20:00', 'nombre' => '20:00');
		$horas[25] = array('id' => '20:30', 'nombre' => '20:30');
		$horas[26] = array('id' => '21:00', 'nombre' => '21:00');
		$horas[27] = array('id' => '21:30', 'nombre' => '21:30');
		$horas[28] = array('id' => '22:00', 'nombre' => '22:00');
		$horas[29] = array('id' => '22:30', 'nombre' => '22:30');
		$horas[30] = array('id' => '23:00', 'nombre' => '23:00');
		$horas[31] = array('id' => '23:30', 'nombre' => '23:30');
		$horas[32] = array('id' => '00:00', 'nombre' => '00:00');
		return $horas;
	}
	public static function fixFecha($fecha)
	{
		$fechaArr = explode("/", $fecha);
		if (count($fechaArr) == 3) return $fechaArr[2] . "-" . $fechaArr[1] . "-" . $fechaArr[0];
		else return "";
	}
	public static function backFecha($fecha)
	{
		$fechaArr = explode("-", $fecha);
		if (count($fechaArr) == 3) return $fechaArr[2] . "/" . $fechaArr[1] . "/" . $fechaArr[0];
		else return "";
	}

	public static function fixEstadoInforme($estado){
		if($estado == 0){
			return "Abierto o En proceso";
		}
		if($estado == 1){
			return "Cerrado";
		}
	}

	public static function getNombreUnidad($unidad)
	{
		if ($unidad == "U") {
			return "unidades";
		}
		if ($unidad == "L") {
			return "lts.";
		}
		if ($unidad == "K") {
			return "kgs.";
		}
	}

	public static function sacarExtension($archivo)
	{
		return substr($archivo, 0, strpos($archivo, "."));
	}

	public static function dv($r)
	{
		$s = 1;
		for ($m = 0; $r != 0; $r /= 10)
			$s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
		return chr($s ? $s + 47 : 75);
	}

	public static function validaRut($rut)
	{
		$rut = strtoupper($rut);
		$pos_guion = strrpos($rut, "-");
		if ($pos_guion > 0 && $pos_guion == strlen($rut) - 2) {
			$r = substr($rut, 0, $pos_guion);
			$dv = substr($rut, $pos_guion + 1);
			return Tools::dv($r) == $dv;
		}
		return false;
	}
}
