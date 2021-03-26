<?php

class ProduccionCamionesController extends Controller
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

		$model=new ProduccionCamiones('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProduccionCamiones'])){
			$model->attributes=$_GET['ProduccionCamiones'];
		}

		$cabeceras = [
			['name'=>'Camión','width'=>'lg'],
			['name'=>'Chofer','width'=>'lg'],
			['name'=>'Centro Gestión','width'=>'lg'],
			['name'=>'Transportado','width'=>'sm'],
			['name'=>'Prod. Contratada','width'=>'md'],
			['name'=>'Prod. Real','width'=>'md'],
			['name'=>'Diferencia','width'=>'md'],
			['name'=>'Detalles', 'filtro'=>'false'],
		];

		$extra_datos = [
			['campo'=>'camion','exportable','dots'=>"md"],
			['campo'=>'chofer','exportable','dots'=>'md'],
			['campo'=>'centro_gestion','exportable','dots'=>'md'],
			['campo'=>'total_transportado','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'produccion_contratada','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'produccion_real','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'produccion_diferencia','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'camion_id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//produccionCamiones/redirect", 'params'=>['camion_id','chofer_id','faena_id','tipo_camion'], 'fecha_inicio'=>$model->fecha_inicio,'fecha_fin'=>$model->fecha_fin, 'ordenable'=>'false'],
		];

		$datos = ProduccionCamiones::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}

	
	public function actionRedirect($camion_id, $chofer_id, $faena_id, $tipo_camion, $fecha_inicio, $fecha_fin){
		if($tipo_camion == "CA"){
			return $this->redirect(["//expedicionesCamionArrendado/admin?".
										"ExpedicionesCamionArrendado[camion_id]=$camion_id&".
										"ExpedicionesCamionArrendado[chofer_id]=$chofer_id&".
										"ExpedicionesCamionArrendado[faena_id]=$faena_id&".
										"ExpedicionesCamionArrendado[fecha_inicio]=$fecha_inicio&".
										"ExpedicionesCamionArrendado[fecha_fin]=$fecha_fin"]);
		}
		if($tipo_camion == "CP"){
			return $this->redirect(["//expedicionesCamionPropio/admin?".
										"ExpedicionesCamionPropio[camion_id]=$camion_id&".
										"ExpedicionesCamionPropio[chofer_id]=$chofer_id&".
										"ExpedicionesCamionPropio[faena_id]=$faena_id&".
										"ExpedicionesCamionPropio[fecha_inicio]=$fecha_inicio&".
										"ExpedicionesCamionPropio[fecha_fin]=$fecha_fin"]);
		}
	}
}
