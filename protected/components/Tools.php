<?php
class Tools{
	/*
	public static function conectarOK(){
		if(Tools::dias() <= 0){
			return false;
		}
		else{
			return true;
		}
	}*/
	
	public static function dias(){
		$day = (int)date("d");
		$month = (int)date("m");
		$quedan = 20-$day;
		if($month == 12){
			$quedan = 0;
		}
		return $quedan;
	}
	
	public static function getEP($eps,$mes,$agno,$resolucion){
		$arr_ret = array();
		foreach($eps as $por_tipo){
			$tipo = $por_tipo['tipo'];
			$flujos = $por_tipo['eps'];
			foreach($flujos as $flujo){
				if($flujo->mes == $mes && $flujo->agno == $agno && $flujo->resoluciones_id == $resolucion){
					$arr_ret[] = array('tipo'=>$tipo,'ep'=>$flujo);
				}
			}
		}
		return $arr_ret;
	}
	
	public static function sacaPorcentaje($palabra){
		return str_replace("%","_PORCENTAJE_",$palabra);
	}
	
	public static function ponePorcentaje($palabra){
		return str_replace("_PORCENTAJE_","%",$palabra);
	}
	public static function cambiarSeparadorDecimal($porcentaje)
	{
		return str_replace(".", ",", $porcentaje);
	}
	public static function estadoGarantia($estado){
		if($estado){
			return "Vigente";
		}else{
			return "No vigente";
		}
	}
	
	public static function getEstadosGarantias(){
		return array(
				array('id'=>'1','nombre'=>'Vigente'),
				array('id'=>'0','nombre'=>'NO Vigente'),
		);
	}
	
	public static function getTiposArchivos(){
		return "doc,pdf,xls,xlsx,docx,jpg, gif, png,zip,rar,tar,tgz,7z,gzip";
	}
	public static function calculaNeto($valorConIva){
		return $valorConIva/1.19;
	}
	
	public static function getTiposMontos(){
		return array(
			array('id'=>'$','nombre'=>'$'),
			array('id'=>'UF','nombre'=>'UF')
		);
	}
	public static function fixFecha($fecha){
		$fechaArr = explode("/", $fecha);
		if(count($fechaArr)==3) return $fechaArr[2]."-".$fechaArr[1]."-".$fechaArr[0];
		else return "";
	}
	
	public static function fixPlata($monto){
		$monto = str_replace(".","",$monto);
		$monto = str_replace(",","",$monto);
		return $monto;
	}
	
	public static function fixPlataDecimales($monto){
		$monto = str_replace(".","",$monto);
		$monto = str_replace(",",".",$monto);
		return $monto;
	}
	
	public static function backFecha($fecha){
		$fechaArr = explode("-", $fecha);
		if(count($fechaArr)==3) return $fechaArr[2]."/".$fechaArr[1]."/".$fechaArr[0];
		else return "";
	}
	
	public static function dv($r){
		$s=1;
		for($m=0;$r!=0;$r/=10)
			$s=($s+$r%10*(9-$m++%6))%11;
		return chr($s?$s+47:75);
	}
	
	public static function esAntesODurante($mes,$agno){
		$mesActual = date("m");
		$agnoActual = date("Y");
		if($agnoActual > $agno){
			return true;
		}else if($agnoActual == $agno){
			if($mesActual >= $mes){
				return true;
			}
			else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public static function validaRut($rut){
		$rut = strtoupper($rut);
		$pos_guion = strrpos($rut,"-");
		if($pos_guion > 0 && $pos_guion == strlen($rut)-2){
			$r = substr($rut,0,$pos_guion);
			$dv = substr($rut,$pos_guion+1);
			return Tools::dv($r)==$dv;
		}
		return false;
	} 
	
	public static function backMes($mes){
		switch ($mes) {
		    case "Enero":
		        return 1;
		    case "Febrero":
		        return 2;
		    case "Marzo":
		        return 3;
		    case "Abril":
		        return 4;
		    case "Mayo":
		        return 5;
		    case "Junio":
		        return 6;
		    case "Julio":
		        return 7;
		    case "Agosto":
		        return 8;
		    case "Septiembre":
		        return 9;
		    case "Octubre":
		        return 10;
		    case "Noviembre":
		        return 11;
		    case "Diciembre":
		        return 12;        
		}
	}
	
	public static function getMes($mes){
		switch ($mes) {
		    case 1:
		        return "Enero";
		    case 2:
		        return "Febrero";
		    case 3:
		        return "Marzo";
		    case 4:
		        return "Abril";
		    case 5:
		        return "Mayo";
		    case 6:
		        return "Junio";
		    case 7:
		        return "Julio";
		    case 8:
		        return "Agosto";
		    case 9:
		        return "Septiembre";
		    case 10:
		        return "Octubre";
		    case 11:
		        return "Noviembre";
		    case 12:
		        return "Diciembre";        
		}
	}
	
	public static function avanzaMes($mes,$agno){
		if($mes == 12){
			$arr = array('mes'=>1,'agno'=>$agno+1);
			return $arr;
		}else{
			$arr = array('mes'=>$mes+1,'agno'=>$agno);
			return $arr;
		}
	}
	
	public static function getMonth($date){
		$arr = explode("-", $date);
		if(count($arr)==3){
			return $arr[1];
		}else{
			return "";
		}
	}
	
	public static function getYear($date){
		$arr = explode("-", $date);
		if(count($arr)==3){
			return $arr[0];
		}else{
			return "";
		}
	}
}