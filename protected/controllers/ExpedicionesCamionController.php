<?php

class ExpedicionesCamionController extends Controller
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
			$model->faena_id = $_GET['ExpedicionesCamion']['faena_id'];
		}
		$cabeceras = [
			['name'=>'Fecha','width'=>'md'],
			['name'=>'Reporte','width'=>'md'],
			['name'=>'Reporte','width'=>'md','visible'=>'false'],
			['name'=>'Obs.','width'=>'sm'],
			['name'=>'Obs.Obra','width'=>'md'],
			['name'=>'Camión','width'=>'lg'],
			['name'=>'KMs.','width'=>'sm'],
			['name'=>'KMs.GPS','width'=>'sm'],
			['name'=>'Hrs.','width'=>'sm'],
			['name'=>'Producción','width'=>'md'],
			['name'=>'Comb.Lts','width'=>'sm'],
			['name'=>'Repuestos($)','width'=>'md'],
			['name'=>'Remuneraciones($)','width'=>'md'],
			['name'=>'Hrs.Panne','width'=>'sm'],
			['name'=>'Panne','width'=>'sm'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable','dots'=>"sm"],
			['campo'=>'reporte','format'=> 'enlace', 'new-page'=>'true', 'url'=>"//rCamionPropio/view", 'params'=>['id']],
			['campo'=>'reporte','exportable','visible'=>'false'],
			['campo'=>'observaciones','exportable','dots'=>'md'],
			['campo'=>'observaciones_obra','exportable', 'dots'=>'md'],
			['campo'=>'camion','exportable', 'dots'=>'md'],
			['campo'=>'km_recorridos','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'km_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'horas','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'produccion','exportable','format'=>'money','acumulado'=>'suma'],
			['campo'=>'combustible','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'remuneraciones','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'horas_panne','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'panne','exportable'],
		];

		$model->propiosOArrendados = "CP";
		$reports = ExpedicionesCamion::model()->findAll($model->search());

		$datos = [];

		
		foreach($reports as $report){
			
			$produccion = 0;
			$combustible = 0;
			$repuestos = 0;
			$remuneraciones = 0;

			$continue = false;
			//producción
			//producción por volumen:
			
			$viajes = ViajeCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($viajes as $viaje){
				$produccion += $viaje->total;
				if($model->faena_id != null && $model->faena_id != ""){
					if($model->faena_id != $viaje->faena_id){
						$continue = true;
					}
				}
			}

			if($continue){
				continue;
			}
			
			//producción por tiempo:
			$expediciones = Expedicionportiempo::model()->findAllByAttributes(['rcamionpropio_id'=>$report['id']]);
			foreach($expediciones as $expedicion){
				$produccion += $expedicion->total;
				if($model->faena_id != null && $model->faena_id != ""){
					if($model->faena_id != $expedicion->faena_id){
						$continue = true;
					}
				}
			}

			if($continue){
				continue;
			}

			//combustible
			$cargas = CargaCombCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($cargas as $carga){
				$combustible += $carga->petroleoLts;
			}

			//repuestos
			$compras = CompraRepuestoCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($compras as $compra){
				$repuestos += $compra->montoNeto;
			}

			//remuneraciones
			$sueldos = RemuneracionCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($sueldos as $sueldo){
				$remuneraciones += $sueldo->montoNeto;
			}

			$dato['tipo'] = $report['tipo'];
			$dato['fecha'] = $report['fecha'];
			$dato['reporte'] = $report['reporte'];
			$dato['observaciones'] = $report['observaciones'];
			$dato['observaciones_obra'] = $report['observaciones_obra'];
			$dato['camion'] = $report['camion'];
			$dato['km_recorridos'] = $report['km_recorridos'];
			$dato['km_gps'] = $report['km_gps'];
			$dato['horas'] = $report['horas'];
			$dato['panne'] = $report['panne'];
			$dato['horas_panne'] = $report['horas_panne'];
			$dato['validado'] = $report['validado'];
			$dato['validador'] = $report['validador'];
			$dato['id'] = $report['id'];
			$dato['produccion'] = $produccion;
			$dato['combustible'] = $combustible;
			$dato['repuestos'] = $repuestos;
			$dato['remuneraciones'] = $remuneraciones;
			
			$datos[] = (object)$dato;	
		}

		$model->propiosOArrendados = "CA";
		$reports = ExpedicionesCamion::model()->findAll($model->search());
				
		foreach($reports as $report){
			
			$produccion = 0;
			$combustible = 0;
			$repuestos = 0;
			$remuneraciones = 0;

			$continue = false;
			//producción
			//producción por volumen:
			
			$viajes = ViajeCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($viajes as $viaje){
				$produccion += $viaje->total;
				if($model->faena_id != null && $model->faena_id != ""){
					if($model->faena_id != $viaje->faena_id){
						$continue = true;
					}
				}
			}

			if($continue){
				continue;
			}
			
			//producción por tiempo:
			$expediciones = Expedicionportiempoarr::model()->findAllByAttributes(['rcamionarrendado_id'=>$report['id']]);
			foreach($expediciones as $expedicion){
				$produccion += $expedicion->total;
				if($model->faena_id != null && $model->faena_id != ""){
					if($model->faena_id != $expedicion->faena_id){
						$continue = true;
					}
				}
			}

			if($continue){
				continue;
			}


			//combustible
			$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($cargas as $carga){
				$combustible += $carga->petroleoLts;
			}

			//repuestos
			$compras = CompraRepuestoCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($compras as $compra){
				$repuestos += $compra->montoNeto;
			}

			//remuneraciones
			$sueldos = RemuneracionCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($sueldos as $sueldo){
				$remuneraciones += $sueldo->montoNeto;
			}


			$dato['tipo'] = $report['tipo'];
			$dato['fecha'] = $report['fecha'];
			$dato['reporte'] = $report['reporte'];
			$dato['observaciones'] = $report['observaciones'];
			$dato['observaciones_obra'] = $report['observaciones_obra'];
			$dato['camion'] = $report['camion'];
			$dato['km_recorridos'] = $report['km_recorridos'];
			$dato['km_gps'] = $report['km_gps'];
			$dato['horas'] = $report['horas'];
			$dato['panne'] = $report['panne'];
			$dato['horas_panne'] = $report['horas_panne'];
			$dato['validado'] = $report['validado'];
			$dato['validador'] = $report['validador'];
			$dato['id'] = $report['id'];
			$dato['produccion'] = $produccion;
			$dato['combustible'] = $combustible;
			$dato['repuestos'] = $repuestos;
			$dato['remuneraciones'] = $remuneraciones;
			
			$datos[] = (object)$dato;	
		}

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	public function actionView($id, $tipo){
		if($tipo == "CP")
		{
			$this->redirect(CController::createUrl("//rCamionPropio/".$id));
		}
		if($tipo == "CA")
		{
			$this->redirect(CController::createUrl("//rCamionArrendado/".$id));
		}
	}
}
