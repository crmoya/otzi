<?php

class ConsumoMaquinariaController extends Controller
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

		$model=new ConsumoMaquinaria('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ConsumoMaquinaria'])){
			$model->attributes=$_GET['ConsumoMaquinaria'];
		}

	
		$cabeceras = [
			['name'=>'Máquina','width'=>'lg'],
			['name'=>'Operador','width'=>'lg'],
			['name'=>'Lts.Totales','width'=>'md'],
			['name'=>'Hrs.Físicas','width'=>'md'],
			['name'=>'Hrs.GPS','width'=>'md'],
			['name'=>'Lts/Hr Esperados[Lts/Hr]','width'=>'md'],
			['name'=>'Lts/Hr Físicos[Lts/Hr]','width'=>'md'],
			['name'=>'Lts/Hr GPS[Lts/Hr]','width'=>'md'],
		];

		$extra_datos = [
			['campo'=>'maquina','exportable','dots'=>"md"],
			['campo'=>'operador','exportable','dots'=>'md'],
			['campo'=>'litros','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'horas','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'horas_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'consumo_esperado','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'litros_hora','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'litros_hora_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
		];

		$datos = ConsumoMaquinaria::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
