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

		$cabeceras_todas = [
			['name'=>'Máquina','width'=>'lg'],
			['name'=>'Operador','width'=>'lg'],
			['name'=>'Centro Gestión','width'=>'lg'],
			['name'=>'PU','width'=>'sm'],
			['name'=>'Hrs. Físicas','width'=>'sm'],
			//['name'=>'Hrs. Contratadas','width'=>'sm'],
			['name'=>'Producción Física','width'=>'md'],
			//['name'=>'Producción Contratada','width'=>'md'],
			['name'=>'Ver', 'filtro'=>'false'],
		];;
		

		$cabeceras = [
			['name'=>'Máquina','width'=>'lg'],
			['name'=>'Operador','width'=>'lg'],
			['name'=>'Centro Gestión','width'=>'lg'],
			['name'=>'PU','width'=>'sm'],
			['name'=>'Hrs. Físicas','width'=>'sm'],
			//['name'=>'Hrs. Contratadas','width'=>'sm'],
			['name'=>'Producción Física','width'=>'md'],
			//['name'=>'Producción Contratada','width'=>'md'],
			['name'=>'Ver', 'filtro'=>'false'],
		];

		$extra_datos = [
			['campo'=>'maquina','exportable','dots'=>"md"],
			['campo'=>'operador','exportable','dots'=>'md'],
			['campo'=>'centro_gestion','exportable','dots'=>'md'],
			['campo'=>'pu','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'horas_fisicas','exportable', 'format'=>'number','acumulado'=>'suma'],
			//['campo'=>'horas_contratadas','exportable', 'format'=>'number','acumulado'=>'suma'],
			['campo'=>'produccion_fisica','exportable', 'format'=>'money','acumulado'=>'suma'],
			//['campo'=>'produccion_contratada','exportable', 'format'=>'money','acumulado'=>'suma'],
			['campo'=>'maquina_id','format'=> 'enlace-imagen', 'new-page'=>'true', 'url'=>"//produccionMaquinaria/redirect", 'params'=>['maquina_id','operador_id','faena_id','tipo_maquina'], 'fecha_inicio'=>$model->fecha_inicio,'fecha_fin'=>$model->fecha_fin, 'ordenable'=>'false'],
		];

		$datos = ProduccionMaquinaria::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
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
