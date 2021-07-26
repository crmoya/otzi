<?php

class ConsumoCamionesController extends Controller
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

		$model=new ExpedicionesCamion('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpedicionesCamion'])){
			$model->attributes=$_GET['ExpedicionesCamion'];
		}

	
		$cabeceras = [
			['name'=>'Camión','width'=>'lg'],
			['name'=>'Chofer','width'=>'lg'],
			['name'=>'Lts.Físicos','width'=>'md'],
			['name'=>'Km.Físicos','width'=>'md'],
			['name'=>'Km.GPS','width'=>'md'],
			['name'=>'Kms/Lt Físicos[Kms/Lt]','width'=>'md'],
			['name'=>'Kms/Lt GPS[Kms/Lt]','width'=>'md'],
			['name'=>'Kms/Lt Esperados[Kms/Lt]','width'=>'md'],
		];

		if($model->decimales != 0){
			$extra_datos = [
				['campo'=>'camion','exportable','dots'=>"md"],
				['campo'=>'chofer','exportable','dots'=>'md'],
				['campo'=>'litros','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'km_recorridos','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'km_gps','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'km_litro','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'km_litro_gps','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'consumo_esperado','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
			];
		}
		else{
			$extra_datos = [
				['campo'=>'camion','exportable','dots'=>"md"],
				['campo'=>'chofer','exportable','dots'=>'md'],
				['campo'=>'litros','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'km_recorridos','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'km_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'km_litro','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'km_litro_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'consumo_esperado','exportable', 'format'=>'number','acumulado'=>'suma'],
			];
		}

		$reports = ExpedicionesCamion::model()->findAll($model->search());

		$datos = [];
		if(isset($model->agruparPor) && $model->agruparPor != "NINGUNO"){
			if($model->agruparPor == "MAQUINA"){
				$camiones = [];
				foreach($reports as $report){						
					$cargas = [];
					//combustible
					if($report['tipo'] == 'camiones_propios'){
						$cargas = CargaCombCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
					}
					if($report['tipo'] == 'camiones_arrendados'){
						$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
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

					$kms = 0;
					$kms_gps = 0;
					$kms_litro = 0;
					$kms_litro_gps = 0;
					$sum_consumo_esperado = 0;
					if(array_key_exists($report['camion'],$camiones)){
						$valores = $camiones[$report['camion']];
						$kms = (float)$valores['km_recorridos'] + (float)$report['km_recorridos'];
						$kms_gps = (float)$valores['km_gps'] + (float)$report['km_gps'];
						$litros = (float)$valores['litros'] + $litros;
						$sum_consumo_esperado = (float)$valores['sum_consumo_esperado'] + (float)$report['consumo_esperado'];
					}
					else{
						$kms = (float)$report['km_recorridos'];
						$kms_gps = (float)$report['km_gps'];
						$sum_consumo_esperado = (float)$report['consumo_esperado'];
					}
					if($litros > 0){
						$kms_litro = $kms/$litros;
						$kms_litro_gps = $kms_gps/$litros;
					}
					
					$count = count($camiones);
					if($count == 0){
						$count = 1;
					}
					$camiones[$report['camion']] = [
						'km_recorridos' => $kms,
						'km_gps' => $kms_gps,
						'litros' => $litros,
						'km_litro' => $kms_litro,
						'km_litro_gps' => $kms_litro_gps,
						'consumo_esperado' => $sum_consumo_esperado / $count,
						'sum_consumo_esperado' => $sum_consumo_esperado,
					];		
				}

				foreach($camiones as $camion => $valores){
					$dato['camion'] = $camion;
					$dato['km_recorridos'] = $valores['km_recorridos'];
					$dato['km_gps'] = $valores['km_gps'];
					$dato['litros'] = $valores['litros'];
					$dato['km_litro'] = $valores['km_litro'];
					$dato['km_litro_gps'] = $valores['km_litro_gps'];
					$dato['consumo_esperado'] = $valores['consumo_esperado'];
					$dato['chofer'] = '';
					$datos[] = (object)$dato;
				}	
			}
			if($model->agruparPor == "OPERADOR"){
				$choferes = [];
				foreach($reports as $report){						
					$cargas = [];
					//combustible
					if($report['tipo'] == 'camiones_propios'){
						$cargas = CargaCombCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
					}
					if($report['tipo'] == 'camiones_arrendados'){
						$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
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

					$kms = 0;
					$kms_gps = 0;
					$kms_litro = 0;
					$kms_litro_gps = 0;
					$sum_consumo_esperado = 0;
					if(array_key_exists($report['chofer'],$choferes)){
						$valores = $choferes[$report['chofer']];
						$kms = (float)$valores['km_recorridos'] + (float)$report['km_recorridos'];
						$kms_gps = (float)$valores['km_gps'] + (float)$report['km_gps'];
						$litros = (float)$valores['litros'] + $litros;
						$sum_consumo_esperado = (float)$valores['sum_consumo_esperado'] + (float)$report['consumo_esperado'];
					}
					else{
						$kms = (float)$report['km_recorridos'];
						$kms_gps = (float)$report['km_gps'];
						$sum_consumo_esperado = (float)$report['consumo_esperado'];
					}
					if($litros > 0){
						$kms_litro = $kms/$litros;
						$kms_litro_gps = $kms_gps/$litros;
					}
					
					$count = count($choferes);
					if($count == 0){
						$count = 1;
					}
					$choferes[$report['chofer']] = [
						'km_recorridos' => $kms,
						'km_gps' => $kms_gps,
						'litros' => $litros,
						'km_litro' => $kms_litro,
						'km_litro_gps' => $kms_litro_gps,
						'consumo_esperado' => $sum_consumo_esperado / $count,
						'sum_consumo_esperado' => $sum_consumo_esperado,
					];		
				}

				foreach($choferes as $chofer => $valores){
					$dato['chofer'] = $chofer;
					$dato['km_recorridos'] = $valores['km_recorridos'];
					$dato['km_gps'] = $valores['km_gps'];
					$dato['litros'] = $valores['litros'];
					$dato['km_litro'] = $valores['km_litro'];
					$dato['km_litro_gps'] = $valores['km_litro_gps'];
					$dato['consumo_esperado'] = $valores['consumo_esperado'];
					$dato['camion'] = '';
					$datos[] = (object)$dato;
				}	
			}
		}
		else{
			$registros = [];
			foreach($reports as $report){						
				$cargas = [];
				//combustible
				if($report['tipo'] == 'camiones_propios'){
					$cargas = CargaCombCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
				}
				if($report['tipo'] == 'camiones_arrendados'){
					$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
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

				$kms = 0;
				$kms_gps = 0;
				$kms_litro = 0;
				$kms_litro_gps = 0;
				$sum_consumo_esperado = 0;
				if(array_key_exists($report['camion_id']."-".$report['chofer_id'],$registros)){
					$valores = $registros[$report['camion_id']."-".$report['chofer_id']];
					$kms = (float)$valores['km_recorridos'] + (float)$report['km_recorridos'];
					$kms_gps = (float)$valores['km_gps'] + (float)$report['km_gps'];
					$litros = (float)$valores['litros'] + $litros;
					$sum_consumo_esperado = (float)$valores['sum_consumo_esperado'] + (float)$report['consumo_esperado'];
				}
				else{
					$kms = (float)$report['km_recorridos'];
					$kms_gps = (float)$report['km_gps'];
					$sum_consumo_esperado = (float)$report['consumo_esperado'];
				}
				if($litros > 0){
					$kms_litro = $kms/$litros;
					$kms_litro_gps = $kms_gps/$litros;
				}
				
				$count = count($registros);
				if($count == 0){
					$count = 1;
				}
				$registros[$report['camion_id']."-".$report['chofer_id']] = [
					'km_recorridos' => $kms,
					'km_gps' => $kms_gps,
					'litros' => $litros,
					'km_litro' => $kms_litro,
					'km_litro_gps' => $kms_litro_gps,
					'consumo_esperado' => $sum_consumo_esperado / $count,
					'sum_consumo_esperado' => $sum_consumo_esperado,
					'camion' => $report['camion'],
					'chofer' => $report['chofer'],
				];		
			}

			foreach($registros as $valores){
				$dato['chofer'] = $valores['chofer'];
				$dato['km_recorridos'] = $valores['km_recorridos'];
				$dato['km_gps'] = $valores['km_gps'];
				$dato['litros'] = $valores['litros'];
				$dato['km_litro'] = $valores['km_litro'];
				$dato['km_litro_gps'] = $valores['km_litro_gps'];
				$dato['consumo_esperado'] = $valores['consumo_esperado'];
				$dato['camion'] = $valores['camion'];
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
