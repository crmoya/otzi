<?php

class ExpedicionesCamionPropioController extends Controller
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

		$model=new ExpedicionesCamionPropio('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpedicionesCamionPropio'])){
			$model->attributes=$_GET['ExpedicionesCamionPropio'];
		}

		$cabeceras = [
			['name'=>'Fecha','width'=>'md'],
			['name'=>'Reporte','width'=>'md'],
			['name'=>'Reporte','width'=>'md','visible'=>'false'],
			['name'=>'Obs.','width'=>'sm'],
			['name'=>'Obs.Obra','width'=>'md'],
			['name'=>'Camión','width'=>'lg'],
			['name'=>'Código','width'=>'sm'],
			['name'=>'Faena','width'=>'lg'],
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
			['campo'=>'reporte','format'=> 'enlace', 'new-page'=>'true', 'url'=>"//rCamionPropio/view", 'params'=>['id']],
			['campo'=>'reporte','exportable','visible'=>'false'],
			['campo'=>'observaciones','exportable','dots'=>'md'],
			['campo'=>'observaciones_obra','exportable', 'dots'=>'md'],
			['campo'=>'camion','exportable', 'dots'=>'md'],
			['campo'=>'camion_codigo','exportable', 'dots'=>'sm'],
			['campo'=>'faena','exportable', 'dots'=>'md'],
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
			['campo'=>'id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//rCamionPropio/verHistorial", 'params'=>['id'],'ordenable'=>'false'],
		];

		$reports = ExpedicionesCamionPropio::model()->findAll($model->search());

		$datos = [];
		foreach($reports as $report){
			
			$faenas = [];
			
			$producciones = [];
			$combustibles = [];
			$repuestos = [];

			//producción
			//producción por volumen:
			
			$viajes = ViajeCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($viajes as $viaje){
				if(array_key_exists($viaje->faena_id,$producciones)){
					$producciones[$viaje->faena_id] += $viaje->total;	
				}
				else{
					$producciones[$viaje->faena_id] = $viaje->total;	
				}
				if(!in_array($viaje->faena_id,$faenas)){
					$faenas[] = $viaje->faena_id;
				}		
			}
			
			//producción por tiempo:
			$expediciones = Expedicionportiempo::model()->findAllByAttributes(['rcamionpropio_id'=>$report['id']]);
			foreach($expediciones as $expedicion){
				if(array_key_exists($expedicion->faena_id,$producciones)){
					$producciones[$expedicion->faena_id] += $expedicion->total;
				}
				else{
					$producciones[$expedicion->faena_id] = $expedicion->total;
				}
				if(!in_array($expedicion->faena_id,$faenas)){
					$faenas[] = $expedicion->faena_id;
				}
			}

			//combustible
			$cargas = CargaCombCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($cargas as $carga){
				if(array_key_exists($carga->faena_id,$combustibles)){
					$combustibles[$carga->faena_id] += $carga->petroleoLts;
				}
				else{
					$combustibles[$carga->faena_id] = $carga->petroleoLts;
				}
				if(!in_array($carga->faena_id,$faenas)){
					$faenas[] = $carga->faena_id;
				}
			}
			
			
			//repuesto
			$compras = CompraRepuestoCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			foreach($compras as $compra){
				if(array_key_exists($compra->faena_id,$repuestos)){
					$repuestos[$compra->faena_id] += $compra->montoNeto;
				}
				else{
					$repuestos[$compra->faena_id] = $compra->montoNeto;
				}
				if(!in_array($compra->faena_id,$faenas)){
					$faenas[] = $compra->faena_id;
				}
			}

			foreach($faenas as $faena_id){
				if($model->faena_id != "" && $model->faena_id != null){
					if($model->faena_id != $faena_id){
						continue;
					}
				}		
				
				$dato['tipo'] = $report['tipo'];
				$dato['fecha'] = $report['fecha'];
				$dato['reporte'] = $report['reporte'];
				$dato['observaciones'] = $report['observaciones'];
				$dato['observaciones_obra'] = $report['observaciones_obra'];
				$dato['camion'] = $report['camion'];
				$dato['camion_codigo'] = $report['camion_codigo'];
				$dato['km_recorridos'] = $report['km_recorridos'];
				$dato['km_gps'] = $report['km_gps'];
				$dato['horas'] = $report['horas'];
				$dato['panne'] = $report['panne'];
				$dato['horas_panne'] = $report['horas_panne'];
				$dato['validado'] = $report['validado'];
				$dato['validador'] = $report['validador'];
				$dato['id'] = $report['id'];
				$dato['faena_id'] = $faena_id;
				$faena = Faena::model()->findByPk($faena_id);
				if(isset($faena)){
					$dato['faena'] = $faena->nombre;
				}
				else{
					$dato['faena'] = " -- NO ASIGNADA -- ";
				}
				if(array_key_exists($faena_id,$producciones)){
					$dato['produccion'] = $producciones[$faena_id];
				}
				else{
					$dato['produccion'] = 0;
				}
				if(array_key_exists($faena_id,$combustibles)){
					$dato['combustible'] = $combustibles[$faena_id];
				}
				else{
					$dato['combustible'] = 0;
				}
				if(array_key_exists($faena_id,$repuestos)){
					$dato['repuestos'] = $repuestos[$faena_id];
				}
				else{
					$dato['repuestos'] = 0;
				}
				
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

	public function actionView($id){
		$this->redirect(CController::createUrl("//rCamionPropio/".$id));
	}
}
