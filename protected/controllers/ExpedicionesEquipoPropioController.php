<?php

class ExpedicionesEquipoPropioController extends Controller {

	/**
	 * @return array action filters
	 */
	public function filters() {
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
	public function accessRules() {
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
	public function actionAdmin() {

		$this->pageTitle = "";

		$model = new ExpedicionesEquipoPropio('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['ExpedicionesEquipoPropio'])) {
			$model->attributes = $_GET['ExpedicionesEquipoPropio'];
		}

		$cabeceras = [
			['name' => 'Fecha', 'width' => 'md'],
			['name' => 'Reporte', 'width' => 'md'],
			['name' => 'Reporte', 'width' => 'md', 'visible' => 'false'],
			['name' => 'Obs.', 'width' => 'sm'],
			['name' => 'Obs.Obra', 'width' => 'md'],
			['name' => 'Equipo', 'width' => 'lg'],
			['name' => 'Hrs.Reales', 'width' => 'sm'],
			['name' => 'Hrs.GPS', 'width' => 'sm'],
			['name' => 'Producción', 'width' => 'md'],
			['name' => 'Comb.Lts', 'width' => 'sm'],
			['name' => 'Repuestos($)', 'width' => 'sm'],
			['name' => 'Remuneraciones($)', 'width' => 'sm'],
			['name' => 'Hrs.Panne', 'width' => 'sm'],
			['name' => 'Panne', 'width' => 'sm'],
			['name' => 'Validar', 'filtro' => 'validacion', 'width' => 'xs'],
			['name' => 'Validado por', 'width' => 'md'],
			['name' => 'Adjuntos', 'filtro' => 'checkbox'],
			['name' => 'Modificaciones', 'filtro' => 'false'],
		];

		$extra_datos = [
			['campo' => 'fecha', 'exportable', 'dots' => "sm"],
			['campo' => 'reporte', 'format' => 'enlace', 'new-page' => 'true', 'url' => "//rEquipoPropio/view", 'params' => ['id']],
			['campo' => 'reporte', 'exportable', 'visible' => 'false'],
			['campo' => 'observaciones', 'exportable', 'dots' => 'md'],
			['campo' => 'observaciones_obra', 'exportable', 'dots' => 'md'],
			['campo' => 'equipo', 'exportable', 'dots' => 'md'],
			['campo' => 'horas_reales', 'exportable', 'format' => 'number', 'acumulado' => 'suma'],
			['campo' => 'horas_gps', 'exportable', 'format' => 'number', 'acumulado' => 'suma'],
			['campo' => 'produccion', 'exportable', 'format' => 'money', 'acumulado' => 'suma'],
			['campo' => 'combustible', 'exportable', 'format' => 'number', 'acumulado' => 'suma'],
			['campo' => 'repuestos', 'exportable', 'format' => 'money', 'acumulado' => 'suma'],
			['campo' => 'remuneraciones', 'exportable', 'format' => 'money', 'acumulado' => 'suma'],
			['campo' => 'horas_panne', 'exportable', 'format' => 'number', 'acumulado' => 'suma'],
			['campo' => 'panne', 'exportable'],
			['campo' => 'validado', 'format' => 'validado', 'params' => ['id'], 'ordenable' => 'false'],
			['campo' => 'validador'],
			['campo' => 'id', 'format' => 'enlace-documento', 'new-page' => 'true', 'url' => "//admin/preview", 'params' => ['id', 'tipo'], 'ordenable' => 'false'],
			['campo' => 'id', 'format' => 'enlace-imagen', 'new-page' => 'true', 'url' => "//rEquipoPropio/verHistorial", 'params' => ['id'], 'ordenable' => 'false'],
		];

		$reports = ExpedicionesEquipoPropio::model()->findAll($model->search());

		$datos = [];
		foreach ($reports as $report) {

			if ($model->equipo_id != null && $model->equipo_id != "") {
				if ($model->equipo_id != $report['equipo_id']) {
					continue;
				}
			}
			if ($model->operador_id != null && $model->operador_id != "") {
				if ($model->operador_id != $report['operador_id']) {
					continue;
				}
			}

			$produccion = 0;
			$combustible = 0;
			$repuestos = 0;
			$remuneraciones = 0;

			$continue = false;
			//producción	
			$expediciones = Expedicionportiempoeq::model()->findAllByAttributes(['requipopropio_id' => $report['id']]);
			foreach ($expediciones as $expedicion) {
				$produccion += $expedicion->total;
				if ($model->faena_id != null && $model->faena_id != "") {
					if ($model->faena_id != $expedicion->faena_id) {
						$continue = true;
					}
				}
			}
			if ($continue) {
				continue;
			}

			//combustible
			$cargas = CargaCombEquipoPropio::model()->findAllByAttributes(['rEquipoPropio_id' => $report['id']]);
			foreach ($cargas as $carga) {
				$combustible += $carga->petroleoLts;
			}

			//repuesto
			$compras = CompraRepuestoEquipoPropio::model()->findAllByAttributes(['rEquipoPropio_id' => $report['id']]);
			foreach ($compras as $compra) {
				$repuestos += $compra->montoNeto;
			}

			//remuneraciones
			$sueldos = RemuneracionEquipoPropio::model()->findAllByAttributes(['rEquipoPropio_id' => $report['id']]);
			foreach ($sueldos as $sueldo) {
				$remuneraciones += $sueldo->montoNeto;
			}

			$dato['tipo'] = $report['tipo'];
			$dato['fecha'] = $report['fecha'];
			$dato['reporte'] = $report['reporte'];
			$dato['observaciones'] = $report['observaciones'];
			$dato['observaciones_obra'] = $report['observaciones_obra'];
			$dato['equipo'] = $report['equipo'];
			$dato['equipo_codigo'] = $report['equipo_codigo'];
			$dato['horas_reales'] = $report['horas_reales'];
			$dato['horas_gps'] = $report['horas_gps'];
			$dato['panne'] = $report['panne'];
			$dato['horas_panne'] = $report['horas_panne'];
			$dato['validado'] = $report['validado'];
			$dato['validador'] = $report['validador'];
			$dato['id'] = $report['id'];
			$dato['produccion'] = $produccion;
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
		$criteria->compare('equipoArrendado_id',$model->equipo_id);
		$criteria->addCondition("tipo_equipo_camion = :ep");
		$criteria->params[":ep"] = "EP";
		//$criteria->group = "";
		//$criteria->select = "SUM(neto)";
		$remuneraciones = RemuneracionesSam::model()->with('equipoPropio')->findAll($criteria);

		if (count($remuneraciones) > 0) {
			foreach ($remuneraciones as $r) {
				$dato['tipo'] = "";
				$dato['fecha'] = $r["fecha_rendicion"];
				$dato['reporte'] = "";
				$dato['observaciones'] = $r["descripcion"];
				$dato['observaciones_obra'] = "";
				$dato['equipo'] = $r["equipoPropio"]["nombre"];
				$dato['equipo_codigo'] = $r["equipoPropio"]["codigo"];
				$dato['horas_reales'] = 0;
				$dato['horas_gps'] = 0;
				$dato['panne'] = "No";
				$dato['horas_panne'] = 0;
				$dato['validado'] = "";
				$dato['validador'] = "";
				$dato['id'] = "";
				$dato['produccion'] = "";
				$dato['combustible'] = 0;
				$dato['repuestos'] = 0;
				$dato['remuneraciones'] = $r["neto"];

				$datos[] = (object)$dato;
			}
		}

		$this->render("admin", array(
			'model' => $model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	public function actionView($id) {
		$this->redirect(CController::createUrl("//rEquipoPropio/" . $id));
	}
}
