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

		$esquema = ["Camión", "Chofer", "Centro Gestión", "Producción Real", "Ver"];

		$columns = "";
		if(isset($_GET['columns'])){
			$columns = $_GET['columns'];
		}
		if($columns == ""){
			for($i=0;$i<count($esquema);$i++){
				$columns .= "1";
			}
		}
		$cabeceras = [];
		$extra_datos = [];
		if(Tools::charAt($columns,0) == "1"){
			$cabeceras[] = ['name'=>'Camión','width'=>'xl'];
			$extra_datos[] = ['campo'=>'camion','exportable','dots'=>"xl"];
		}
		if(Tools::charAt($columns,1) == "1"){
			$cabeceras[] = ['name'=>'Chofer','width'=>'lg'];
			$extra_datos[] = ['campo'=>'chofer','exportable','dots'=>'lg'];
		}
		if(Tools::charAt($columns,2) == "1"){
			$cabeceras[] = ['name'=>'Centro Gestión','width'=>'lg'];
			$extra_datos[] = ['campo'=>'centro_gestion','exportable','dots'=>'lg'];
		}
		if(Tools::charAt($columns,3) == "1"){
			$cabeceras[] = ['name'=>'Prod. Real','width'=>'md'];
			$extra_datos[] = ['campo'=>'produccion_real','exportable', 'format'=>'money','acumulado'=>'suma'];
		}
		if(Tools::charAt($columns,4) == "1"){
			$cabeceras[] = ['name'=>'ver', 'filtro'=>'false'];
			$extra_datos[] = ['campo'=>'camion_id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//produccionCamiones/redirect", 'params'=>['camion_id','chofer_id','faena_id','tipo_camion'], 'fecha_inicio'=>$model->fecha_inicio,'fecha_fin'=>$model->fecha_fin, 'ordenable'=>'false'];
		}

		$datos = ProduccionCamiones::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
			'esquema' => $esquema,
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
