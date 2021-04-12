<?php

class GastoRepuestoController extends Controller
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
				'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'exportar', 'export', 'redirect'),
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

		$model=new GastoRepuesto('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GastoRepuesto'])){
			$model->attributes=$_GET['GastoRepuesto'];
		}

	
		$cabeceras = [
			['name'=>'Máquina o camión','width'=>'lg'],
			['name'=>'Operador o Chofer','width'=>'lg'],
			['name'=>'Centro Gestión','width'=>'lg'],
			['name'=>'Consumo ($)','width'=>'md'],
			['name'=>'Ver','width'=>'xs'],
		];

		$extra_datos = [
			['campo'=>'maquina','exportable','dots'=>"md"],
			['campo'=>'operador','exportable','dots'=>'md'],
			['campo'=>'centro_gestion','exportable','dots'=>'md'],
			['campo'=>'total','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'id','format'=> 'enlace-ver', 'url'=>"//gastoRepuesto/view?fecha_inicio=$model->fecha_inicio&fecha_fin=$model->fecha_fin&propiosOArrendados=$model->propiosOArrendados", 'params'=>['maquina','operador','centro_gestion']],
		];

		$datos = GastoRepuesto::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	
	public function actionView($fecha_inicio, $fecha_fin, $propiosOArrendados, $maquina, $operador, $centro_gestion){
		
		
		$this->pageTitle = "Detalle de informe de gasto de combustibles";
		$criteria=new CDbCriteria();	

		if($fecha_inicio != "" && $fecha_fin == ""){
			$criteria->addCondition('fecha >= :fecha_inicio');
			$criteria->params[':fecha_inicio'] = $fecha_inicio;
		}
		if($fecha_inicio == "" && $fecha_fin != ""){
			$criteria->addCondition('fecha <= :fecha_fin');
			$criteria->params = [':fecha_fin'=>$fecha_fin];
		}
		if($fecha_inicio != "" && $fecha_fin != ""){
			$criteria->addCondition('fecha >= :fecha_inicio and fecha <= :fecha_fin');
			$criteria->params[':fecha_inicio'] = $fecha_inicio;
			$criteria->params[':fecha_fin'] = $fecha_fin;
		}

		if($propiosOArrendados == "CA" || $propiosOArrendados == "CP"){
			$criteria->addCondition('tipo_maquina = :tipo_maquina');
			$criteria->params[':tipo_maquina'] = $propiosOArrendados;
		}

		if($maquina != ''){
			$criteria->addCondition('maquina = :maquina');
			$criteria->params[':maquina'] = $maquina;
		}
		if($operador != ''){
			$criteria->addCondition('operador = :operador');
			$criteria->params[':operador'] = $operador;
		}
		if($centro_gestion != ''){
			$criteria->addCondition('centro_gestion = :centro_gestion');
			$criteria->params[':centro_gestion'] = $centro_gestion;
		}

		$cabeceras = [
			['name'=>'Fecha','width'=>'sm'],
			['name'=>'Reporte','width'=>'sm'],
			//['name'=>'Fuente','width'=>'sm'],
			//['name'=>'Operador','width'=>'md'],
			['name'=>'Máquina','width'=>'md'],
			['name'=>'Repuesto','width'=>'xl'],
			//['name'=>'Guía','width'=>'sm'],
			['name'=>'Factura','width'=>'sm'],
			//['name'=>'Cantidad','width'=>'sm'],
			['name'=>'Neto','width'=>'md'],
			['name'=>'Faena','width'=>'md'],
			//['name'=>'Supervisor','width'=>'md'],
			['name'=>'Proveedor','width'=>'md'],
			//['name'=>'RUT rendidor','width'=>'md'],
			['name'=>'Nombre','width'=>'md'],
			['name'=>'Número','width'=>'sm'],
			['name'=>'Ver','width'=>'sm', 'filtro'=>'false'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable', 'format'=>'date', 'dots'=>"sm"],
			['campo'=>'reporte','format'=> 'enlace_rg', 'url'=>"//gastoRepuesto/redirect", 'params'=>['report_id','report_tipo']],
			//['campo'=>'fuente','exportable', 'dots'=>"sm"],
			//['campo'=>'operador','exportable', 'dots'=>"md"],
			['campo'=>'maquina','exportable', 'dots'=>"md"],
			['campo'=>'repuesto','exportable', 'dots'=>'xl',],
			//['campo'=>'guia','exportable', 'dots'=>"sm"],
			['campo'=>'factura','exportable', 'dots'=>"sm"],
			//['campo'=>'cantidad','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'neto','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'centro_gestion','exportable', 'dots'=>"md"],
			//['campo'=>'supervisor','exportable', 'dots'=>"md"],
			['campo'=>'nombre_proveedor','exportable', 'dots'=>"md"],
			//['campo'=>'rut_rinde','exportable', 'dots'=>"md"],
			['campo'=>'nombre','exportable', 'dots'=>"md"],
			['campo'=>'numero','format'=> 'enlace_rg', 'url'=>"//informeGasto/view", 'params'=>['folio','gasto_id']],
			['campo'=>'imagen','format'=>'imagen-gasto'],
		];

		$gastos = GastoRepuesto::model()->findAll($criteria);
		$datos = [];
		foreach($gastos as $gasto){
			$partes = explode("-",$gasto->id);
			$id = (int)$partes[0];
			$tipo = $partes[1];
			$tipo_maquina = $partes[2];
			$detalleGastoRepuesto = new DetalleGastoRepuesto;
			$detalleGastoRepuesto->id = $id;
			$detalleGastoRepuesto->fecha = $gasto->fecha;
			$detalleGastoRepuesto->repuesto = "";
			$detalleGastoRepuesto->guia = "";
			$detalleGastoRepuesto->factura = "";
			$detalleGastoRepuesto->cantidad = "";
			$detalleGastoRepuesto->supervisor = "";
			$detalleGastoRepuesto->fuente = "";
			$detalleGastoRepuesto->numero = "";
			$detalleGastoRepuesto->nombre = "";
			$detalleGastoRepuesto->rut_rinde = "";
			$detalleGastoRepuesto->nombre_proveedor = "";

			$gastoCompleta = $gasto->gastoCompleta;
			
			if($tipo == "R"){
				$report = null;
				$compra = null;
				if($tipo_maquina == "CP"){
					$report = RCamionPropio::model()->findByPk($id);
					$compra = CompraRepuestoCamionPropio::model()->findByAttributes(['rCamionPropio_id'=>$id]);
					$detalleGastoRepuesto->report_id = $id;
					$detalleGastoRepuesto->report_tipo = "CP";
				}
				if($tipo_maquina == "CA"){
					$report = RCamionArrendado::model()->findByPk($id);
					$compra = CompraRepuestoCamionArrendado::model()->findByAttributes(['rCamionArrendado_id'=>$id]);
					$detalleGastoRepuesto->report_id = $id;
					$detalleGastoRepuesto->report_tipo = "CA";
				}
				if($tipo_maquina == "EP"){
					$report = REquipoPropio::model()->findByPk($id);
					$compra = CompraRepuestoEquipoPropio::model()->findByAttributes(['rEquipoPropio_id'=>$id]);
					$detalleGastoRepuesto->report_id = $id;
					$detalleGastoRepuesto->report_tipo = "EP";
				}
				if($tipo_maquina == "EA"){
					$report = REquipoArrendado::model()->findByPk($id);
					$compra = CompraRepuestoEquipoArrendado::model()->findByAttributes(['rEquipoArrendado_id'=>$id]);
					$detalleGastoRepuesto->report_id = $id;
					$detalleGastoRepuesto->report_tipo = "EA";
				}
				if(isset($report)){
					$detalleGastoRepuesto->reporte = $report->reporte;
				}
				if(isset($compra)){
					$detalleGastoRepuesto->guia = $compra->guia;
					$detalleGastoRepuesto->factura = $compra->factura;
					$detalleGastoRepuesto->repuesto = $compra->repuesto;
					$detalleGastoRepuesto->nombre = $compra->nombre;
					$detalleGastoRepuesto->rut_rinde = $compra->rut_rinde;
					$detalleGastoRepuesto->nombre_proveedor = $compra->nombre_proveedor;

				}
				$detalleGastoRepuesto->fuente = "SAM";
			}
			if(isset($gastoCompleta)){
				if(isset($gastoCompleta->gasto)){
					$informeGasto = InformeGasto::model()->findByPk($gastoCompleta->gasto->report_id);
					if(isset($informeGasto)){
						$detalleGastoRepuesto->numero = $informeGasto->numero;
						$detalleGastoRepuesto->folio = $informeGasto->numero;
						$detalleGastoRepuesto->gasto_id = $gastoCompleta->gasto->id;
					}
				}
			}
			$detalleGastoRepuesto->imagen = "";
			if(isset($gastoCompleta)){
				$detalleGastoRepuesto->imagen = $gastoCompleta->imagen;
			}
			$detalleGastoRepuesto->operador = $gasto->operador;
			$detalleGastoRepuesto->maquina = $gasto->maquina;
			$detalleGastoRepuesto->centro_gestion = $gasto->centro_gestion;
			$detalleGastoRepuesto->neto = $gasto->total;
			
			$datos[] = $detalleGastoRepuesto;
		}


		$this->render("view",array(
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
			'fecha_inicio' => $fecha_inicio,
			'fecha_fin' => $fecha_fin,
			'propiosOArrendados' => $propiosOArrendados,
			'maquina' => $maquina,
			'operador' => $operador,
			'centro_gestion' => $centro_gestion
		));
	}


	public function actionRedirect($report_id, $report_tipo){
		if($report_tipo == "CA"){
			$this->redirect(['rCamionArrendado/view','id'=>$report_id]);
		}
		if($report_tipo == "CP"){
			$this->redirect(['rCamionPropio/view','id'=>$report_id]);
		}
		if($report_tipo == "EA"){
			$this->redirect(['rEquipoArrendado/view','id'=>$report_id]);
		}
		if($report_tipo == "EP"){
			$this->redirect(['rEquipoPropio/view','id'=>$report_id]);
		}
	}
}
