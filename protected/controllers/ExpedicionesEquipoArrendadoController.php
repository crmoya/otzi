<?php

class ExpedicionesEquipoArrendadoController extends Controller
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

		$model=new ExpedicionesEquipoArrendado('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ExpedicionesEquipoArrendado'])){
			$model->attributes=$_GET['ExpedicionesEquipoArrendado'];
		}

		$cabeceras = [
			['name'=>'Fecha','width'=>'md'],
			['name'=>'Reporte','width'=>'md'],
			['name'=>'Obs.','width'=>'sm'],
			['name'=>'Obs.Obra','width'=>'md'],
			['name'=>'Equipo','width'=>'lg'],
			['name'=>'Hrs.Reales','width'=>'sm'],
			['name'=>'Hrs.GPS','width'=>'sm'],
			['name'=>'Producción','width'=>'md'],
			['name'=>'Comb.Lts','width'=>'sm'],
			['name'=>'Repuestos($)','width'=>'sm'],
			['name'=>'Hrs.Panne','width'=>'sm'],
			['name'=>'Panne','width'=>'sm'],
			['name'=>'Validar', 'filtro'=>'validacion', 'width'=>'xs'],
			['name'=>'Validado por','width'=>'md'],
			['name'=>'Adjuntos', 'filtro'=>'checkbox'],
			['name'=>'Modificaciones', 'filtro'=>'false'],
		];

		$extra_datos = [
			['campo'=>'fecha','exportable','dots'=>"sm"],
			['campo'=>'reporte','exportable','format'=> 'enlace', 'new-page'=>'true', 'url'=>"//rEquipoArrendado/view", 'params'=>['id']],
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
			['campo'=>'validado','format'=>'validado','params'=>['id'],'ordenable'=>'false'],
			['campo'=>'validador'],
			['campo'=>'id','format'=> 'enlace-documento', 'new-page'=>'true', 'url'=>"//admin/preview", 'params'=>['id','tipo'],'ordenable'=>'false'],
			['campo'=>'id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//rEquipoArrendado/verHistorial", 'params'=>['id'],'ordenable'=>'false'],
		];

		$datos = ExpedicionesEquipoPropio::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	public function actionView($id){
		$this->redirect(CController::createUrl("//rEquipoArrendado/".$id));
	}
}