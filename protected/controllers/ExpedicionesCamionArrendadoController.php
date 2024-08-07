<?php

class ExpedicionesCamionArrendadoController extends Controller
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

		$model=new ExpedicionesCamionArrendado('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpedicionesCamionArrendado'])){
			$model->attributes=$_GET['ExpedicionesCamionArrendado'];
			$model->faena_id = $_GET['ExpedicionesCamionArrendado']['faena_id'];
		}

		$cabeceras = [
			['name'=>'Fecha','width'=>'md'],
			['name'=>'Reporte','width'=>'md'],
			['name'=>'Reporte','width'=>'md','visible'=>'false'],
			['name'=>'Obs.','width'=>'sm'],
			['name'=>'Obs.Obra','width'=>'md'],
			['name'=>'Camión','width'=>'lg'],
			/* ['name'=>'KMs.','width'=>'sm'],
			['name'=>'KMs.GPS','width'=>'sm'],
			['name'=>'Hrs.','width'=>'sm'],
			['name'=>'Producción','width'=>'md'],
			['name'=>'Comb.Lts','width'=>'sm'],
			['name'=>'Repuestos($)','width'=>'md'],
			['name'=>'Remuneraciones($)','width'=>'md'],
			['name'=>'Hrs.Panne','width'=>'sm'],
			['name'=>'Panne','width'=>'sm'], */
			['name'=>'Validar', 'filtro'=>'validacion', 'width'=>'xs'],
			['name'=>'Validado por','width'=>'md'],
			['name'=>'Adjuntos', 'filtro'=>'checkbox'],
			['name'=>'Modificaciones', 'filtro'=>'false'],
		];

		if ($model->chkKms == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Kms.', 'width' => 'sm']]);
		if ($model->chkKmsGPS == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Kms.GPS', 'width' => 'sm']]);
		if ($model->chkHrs == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Hrs.', 'width' => 'sm']]);
		if ($model->chkHrsMin == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Hrs.Mín.', 'width' => 'sm']]);
		if ($model->chkProduccion == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Producción', 'width' => 'sm']]);
		if ($model->chkProduccionMinima == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Producción Mínima', 'width' => 'sm']]);
		if ($model->chkCombLts == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Comb.Lts', 'width' => 'sm']]);
		if ($model->chkRepuestos == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Repuestos($)', 'width' => 'sm']]);
		if ($model->chkRemuneraciones == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Remuneraciones($)', 'width' => 'sm']]);
		if ($model->chkHrsPanne == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Hrs.Panne', 'width' => 'sm']]);
		if ($model->chkPanne == 1) array_splice($cabeceras, count($cabeceras) - 4, 0, [['name' => 'Panne', 'width' => 'sm']]);

		$extra_datos = [
			['campo'=>'fecha','exportable','dots'=>"sm"],
			['campo'=>'reporte','format'=> 'enlace', 'new-page'=>'true', 'url'=>"//rCamionArrendado/view", 'params'=>['id']],
			['campo'=>'reporte','exportable','visible'=>'false'],
			['campo'=>'observaciones','exportable','dots'=>'md'],
			['campo'=>'observaciones_obra','exportable', 'dots'=>'md'],
			['campo'=>'camion','exportable', 'dots'=>'md'],
			/* ['campo'=>'km_recorridos','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'km_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'horas','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'produccion','exportable','format'=>'money','acumulado'=>'suma'],
			['campo'=>'combustible','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'remuneraciones','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'horas_panne','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'panne','exportable'], */
			['campo'=>'validado','format'=>'validado','params'=>['id'],'ordenable'=>'false'],
			['campo'=>'validador'],
			['campo'=>'id','format'=> 'enlace-documento', 'new-page'=>'true', 'url'=>"//admin/preview", 'params'=>['id','tipo'],'ordenable'=>'false'],
			['campo'=>'id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//rCamionArrendado/verHistorial", 'params'=>['id'],'ordenable'=>'false'],
		];

		if ($model->chkKms == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'km_recorridos', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkKmsGPS == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'km_gps', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkHrs == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'horas', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkHrsMin == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'horas_min', 'exportable', 'format' => 'decimal1', 'acumulado' => 'suma']]);
		if ($model->chkProduccion == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'produccion', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkProduccionMinima == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'produccion_min', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkCombLts == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'combustible', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkRepuestos == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'repuestos', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkRemuneraciones == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'remuneraciones', 'exportable', 'format' => 'number', 'acumulado' => 'suma']]);
		if ($model->chkHrsPanne == 1) array_splice($extra_datos, count($extra_datos) - 4, 0, [['campo' => 'horas_panne', 'exportable', 'format' => 'decimal1', 'acumulado' => 'suma']]);
		if ($model->chkPanne == 1) array_splice($extra_datos, count($extra_datos) - 4, 0,  [['campo' => 'panne', 'exportable']]);

		$reports = ExpedicionesCamionArrendado::model()->findAll($model->search());

		$datos = [];
		foreach($reports as $report){
			
			$produccion = 0;
			$produccion_min = 0;
			$horas_min = 0;
			$combustible = 0;
			$repuestos = 0;
			$remuneraciones = 0;

			if($model->camion_id != null && $model->camion_id != ""){
				if($model->camion_id != $report['camion_id']){
					continue;
				}
			}
			if($model->chofer_id != null && $model->chofer_id != ""){
				if($model->chofer_id != $report['chofer_id']){
					continue;
				}
			}
			
			//producción
			//producción por volumen:
			
			$viajes = ViajeCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($viajes as $viaje){
				$produccion += $viaje->total;		
			}
			
			//producción por tiempo:
			$expediciones = Expedicionportiempoarr::model()->findAllByAttributes(['rcamionarrendado_id'=>$report['id']]);
			foreach($expediciones as $expedicion){
				$produccion += $expedicion->total;
				$horas_min += $expedicion->unidadfaena->produccion_minima;
				if ($expedicion->rcamionarrendado->panne == 1) {
					$horasPanne = $expedicion->rcamionarrendado->minPanne / 60;
					$horas_min = $expedicion->unidadfaena->produccion_minima - $horasPanne;
					$horas_min = $horas_min < 0 ? 0 : $horas_min;
					$horasReales = $horas_min;
					$produccion_min = $horasReales < 0 ? 0 : $horasReales * $expedicion->unidadfaena->pu;
				}
			}
			//combustible
			$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($cargas as $carga){
				$combustible += $carga->petroleoLts;
			}
			
			
			//repuesto
			$compras = CompraRepuestoCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($compras as $compra){
				$repuestos += $compra->montoNeto;
			}

			//remuneraciones
			/* $sueldos = RemuneracionCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			foreach($sueldos as $sueldo){
				$remuneraciones += $sueldo->montoNeto;
			} */

			$dato['tipo'] = $report['tipo'];
			$dato['fecha'] = $report['fecha'];
			$dato['reporte'] = $report['reporte'];
			$dato['observaciones'] = $report['observaciones'];
			$dato['observaciones_obra'] = $report['observaciones_obra'];
			$dato['camion'] = $report['camion'];
			$dato['km_recorridos'] = $report['km_recorridos'];
			$dato['km_gps'] = $report['km_gps'];
			$dato['horas'] = $report['horas'];
			$dato['horas_min'] = $horas_min;
			$dato['panne'] = $report['panne'];
			$floatValue = floatval($report['horas_panne']);
			$dato['horas_panne'] = number_format($floatValue, 1);
			$dato['validado'] = $report['validado'];
			$dato['validador'] = $report['validador'];
			$dato['id'] = $report['id'];
			$dato['produccion'] = $produccion;
			$dato['produccion_min'] = $produccion_min;
			$dato['combustible'] = $combustible;
			$dato['repuestos'] = $repuestos;
			$dato['remuneraciones'] = 0;
			$datos[] = (object)$dato;
		}
		
		// REMUNERACIONES SAM
		$criteria = new CDbCriteria();
		if ($model->fecha_inicio != "" && $model->fecha_fin == "") {
			$criteria->addCondition('fecha_rendicion >= :fecha_inicio');
			$criteria->params[':fecha_inicio'] = $model->fecha_inicio;
		}
		if ($model->fecha_inicio == "" && $model->fecha_fin != "") {
			$criteria->addCondition('fecha_rendicion <= :fecha_fin');
			$criteria->params = [':fecha_fin' => $model->fecha_fin];
		}
		if ($model->fecha_inicio != "" && $model->fecha_fin != "") {
			$criteria->addCondition('fecha_rendicion >= :fecha_inicio and fecha_rendicion <= :fecha_fin');
			$criteria->params[':fecha_inicio'] = $model->fecha_inicio;
			$criteria->params[':fecha_fin'] = $model->fecha_fin;
		}
		$criteria->compare('camionArrendado_id',$model->camion_id);
		$criteria->addCondition("tipo_equipo_camion = :ca");
		$criteria->params[":ca"] = "CA";
		//$criteria->group = "";
		//$criteria->select = "SUM(neto)";
		$remuneraciones = RemuneracionesSam::model()->with('camionArrendado')->findAll($criteria);

		foreach ($remuneraciones as $r) {
			$dato['tipo'] = "";
			$dato['fecha'] = $r["fecha_rendicion"];
			$dato['reporte'] = "";
			$dato['observaciones'] = $r["descripcion"];
			$dato['observaciones_obra'] = "";
			$dato['camion'] = $r["camionArrendado"]["nombre"];
			$dato['camion_codigo'] = $r["camionArrendado"]["codigo"];
			$dato['km_recorridos'] = 0;
			$dato['km_gps'] = 0;
			$dato['horas'] = 0;
			$dato['horas_min'] = 0;
			$dato['panne'] = "No";
			$dato['horas_panne'] = 0;
			$dato['validado'] = "";
			$dato['validador'] = "";
			$dato['id'] = "";
			$dato['produccion'] = "";
			$dato['produccion_min'] = "";
			$dato['combustible'] = 0;
			$dato['repuestos'] = 0;
			$dato['remuneraciones'] = $r["neto"];

			$datos[] = (object)$dato;
		}

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	public function actionView($id){
		$this->redirect(CController::createUrl("//rCamionArrendado/".$id));
	}
}
