<?php

class GastoRemuneracionesController extends Controller
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

		$model=new GastoRemuneraciones('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GastoRemuneraciones'])){
			$model->attributes=$_GET['GastoRemuneraciones'];
		}

	
		$cabeceras = [
			['name'=>'Máquina o camión','width'=>'lg'],
			['name'=>'Operador o Chofer','width'=>'lg'],
			['name'=>'Centro Gestión','width'=>'lg'],
			['name'=>'Gasto ($)','width'=>'md'],
			['name'=>'Ver','width'=>'xs'],
		];

		$extra_datos = [
			['campo'=>'maquina','exportable','dots'=>"md"],
			['campo'=>'operador','exportable','dots'=>'md'],
			['campo'=>'centro_gestion','exportable','dots'=>'md'],
			['campo'=>'total','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'id','format'=> 'enlace-ver', 'url'=>"//gastoRemuneraciones/view?fecha_inicio=$model->fecha_inicio&fecha_fin=$model->fecha_fin&propiosOArrendados=$model->propiosOArrendados", 'params'=>['maquina','operador','centro_gestion']],
		];

		$datos = GastoRemuneraciones::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	
	public function actionView($fecha_inicio, $fecha_fin, $propiosOArrendados, $maquina, $operador, $centro_gestion){
		$maquina = str_replace("___","\"",$maquina);
		$this->pageTitle = "Detalle de informe de gasto de remuneraciones";
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
		
		
		if($centro_gestion != ''){
			$criteria->addCondition('centro_gestion = :centro_gestion');
			$criteria->params[':centro_gestion'] = $centro_gestion;
		}
		
		if($operador != ''){
			$criteria->addCondition('operador = :operador');
			$criteria->params[':operador'] = $operador;
		}
		if($maquina != ''){
			$criteria->addCondition('maquina = :maquina');
			$criteria->params[':maquina'] = $maquina;
		}
		

		$cabeceras = [
			['name'=>'Fecha','width'=>'sm'],
			['name'=>'Reporte','width'=>'sm'],
			//['name'=>'Fuente','width'=>'sm'],
			//['name'=>'Operador','width'=>'md'],
			['name'=>'Máquina','width'=>'md'],
			['name'=>'Descripción','width'=>'lg'],
			//['name'=>'Guía','width'=>'sm'],
			['name'=>'Documento','width'=>'sm'],
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
			['campo'=>'reporte','format'=> 'enlace_rg', 'url'=>"//gastoRemuneraciones/redirect", 'params'=>['report_id','report_tipo']],
			//['campo'=>'fuente','exportable', 'dots'=>"sm"],
			//['campo'=>'operador','exportable', 'dots'=>"md"],
			['campo'=>'maquina','exportable', 'dots'=>"md"],
			['campo'=>'descripcion','exportable', 'dots'=>'lg',],
			//['campo'=>'guia','exportable', 'dots'=>"sm"],
			['campo'=>'documento','exportable', 'dots'=>"sm"],
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

		$gastos = GastoRemuneraciones::model()->findAll($criteria);
		$datos = [];
		foreach($gastos as $gasto){
			$partes = explode("-",$gasto->id);
			$report_id = (int)$partes[0];
			$remuneracion_id = (int)$partes[3];
			$tipo = $partes[1];
			$tipo_maquina = $partes[2];
			$detalleGastoRemuneraciones = new DetalleGastoRemuneraciones;
			$detalleGastoRemuneraciones->id = $report_id;
			$detalleGastoRemuneraciones->fecha = $gasto->fecha;
			$detalleGastoRemuneraciones->descripcion = "";
			$detalleGastoRemuneraciones->guia = "";
			$detalleGastoRemuneraciones->documento = "";
			$detalleGastoRemuneraciones->cantidad = "";
			$detalleGastoRemuneraciones->supervisor = "";
			$detalleGastoRemuneraciones->fuente = "";
			$detalleGastoRemuneraciones->numero = "";
			$detalleGastoRemuneraciones->nombre = "";
			$detalleGastoRemuneraciones->rut_rinde = "";
			$detalleGastoRemuneraciones->nombre_proveedor = "";
			

			$gastoCompleta = $gasto->gastoCompleta;
			
			
			if($tipo == "R"){
				$report = null;
				$remuneracion = null;
				if($tipo_maquina == "CP"){
					$report = RCamionPropio::model()->findByPk($report_id);
					$remuneracion = RemuneracionCamionPropio::model()->findByPk($remuneracion_id);
					$detalleGastoRemuneraciones->report_id = $report_id;
					$detalleGastoRemuneraciones->report_tipo = "CP";
				}
				if($tipo_maquina == "CA"){
					$report = RCamionArrendado::model()->findByPk($report_id);
					$remuneracion = RemuneracionCamionArrendado::model()->findByPk($remuneracion_id);
					$detalleGastoRemuneraciones->report_id = $report_id;
					$detalleGastoRemuneraciones->report_tipo = "CA";
				}
				if($tipo_maquina == "EP"){
					$report = REquipoPropio::model()->findByPk($report_id);
					$remuneracion = RemuneracionEquipoPropio::model()->findByPk($remuneracion_id);
					$detalleGastoRemuneraciones->report_id = $report_id;
					$detalleGastoRemuneraciones->report_tipo = "EP";
				}
				if($tipo_maquina == "EA"){
					$report = REquipoArrendado::model()->findByPk($report_id);
					$remuneracion = RemuneracionEquipoArrendado::model()->findByPk($remuneracion_id);
					$detalleGastoRemuneraciones->report_id = $report_id;
					$detalleGastoRemuneraciones->report_tipo = "EA";
				}
				if(isset($report)){
					$detalleGastoRemuneraciones->reporte = $report->reporte;
				}
				if(isset($remuneracion)){
					$detalleGastoRemuneraciones->guia = $remuneracion->guia;
					$detalleGastoRemuneraciones->documento = $remuneracion->documento;
					$detalleGastoRemuneraciones->descripcion = $remuneracion->descripcion;
					$detalleGastoRemuneraciones->nombre = $remuneracion->nombre;
					$detalleGastoRemuneraciones->rut_rinde = $remuneracion->rut_rinde;
					$detalleGastoRemuneraciones->nombre_proveedor = $remuneracion->nombre_proveedor;

				}
				$detalleGastoRemuneraciones->fuente = "SAM";
			}
			if(isset($gastoCompleta)){
				if(isset($gastoCompleta->gasto)){
					$informeGasto = InformeGasto::model()->findByPk($gastoCompleta->gasto->report_id);
					if(isset($informeGasto)){
						$detalleGastoRemuneraciones->numero = $informeGasto->numero;
						$detalleGastoRemuneraciones->folio = $informeGasto->numero;
						$detalleGastoRemuneraciones->gasto_id = $gastoCompleta->gasto->id;
					}
				}
			}
			$detalleGastoRemuneraciones->imagen = "";
			if(isset($gastoCompleta)){
				$detalleGastoRemuneraciones->imagen = $gastoCompleta->imagen;
			}
			$detalleGastoRemuneraciones->operador = $gasto->operador;
			$detalleGastoRemuneraciones->maquina = $gasto->maquina;
			$detalleGastoRemuneraciones->centro_gestion = $gasto->centro_gestion;
			$detalleGastoRemuneraciones->neto = $gasto->total;
			
			$datos[] = $detalleGastoRemuneraciones;
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
