<?php

class ExpedicionesEquipoPropioController extends Controller
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

		$model=new ExpedicionesEquipoPropio('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpedicionesEquipoPropio'])){
			$model->attributes=$_GET['ExpedicionesEquipoPropio'];
		}

		$cabeceras = [
			['name'=>'Fecha','width'=>'sm'],
			['name'=>'Reporte','width'=>'sm'],
			['name'=>'Obs.','width'=>'sm'],
			['name'=>'Obs.Obra','width'=>'sm'],
			['name'=>'Equipo','width'=>'lg'],
			['name'=>'Hrs.Reales','width'=>'sm'],
			['name'=>'Hrs.GPS','width'=>'sm'],
			['name'=>'Producción','width'=>'sm'],
			['name'=>'Comb.Lts','width'=>'sm'],
			['name'=>'Repuestos($)','width'=>'sm'],
			['name'=>'Hrs.Panne','width'=>'sm'],
			['name'=>'Panne','width'=>'sm'],
			['name'=>'Validar','width'=>'xs'],
			['name'=>'Validado por','width'=>'sm'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable','dots'=>"sm"],
			['campo'=>'reporte','exportable','dots'=>'xs'],
			['campo'=>'observaciones','exportable','dots'=>'md'],
			['campo'=>'observaciones_obra','exportable', 'dots'=>'md'],
			['campo'=>'equipo','exportable', 'dots'=>'md'],
			['campo'=>'horas_reales','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'horas_gps','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'produccion','exportable','format'=>'money','acumulado'=>'suma'],
			['campo'=>'combustible','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'horas_panne','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'panne','exportable'],
			['campo'=>'validado'],
			['campo'=>'validador'],
		];

		$datos = ExpedicionesEquipoPropio::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
