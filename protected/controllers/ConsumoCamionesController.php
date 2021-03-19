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
		foreach($reports as $report){
			
			$litros = 0;
			$kms_litro = 0;
			$kms_litro_gps = 0;
			
			$cargas = [];
			//combustible
			if($report['tipo'] == 'camiones_propios'){
				$cargas = CargaCombCamionPropio::model()->findAllByAttributes(['rCamionPropio_id'=>$report['id']]);
			}
			if($report['tipo'] == 'camiones_arrendados'){
				$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(['rCamionArrendado_id'=>$report['id']]);
			}
			
			foreach($cargas as $carga){
				$litros += $carga->petroleoLts;
			}
			if($litros > 0){
				$kms_litro = ((float)$report['km_recorridos'])/$litros;
				$kms_litro_gps = ((float)$report['km_gps'])/$litros;
			}

			$dato['camion'] = $report['camion'];
			$dato['chofer'] = $report['chofer'];
			$dato['km_recorridos'] = $report['km_recorridos'];
			$dato['km_gps'] = $report['km_gps'];
			$dato['litros'] = $litros;
			$dato['km_litro'] = $kms_litro;
			$dato['km_litro_gps'] = $kms_litro_gps;
			$dato['consumo_esperado'] = $report['consumo_esperado'];
			
			$datos[] = (object)$dato;	
			
		}

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
