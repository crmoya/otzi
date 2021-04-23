<?php

class ConsumoMaquinariaController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'exportar', 'export'),
				'roles' => array('gerencia'),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$this->pageTitle = "";

		$model=new ExpedicionesEquipo('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpedicionesEquipo'])){
			$model->attributes=$_GET['ExpedicionesEquipo'];
		}

	
		$cabeceras = [
			['name'=>'Máquina','width'=>'lg'],
			['name'=>'Operador','width'=>'lg'],
			['name'=>'Lts.Totales','width'=>'md'],
			['name'=>'Hrs.Físicas','width'=>'md'],
			['name'=>'Hrs.GPS','width'=>'md'],
			['name'=>'Lts/Hr Esperados[Lts/Hr]','width'=>'md'],
			['name'=>'Lts/Hr Físicos[Lts/Hr]','width'=>'md'],
			['name'=>'Lts/Hr GPS[Lts/Hr]','width'=>'md'],
		];

		if($model->decimales != 0){
			$extra_datos = [
				['campo'=>'equipo','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'litros','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'horas_reales','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'horas_gps','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'litros_hora_esperado','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'litros_hora','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'litros_hora_gps','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
			];
		}
		else{
			$extra_datos = [
				['campo'=>'equipo','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'litros','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'horas_reales','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'horas_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'litros_hora_esperado','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'litros_hora','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'litros_hora_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
			];
		}

		$reports = ExpedicionesEquipo::model()->findAll($model->search());

		$datos = [];
		if(isset($model->agruparPor) && $model->agruparPor != "NINGUNO"){
			if($model->agruparPor == "MAQUINA"){
				$maquinas = [];
				foreach($reports as $report){						
					$cargas = [];
					//combustible
					if($report['tipo'] == 'equipos_propios'){
						$cargas = CargaCombEquipoPropio::model()->findAllByAttributes(['rEquipoPropio_id'=>$report['id']]);
					}
					if($report['tipo'] == 'equipos_arrendados'){
						$cargas = CargaCombEquipoArrendado::model()->findAllByAttributes(['rEquipoArrendado_id'=>$report['id']]);
					}
					
					$litros = 0;
					foreach($cargas as $carga){
						if(isset($model->tipoCombustible_id) && $model->tipoCombustible_id != ""){
							if($model->tipoCombustible_id != $carga->tipoCombustible_id){
								continue;
							}
						}
						$litros += $carga->petroleoLts;
					}

					$horas_reales = 0;
					$horas_gps = 0;
					$litros_hora_esperado = 0;
					$litros_hora = 0;
					$litros_hora_gps= 0;
					$sum_litros_hora_esperado = 0;
					if(array_key_exists($report['equipo_id'],$maquinas)){
						$valores = $maquinas[$report['equipo_id']];
						$litros = (float)$valores['litros'] + $litros;
						$horas_reales = (float)$valores['horas_reales'] + (float)$report['horas_reales'];
						$horas_gps = (float)$valores['horas_gps'] + (float)$report['horas_gps'];
						$sum_litros_hora_esperado = (float)$valores['sum_litros_hora_esperado'] + (float)$report['consumo_esperado'];
					}
					else{
						$horas_reales = (float)$report['horas_reales'];
						$horas_gps = (float)$report['horas_gps'];
						$sum_litros_hora_esperado = (float)$report['consumo_esperado'];
					}
					if($horas_reales > 0){
						$litros_hora = $litros/$horas_reales;
					}
					if($horas_gps > 0){
						$litros_hora_gps = $litros/$horas_gps;
					}
					
					$count = count($maquinas);
					if($count == 0){
						$count = 1;
					}
					
					$maquinas[$report['equipo_id']] = [
						'litros' => $litros,
						'equipo' => $report['equipo'],
						'horas_reales' => $horas_reales,
						'horas_gps' => $horas_gps,
						'litros_hora_esperado' => $litros_hora_esperado,
						'litros_hora' => $litros_hora,
						'litros_hora_gps' => $litros_hora_gps,
						'consumo_esperado' => $sum_litros_hora_esperado / $count,
						'sum_litros_hora_esperado' => $sum_litros_hora_esperado,
					];		
				}

				foreach($maquinas as $valores){
					$dato['equipo'] = $valores['equipo'];
					$dato['litros'] = $valores['litros'];
					$dato['horas_reales'] = $valores['horas_reales'];
					$dato['horas_gps'] = $valores['horas_gps'];
					$dato['litros_hora_esperado'] = $valores['litros_hora_esperado'];
					$dato['litros_hora'] = $valores['litros_hora'];
					$dato['litros_hora_gps'] = $valores['litros_hora_gps'];
					$dato['operador'] = '';
					$datos[] = (object)$dato;
				}	
			}
			if($model->agruparPor == "OPERADOR"){
				$operadores = [];
				foreach($reports as $report){						
					$cargas = [];
					//combustible
					if($report['tipo'] == 'equipos_propios'){
						$cargas = CargaCombEquipoPropio::model()->findAllByAttributes(['rEquipoPropio_id'=>$report['id']]);
					}
					if($report['tipo'] == 'equipos_arrendados'){
						$cargas = CargaCombEquipoArrendado::model()->findAllByAttributes(['rEquipoArrendado_id'=>$report['id']]);
					}
					
					$litros = 0;
					foreach($cargas as $carga){
						if(isset($model->tipoCombustible_id) && $model->tipoCombustible_id != ""){
							if($model->tipoCombustible_id != $carga->tipoCombustible_id){
								continue;
							}
						}
						$litros += $carga->petroleoLts;
					}

					$horas_reales = 0;
					$horas_gps = 0;
					$litros_hora_esperado = 0;
					$litros_hora = 0;
					$litros_hora_gps= 0;
					$sum_litros_hora_esperado = 0;
					if(array_key_exists($report['operador_id'],$operadores)){
						$valores = $operadores[$report['operador_id']];
						$litros = (float)$valores['litros'] + $litros;
						$horas_reales = (float)$valores['horas_reales'] + (float)$report['horas_reales'];
						$horas_gps = (float)$valores['horas_gps'] + (float)$report['horas_gps'];
						$sum_litros_hora_esperado = (float)$valores['sum_litros_hora_esperado'] + (float)$report['consumo_esperado'];
					}
					else{
						$horas_reales = (float)$report['horas_reales'];
						$horas_gps = (float)$report['horas_gps'];
						$sum_litros_hora_esperado = (float)$report['consumo_esperado'];
					}
					if($horas_reales > 0){
						$litros_hora = $litros/$horas_reales;
					}
					if($horas_gps > 0){
						$litros_hora_gps = $litros/$horas_gps;
					}
					
					$count = count($operadores);
					if($count == 0){
						$count = 1;
					}
					$operadores[$report['operador_id']] = [
						'operador' => $report['operador'],
						'litros' => $litros,
						'horas_reales' => $horas_reales,
						'horas_gps' => $horas_gps,
						'litros_hora_esperado' => $litros_hora_esperado,
						'litros_hora' => $litros_hora,
						'litros_hora_gps' => $litros_hora_gps,
						'consumo_esperado' => $sum_litros_hora_esperado / $count,
						'sum_litros_hora_esperado' => $sum_litros_hora_esperado,
					];		
				}

				foreach($operadores as $valores){
					$dato['operador'] = $valores['operador'];
					$dato['litros'] = $valores['litros'];
					$dato['horas_reales'] = $valores['horas_reales'];
					$dato['horas_gps'] = $valores['horas_gps'];
					$dato['litros_hora_esperado'] = $valores['litros_hora_esperado'];
					$dato['litros_hora'] = $valores['litros_hora'];
					$dato['litros_hora_gps'] = $valores['litros_hora_gps'];
					$dato['equipo'] = '';
					$datos[] = (object)$dato;
				}
			}
		}
		else{
			$registros = [];
			foreach($reports as $report){			
				$cargas = [];
				//combustible
				if($report['tipo'] == 'equipos_propios'){
					$cargas = CargaCombEquipoPropio::model()->findAllByAttributes(['rEquipoPropio_id'=>$report['id']]);
				}
				if($report['tipo'] == 'equipos_arrendados'){
					$cargas = CargaCombEquipoArrendado::model()->findAllByAttributes(['rEquipoArrendado_id'=>$report['id']]);
				}
				
				$litros = 0;
				foreach($cargas as $carga){
					if(isset($model->tipoCombustible_id) && $model->tipoCombustible_id != ""){
						if($model->tipoCombustible_id != $carga->tipoCombustible_id){
							continue;
						}
					}
					$litros += $carga->petroleoLts;
				}

				$horas_reales = 0;
				$horas_gps = 0;
				$litros_hora_esperado = 0;
				$litros_hora = 0;
				$litros_hora_gps= 0;
				$sum_litros_hora_esperado = 0;
				if(array_key_exists($report['equipo_id']."-".$report['operador_id'],$registros)){
					$valores = $registros[$report['equipo_id']."-".$report['operador_id']];
					$litros = (float)$valores['litros'] + $litros;
					$horas_reales = (float)$valores['horas_reales'] + (float)$report['horas_reales'];
					$horas_gps = (float)$valores['horas_gps'] + (float)$report['horas_gps'];
					$sum_litros_hora_esperado = (float)$valores['sum_litros_hora_esperado'] + (float)$report['consumo_esperado'];
				}
				else{
					$horas_reales = (float)$report['horas_reales'];
					$horas_gps = (float)$report['horas_gps'];
					$sum_litros_hora_esperado = (float)$report['consumo_esperado'];
				}
				
				if($horas_reales > 0){
					$litros_hora = $litros/$horas_reales;
				}
				if($horas_gps > 0){
					$litros_hora_gps = $litros/$horas_gps;
				}
				
				$count = count($registros);
				if($count == 0){
					$count = 1;
				}
				
				$registros[$report['equipo_id']."-".$report['operador_id']] = [
					'litros' => $litros,
					'horas_reales' => $horas_reales,
					'horas_gps' => $horas_gps,
					'litros_hora_esperado' => $sum_litros_hora_esperado / $count,
					'litros_hora' => $litros_hora,
					'litros_hora_gps' => $litros_hora_gps,
					'sum_litros_hora_esperado' => $sum_litros_hora_esperado,
					'equipo' => $report['equipo'],
					'operador' => $report['operador'],
				];
			}
			
			foreach($registros as $valores){
				$dato['operador'] = $valores['operador'];
				$dato['litros'] = $valores['litros'];
				$dato['horas_reales'] = $valores['horas_reales'];
				$dato['horas_gps'] = $valores['horas_gps'];
				$dato['litros_hora_esperado'] = $valores['litros_hora_esperado'];
				$dato['litros_hora'] = $valores['litros_hora'];
				$dato['litros_hora_gps'] = $valores['litros_hora_gps'];
				$dato['equipo'] = $valores['equipo'];
				$datos[] = (object)$dato;
			}
		}

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
