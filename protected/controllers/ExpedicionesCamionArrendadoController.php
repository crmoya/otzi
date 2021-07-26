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
			['name'=>'Hrs.Panne','width'=>'sm'],
			['name'=>'Panne','width'=>'sm'],
			['name'=>'Validar', 'filtro'=>'validacion', 'width'=>'xs'],
			['name'=>'Validado por','width'=>'md'],
			['name'=>'Adjuntos', 'filtro'=>'checkbox'],
			['name'=>'Modificaciones', 'filtro'=>'false'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable','dots'=>"sm"],
			['campo'=>'reporte','format'=> 'enlace', 'new-page'=>'true', 'url'=>"//rCamionArrendado/view", 'params'=>['id']],
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
			['campo'=>'horas_panne','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'panne','exportable'],
			['campo'=>'validado','format'=>'validado','params'=>['id'],'ordenable'=>'false'],
			['campo'=>'validador'],
			['campo'=>'id','format'=> 'enlace-documento', 'new-page'=>'true', 'url'=>"//admin/preview", 'params'=>['id','tipo'],'ordenable'=>'false'],
			['campo'=>'id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//rCamionArrendado/verHistorial", 'params'=>['id'],'ordenable'=>'false'],
		];

		$reports = ExpedicionesCamionArrendado::model()->findAll($model->search());

		$datos = [];
		foreach($reports as $report){
			
			$produccion = 0;
			$combustible = 0;
			$repuestos = 0;

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
