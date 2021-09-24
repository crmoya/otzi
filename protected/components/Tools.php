<?php
class Tools
{
	public static $XS_CELL = 30;
	public static $SM_CELL = 50;
	public static $MD_CELL = 100;
	public static $LG_CELL = 240;
	public static $XL_CELL = 450;

	const FACTOR_KMS_MILLAS = 1.60934;

	const CATEGORIAS_COMBUSTIBLES_CHIPAX = [
		'01 Bencina',
		'02 Petróleo',
		'02.a Gas Licuado Vehicular',
		'Cop. 01 Bencina',
		'Cop. 02 Petróleo',
		'Cop. 02.a Gas Licuado Vehicular',
		'Cop. 03.1 Parafina',
		'CG.- 01 Bencina',
		'CG.- 02 Petróleo',

	];
	const CATEGORIAS_REMUNERACIONES_CHIPAX = [
		'13 Remuneraciónes del Personal',
		'13.2 Remuneraciones Personal externo',
		'Cop. 13.1 Remuneraciones del Personal Indirecto',
		'Cop. 13.2 Remuneraciones Personal externo',
		'14 Honorarios Profesionales',
		'14.1 Honorarios Profesionales Abogados',
		'14.2 Honorarios Profesionales Notarios, CBR',
		'14.3 Honorarios Técnicos, Profesionales, Otros',
		'Cop. 14 Honorarios Profesionales',
		'Cop. 14.1 Profesionales Abogados',
		'Cop. 14.2 Honorarios Técnicos, Profesionales, Otro',
		'Cop. 14.3 Honorarios Profesionales Notarios, CBR',
		'CG.- 13 Remuneraciones del Personal',
		'CG.-Honorarios Profesionales',
		'CG.- 14 Honorarios Profesionales Abogados',
		'CG.- 14.2_Honorarios Técnicos, Profesionales Otros',
		'CG.- 14.3 Honorarios Profesionales Notarios, CBR',
	];

	const CATEGORIAS_REMUNERACIONES_RINDEGASTOS = [
		'13 Remuneraciones del Personal',
		'13.1 Remuneraciones del Personal Indirecto',
		'13.2 Remuneraciones Personal externo',
		'13.2 Remuneraciones personal externo (por ej. Moncho)',
		'13.2 Remuneraciones personal externo por ej. Moncho',
		'14.3 Honorarios Técnicos, Profesionales, Otros',
		'2-1-06-007 Imposiciones valor nominal 2019',
		'2-1-06-008 Imposiciones valor nominal 2018',
		'Cop. 2-1-06-008 Imposiciones valor nominal 2018'
	];

	public static function charAt($string, $i){
		return substr($string, $i, 1);
	}
	
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

	public static function getExpenses($page, $policy)
	{
		header('Content-Type: application/json'); // Specify the type of data
		$ch = curl_init('https://api.rindegastos.com/v1/getExpenses?&Status=1&ExpensePolicyId=' . $policy . '&Page=' . $page); // Initialise cURL
		$authorization = "Authorization: Bearer " . "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMzA2NDIiLCJjb21wYW55X2lkIjoiMzY0NiIsInJhbmRvbSI6InJhbmRBUEk1ZjEwNTdmYzVjOWU0MC4zNzY0MjU0MSJ9.Y3YjaG4SaO0SY9LPE_Uwuf809J4d_1lTTVgX8yCaQ5k"; // Prepare the authorisation token
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization)); // Inject the token into the header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
		$result = curl_exec($ch); // Execute the cURL statement
		curl_close($ch); // Close the cURL connection
		return json_decode($result); // Return the received data
	}

	public static function getReports($page, $policy)
	{
		header('Content-Type: application/json'); // Specify the type of data
		$ch = curl_init('https://api.rindegastos.com/v1/getExpenseReports?ExpensePolicyId=' . $policy . '&Page=' . $page); // Initialise cURL
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

	public static function traducirTipoDocumento($tipoDocumento){
		$dev = "";
		$tipoDocumento = trim(strtolower($tipoDocumento));
		switch ($tipoDocumento) {
			case 'boleta':
				$dev = "B";
				break;
			case 'boleta':
				$dev = "B";
				break;
			case 'boleta de honorarios':
				$dev = "BH";
				break;
			case 'boleta de honorarios propia':
				$dev = "BHP";
				break;
			case 'boleta de honorarios terceros':
				$dev = "BHT";
				break;
			case 'boleta de prestación de servicios de terceros':
				$dev = "BPS";
				break;
			case 'boleta honorarios terceros':
				$dev = "BHT";
				break;
			case 'contrato de compra-venta':
				$dev = "CCV";
				break;
			case 'factura afecta':
				$dev = "FA";
				break;
			case 'factura combustible':
				$dev = "FC";
				break;
			case 'factura excenta':
				$dev = "FE";
				break;
			case 'factura exenta':
				$dev = "FE";
				break;
			case 'factura no afecta':
				$dev = "F";
				break;
			case 'interes planilla previsional':
				$dev = "IPP";
				break;
			case 'interés planilla previsional':
				$dev = "IPP";
				break;
			case 'nota de credito':
				$dev = "N";
				break;
			case 'nota de crédito':
				$dev = "N";
				break;
			case 'planilla remuneración o previsional':
				$dev = "PRP";
				break;
			case 'planilla remuneracion o previsional':
				$dev = "PRP";
				break;
			case 'planilla remuneracion previsional':
				$dev = "PRP";
				break;	
			case 'planilla remuneración previsional':
				$dev = "PRP";
				break;
			case 'planilla remuneración o previsionalñ':
				$dev = "PRP";
				break;	
			case 'vale':
				$dev = "V";
				break;			
			default:
				$dev = "";
				break;
		}
		return $dev;
	}

	public static function getTipoDocumento($id)
	{
		$dev = "";
		switch ($id) {
			case 'B':
				$dev = "BOLETA";
				break;
			case 'F':
				$dev = "FACTURA";
				break;
			case 'F':
				$dev = "FACTURA";
				break;
			case 'FA':
				$dev = "FACTURA AFECTA";
				break;
			case 'G':
				$dev = "GUÍA";
				break;
			case 'N':
				$dev = "NOTA DE CRÉDITO";
				break;
			case 'V':
				$dev = "VALE";
				break;
			case 'BHP':
				$dev = "BOLETA HONORARIOS PROPIA";
				break;
			case 'BHT':
				$dev = "BOLETA HONORARIOS TERCEROS";
				break;
			case 'BPS':
				$dev = "BOLETA PRESTACIÓN SERVICIOS TERCERO";
				break;
			case 'CCV':
				$dev = "CONTRATO COMPRA VENTA";
				break;
			case 'FC':
				$dev = "FACTURA COMBUSTIBLE";
				break;
			case 'FE':
				$dev = "FACTURA EXENTA";
				break;
			case 'R':
				$dev = "REMESA";
				break;
			case 'RAP':
				$dev = "RENDICIÓN ANTICIPO PROVEEDORES";
				break;
			case 'RFR':
				$dev = "RENDICIÓN FONDO POR RENDIR";
				break;
			case 'IPP':
				$dev = "INTERÉS PLANILLA PREVISIONAL";
				break;
			case 'PRP':
				$dev = "PLANILLA DE REMUNERACIÓN O PREVISIONAL";
				break;
			default:
				$dev = "OTRO";
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
			array('id' => '', 'nombre' => 'Otro'),
			array('id' => 'B', 'nombre' => 'Boleta'),
			array('id' => 'F', 'nombre' => 'Factura'),
			array('id' => 'FA', 'nombre' => 'Factura afecta'),
			array('id' => 'G', 'nombre' => 'Guía'),
			array('id' => 'N', 'nombre' => 'Nota de crédito'),
			array('id' => 'V', 'nombre' => 'Vale'),
			array('id' => 'BHP', 'nombre' => 'Boleta de honorarios propia'),
			array('id' => 'BHT', 'nombre' => 'Boleta de honorarios terceros'),
			array('id' => 'BPS', 'nombre' => 'Boleta de prestación de servicios tercero'),
			array('id' => 'CCV', 'nombre' => 'Contrato de compra-venta'),
			array('id' => 'FC', 'nombre' => 'Factura combustible'),
			array('id' => 'FE', 'nombre' => 'Factura exenta'),
			array('id' => 'R', 'nombre' => 'Remesa'),
			array('id' => 'RAP', 'nombre' => 'Rendición anticipo proveedores'),
			array('id' => 'RFR', 'nombre' => 'Rendición fondo por rendir'),
			array('id' => 'IPP', 'nombre' => 'Interés planilla previsional'),
			array('id' => 'PRP', 'nombre' => 'Planilla de remuneración o previsional'),
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
		if ($unidad == "B") {
			return "Balde";
		}
		if ($unidad == "G") {
			return "Galón";
		}
		if ($unidad == "D") {
			return "Día";
		}
		if ($unidad == "M3") {
			return "Mts. cúbicos";
		}
		if ($unidad == "M") {
			return "Mts.";
		}
	}

	public static function convertUnidad($unidad)
	{
		$unidad = trim($unidad);
		if ($unidad == "Balde") {
			return "B";
		}
		if ($unidad == "día") {
			return "D";
		}
		if ($unidad == "galon") {
			return "G";
		}
		if ($unidad == "Kilos") {
			return "K";
		}
		if ($unidad == "Litros") {
			return "L";
		}
		if ($unidad == "Metro cúbico") {
			return "M3";
		}
		if ($unidad == "Metros") {
			return "M";
		}
		if ($unidad == "Unidades") {
			return "U";
		}
		return "";
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

	public static function generateSecretChipax(){
		$secret = date("Y-m-d i")."chipax-mogly-secret";
		$encrypted = md5($secret);
		return $encrypted;
	}


	public static function chipaxSecret($retraso) {
        $fecha = new DateTime(date("Y-m-d H:i"));
        $fecha->sub(new DateInterval('PT' . (int) $retraso . 'M'));
        $secret = $fecha->format('Y-m-d i') . "chipax-mogly-secret";
        $encrypted = md5($secret);
        return $encrypted;
    }

}
