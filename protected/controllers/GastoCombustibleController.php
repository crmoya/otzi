<?php

class GastoCombustibleController extends Controller
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

		$model=new GastoCombustible('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GastoCombustible'])){
			$model->attributes=$_GET['GastoCombustible'];
		}

	
		$cabeceras = [
			['name'=>'Máquina o camión','width'=>'lg'],
			['name'=>'Operador o Chofer','width'=>'lg'],
			['name'=>'Centro Gestión','width'=>'lg'],
			['name'=>'Consumo (Lts)','width'=>'md'],
			['name'=>'Consumo ($)','width'=>'md'],
			['name'=>'Ver','width'=>'xs'],
		];

		$extra_datos = [
			['campo'=>'maquina','exportable','dots'=>"md"],
			['campo'=>'operador','exportable','dots'=>'md'],
			['campo'=>'centro_gestion','exportable','dots'=>'md'],
			['campo'=>'litros','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'total','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'id','format'=> 'enlace-ver', 'url'=>"//gastoCombustible/view?fecha_inicio=$model->fecha_inicio&fecha_fin=$model->fecha_fin&propiosOArrendados=$model->propiosOArrendados&tipoCombustible_id=$model->tipoCombustible_id", 'params'=>['maquina','operador','centro_gestion']],
		];

		$datos = GastoCombustible::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	public function actionView($fecha_inicio, $fecha_fin, $propiosOArrendados, $tipoCombustible_id, $maquina, $operador, $centro_gestion){
		
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

		if(isset($tipoCombustible_id) && $tipoCombustible_id != ""){
			$criteria->addCondition('(tipo_combustible = :combustible or tipo_combustible = -1)');
			$criteria->params[':combustible'] = $tipoCombustible_id;
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
			['name'=>'Consumo (Lts)','width'=>'md'],
			['name'=>'Carguío','width'=>'md'],
			['name'=>'Guía','width'=>'sm'],
			['name'=>'Factura','width'=>'sm'],
			['name'=>'Precio Unitario','width'=>'md'],
			['name'=>'Valor Total','width'=>'md'],
			['name'=>'Faena','width'=>'md'],
			['name'=>'Tipo Combustible','width'=>'md'],
			['name'=>'Supervisor Combustible','width'=>'md'],
			['name'=>'Número','width'=>'sm'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable', 'format'=>'date', 'dots'=>"sm"],
			['campo'=>'reporte','format'=> 'enlace_rg', 'url'=>"//gastoCombustible/redirect", 'params'=>['id','reporte','fuente']],
			['campo'=>'fuente','exportable', 'dots'=>"sm"],
			['campo'=>'operador','exportable', 'dots'=>"md"],
			['campo'=>'maquina','exportable', 'dots'=>"md"],
			['campo'=>'litros','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'carguio','exportable', 'format'=>'number','dots'=>"md",'acumulado'=>'suma'],
			['campo'=>'guia','exportable', 'dots'=>"sm"],
			['campo'=>'factura','exportable', 'dots'=>"sm"],
			['campo'=>'precio_unitario','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'total','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'centro_gestion','exportable', 'dots'=>"md"],
			['campo'=>'tipo_combustible','exportable', 'dots'=>"md"],
			['campo'=>'supervisor_combustible','exportable', 'dots'=>"md"],
			['campo'=>'numero','exportable', 'dots'=>"sm"],
		];

		$gastos = GastoCombustible::model()->findAll($criteria);
		$datos = [];
		
		foreach($gastos as $gasto){
			$partes = explode("-",$gasto->id);
			$id = (int)$partes[0];
			$tipo = $partes[1];
			$tipo_maquina = $partes[2];
			$detalleGastoCombustible = new DetalleGastoCombustible;
			$detalleGastoCombustible->reporte = "";
			$detalleGastoCombustible->carguio = "";
			$detalleGastoCombustible->guia = "";
			$detalleGastoCombustible->factura = "";
			$detalleGastoCombustible->precio_unitario = "";
			$detalleGastoCombustible->supervisor_combustible = "";
			$detalleGastoCombustible->numero = "";
			$detalleGastoCombustible->tipo_combustible = "";

			if($tipo == "R"){
				$report = null;
				$carga = null;
				if($tipo_maquina == "CP"){
					$report = RCamionPropio::model()->findByPk($id);
					$carga = CargaCombCamionPropio::model()->findByAttributes(['rCamionPropio_id'=>$id]);
				}
				if($tipo_maquina == "CA"){
					$report = RCamionArrendado::model()->findByPk($id);
					$carga = CargaCombCamionArrendado::model()->findByAttributes(['rCamionArrendado_id'=>$id]);
				}
				if($tipo_maquina == "EP"){
					$report = REquipoPropio::model()->findByPk($id);
					$carga = CargaCombEquipoPropio::model()->findByAttributes(['rEquipoPropio_id'=>$id]);
				}
				if($tipo_maquina == "EA"){
					$report = REquipoArrendado::model()->findByPk($id);
					$carga = CargaCombEquipoArrendado::model()->findByAttributes(['rEquipoArrendado_id'=>$id]);
				}
				if(isset($report)){
					$detalleGastoCombustible->reporte = $report->reporte;
				}
				if(isset($carga)){
					if($tipo_maquina == "CP" || $tipo_maquina == "CA"){
						$detalleGastoCombustible->carguio = $carga->kmCarguio;
					}
					if($tipo_maquina == "EP" || $tipo_maquina == "EA"){
						$detalleGastoCombustible->carguio = $carga->hCarguio;
					}
					
					$detalleGastoCombustible->guia = $carga->guia;
					$detalleGastoCombustible->factura = $carga->factura;
					$detalleGastoCombustible->precio_unitario = $carga->precioUnitario;
					if(isset($carga->supervisor)){
						$detalleGastoCombustible->supervisor_combustible = $carga->supervisor->rut . " / " . $carga->supervisor->nombre;
					}
					$detalleGastoCombustible->numero = $carga->numero;
				}
				$detalleGastoCombustible->fuente = "SAM";
			}
			
			if($tipo == "RG"){
				$gastoCompleta = $gasto->gastoCompleta;
				if(isset($gastoCompleta)){
					if(isset($gastoCompleta->gasto)){
						$informeGasto = InformeGasto::model()->findByPk($gastoCompleta->gasto->report_id);						
						if(isset($informeGasto)){
							$detalleGastoCombustible->reporte = $informeGasto->numero;
						}
						$detalleGastoCombustible->id = $gastoCompleta->gasto->id;
					}
				}
				$detalleGastoCombustible->fuente = "RindeGastos";
			}
			$detalleGastoCombustible->fecha = $gasto->fecha;
			$detalleGastoCombustible->operador = $gasto->operador;
			$detalleGastoCombustible->maquina = $gasto->maquina;
			$detalleGastoCombustible->litros = $gasto->litros;
			$detalleGastoCombustible->total = $gasto->total;
			$detalleGastoCombustible->centro_gestion = $gasto->centro_gestion;
			$tipoCombustible = TipoCombustible::model()->findByPk($gasto->tipo_combustible);
			if(isset($tipoCombustible)){
				$detalleGastoCombustible->tipo_combustible = $tipoCombustible->nombre;
			}
			$datos[] = $detalleGastoCombustible;
			
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
			'centro_gestion' => $centro_gestion,
			'tipoCombustible_id' => $tipoCombustible_id,
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
