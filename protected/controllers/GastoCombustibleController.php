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
			['campo'=>'id','dots'=>'xs'],
		];

		$datos = GastoCombustible::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
