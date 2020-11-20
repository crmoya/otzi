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

		$model=new ConsumoCamiones('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ConsumoCamiones'])){
			$model->attributes=$_GET['ConsumoCamiones'];
		}

	
		$cabeceras = [
			['name'=>'Vehículo','width'=>'lg'],
			['name'=>'Operador','width'=>'lg'],
			['name'=>'Lts.Físicos','width'=>'md'],
			['name'=>'Km.Físicos','width'=>'md'],
			['name'=>'Km.GPS','width'=>'md'],
			['name'=>'Kms/Lt Físicos[Kms/Lt]','width'=>'md'],
			['name'=>'Kms/Lt GPS[Kms/Lt]','width'=>'md'],
			['name'=>'Kms/Lt Esperados[Kms/Lt]','width'=>'md'],
		];

		if($model->decimales != 0){
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'litros','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'kms','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'kms_gps','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'kms_litro','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'kms_litro_gps','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
				['campo'=>'consumo_esperado','exportable', 'format'=>'decimal'.$model->decimales,'acumulado'=>'suma'],
			];
		}
		else{
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'litros','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'kms','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'kms_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'kms_litro','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'kms_litro_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
				['campo'=>'consumo_esperado','exportable', 'format'=>'number','acumulado'=>'suma'],
			];
		}

		$datos = ConsumoCamiones::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
