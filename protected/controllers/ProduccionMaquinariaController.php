<?php

class ProduccionMaquinariaController extends Controller
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
				'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'exportar', 'export','redirect'),
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

		$model=new ProduccionMaquinaria('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProduccionMaquinaria'])){
			$model->attributes=$_GET['ProduccionMaquinaria'];
		}

		$esquema = ["Máquina", "Operador", "Centro Gestión", "Producción Física", "Ver"];

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
			$cabeceras[] = ['name'=>'Máquina','width'=>'xl'];
			$extra_datos[] = ['campo'=>'maquina','exportable','dots'=>"xl"];
		}
		if(Tools::charAt($columns,1) == "1"){
			$cabeceras[] = ['name'=>'Operador','width'=>'lg'];
			$extra_datos[] = ['campo'=>'operador','exportable','dots'=>'md'];
		}
		if(Tools::charAt($columns,2) == "1"){
			$cabeceras[] = ['name'=>'Centro Gestión','width'=>'lg'];
			$extra_datos[] = ['campo'=>'centro_gestion','exportable','dots'=>'md'];
		}
		if(Tools::charAt($columns,3) == "1"){
			$cabeceras[] = ['name'=>'Producción Física','width'=>'md'];
			$extra_datos[] = ['campo'=>'produccion_fisica','exportable', 'format'=>'money','acumulado'=>'suma'];
		}
		if(Tools::charAt($columns,4) == "1"){
			$cabeceras[] = ['name'=>'Ver', 'filtro'=>'false'];
			$extra_datos[] = ['campo'=>'maquina_id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//produccionMaquinaria/redirect", 'params'=>['maquina_id','operador_id','faena_id','tipo_maquina'], 'fecha_inicio'=>$model->fecha_inicio,'fecha_fin'=>$model->fecha_fin, 'ordenable'=>'false'];
		}

		$datos = ProduccionMaquinaria::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
			'esquema' => $esquema,
		));
	}

	public function actionRedirect($maquina_id, $operador_id, $faena_id, $tipo_maquina, $fecha_inicio, $fecha_fin){
		if($tipo_maquina == "EA"){
			return $this->redirect(["//expedicionesEquipoArrendado/admin?".
										"ExpedicionesEquipoArrendado[equipo_id]=$maquina_id&".
										"ExpedicionesEquipoArrendado[operador_id]=$operador_id&".
										"ExpedicionesEquipoArrendado[faena_id]=$faena_id&".
										"ExpedicionesEquipoArrendado[fecha_inicio]=$fecha_inicio&".
										"ExpedicionesEquipoArrendado[fecha_fin]=$fecha_fin"]);
		}
		else if($tipo_maquina == "EP"){
			return $this->redirect(["//expedicionesEquipoPropio/admin?".
										"ExpedicionesEquipoPropio[equipo_id]=$maquina_id&".
										"ExpedicionesEquipoPropio[operador_id]=$operador_id&".
										"ExpedicionesEquipoPropio[faena_id]=$faena_id&".
										"ExpedicionesEquipoPropio[fecha_inicio]=$fecha_inicio&".
										"ExpedicionesEquipoPropio[fecha_fin]=$fecha_fin"]);
		}
		else{
			return $this->redirect(["//expedicionesEquipo/admin?".
										"ExpedicionesEquipo[equipo_id]=$maquina_id&".
										"ExpedicionesEquipo[operador_id]=$operador_id&".
										"ExpedicionesEquipo[faena_id]=$faena_id&".
										"ExpedicionesEquipo[fecha_inicio]=$fecha_inicio&".
										"ExpedicionesEquipo[fecha_fin]=$fecha_fin"]);
		}
	}
}
