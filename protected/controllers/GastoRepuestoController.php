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
			['name'=>'Fuente','width'=>'sm'],
			['name'=>'Operador','width'=>'md'],
			['name'=>'Máquina','width'=>'md'],
			['name'=>'Repuesto','width'=>'md'],
			['name'=>'Guía','width'=>'sm'],
			['name'=>'Factura','width'=>'sm'],
			['name'=>'Cantidad','width'=>'sm'],
			['name'=>'Neto','width'=>'md'],
			['name'=>'Faena','width'=>'md'],
			['name'=>'Supervisor','width'=>'md'],
			['name'=>'Proveedor','width'=>'md'],
			['name'=>'RUT rendidor','width'=>'md'],
			['name'=>'Nombre','width'=>'md'],
			['name'=>'Número','width'=>'sm'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable', 'format'=>'date', 'dots'=>"sm"],
			['campo'=>'reporte','format'=> 'enlace_rg', 'url'=>"//gastoRepuesto/redirect", 'params'=>['id','reporte','fuente']],
			['campo'=>'fuente','exportable', 'dots'=>"sm"],
			['campo'=>'operador','exportable', 'dots'=>"md"],
			['campo'=>'maquina','exportable', 'dots'=>"md"],
			['campo'=>'repuesto','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'guia','exportable', 'dots'=>"sm"],
			['campo'=>'factura','exportable', 'dots'=>"sm"],
			['campo'=>'cantidad','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'neto','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'centro_gestion','exportable', 'dots'=>"md"],
			['campo'=>'supervisor','exportable', 'dots'=>"md"],
			['campo'=>'nombre_proveedor','exportable', 'dots'=>"md"],
			['campo'=>'rut_rinde','exportable', 'dots'=>"md"],
			['campo'=>'nombre','exportable', 'dots'=>"md"],
			['campo'=>'numero','exportable', 'dots'=>"sm"],
		];

		$gastos = GastoRepuesto::model()->findAll($criteria);
		$datos = [];
		foreach($gastos as $gasto){
			$partes = explode("-",$gasto->id);
			$id = (int)$partes[0];
			$tipo = $partes[1];
			$tipo_maquina = $partes[2];
			$detalleGastoRepuesto = new DetalleGastoRepuesto;
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
			
			if($tipo == "R"){
				$report = null;
				$compra = null;
				if($tipo_maquina == "CP"){
					$report = RCamionPropio::model()->findByPk($id);
					$carga = CompraRepuestoCamionPropio::model()->findByAttributes(['rCamionPropio_id'=>$id]);
				}
				if($tipo_maquina == "CA"){
					$report = RCamionArrendado::model()->findByPk($id);
					$compra = CompraRepuestoCamionArrendado::model()->findByAttributes(['rCamionArrendado_id'=>$id]);
				}
				if($tipo_maquina == "EP"){
					$report = REquipoPropio::model()->findByPk($id);
					$compra = CompraRepuestoEquipoPropio::model()->findByAttributes(['rEquipoPropio_id'=>$id]);
				}
				if($tipo_maquina == "EA"){
					$report = REquipoArrendado::model()->findByPk($id);
					$compra = CompraRepuestoEquipoArrendado::model()->findByAttributes(['rEquipoArrendado_id'=>$id]);
				}
				if(isset($report)){
					$detalleGastoRepuesto->reporte = $report->reporte;
				}
				if(isset($compra)){
					$detalleGastoRepuesto->guia = $compra->guia;
					$detalleGastoRepuesto->factura = $compra->factura;
					$detalleGastoRepuesto->repuesto = $compra->repuesto;
					$detalleGastoRepuesto->nombre = $compra->nombre;
					$detalleGastoRepuesto->numero = $compra->numero;
					$detalleGastoRepuesto->rut_rinde = $compra->rut_rinde;
					$detalleGastoRepuesto->nombre_proveedor = $compra->nombre_proveedor;
				}
				$detalleGastoRepuesto->fuente = "SAM";
			}
			if($tipo == "RG"){
				$gastoCompleta = $gasto->gastoCompleta;
				if(isset($gastoCompleta)){
					if(isset($gastoCompleta->gasto)){
						$informeGasto = InformeGasto::model()->findByPk($gastoCompleta->gasto->report_id);
						if(isset($informeGasto)){
							$detalleGastoRepuesto->reporte = $informeGasto->numero;
						}
						$detalleGastoRepuesto->id = $gastoCompleta->gasto->id;
					}
				}
				$detalleGastoRepuesto->fuente = "RindeGastos";
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


	public function actionRedirect($id, $reporte, $fuente){
		if($fuente == "SAM"){
			$this->redirect(['','id'=>$model->id]);
		}
		if($fuente == "RindeGastos"){
			$this->redirect(['informeGasto/view','folio'=>$reporte,'gasto_id'=>$id]);
		}
	}

}
