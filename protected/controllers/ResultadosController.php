<?php

class ResultadosController extends Controller
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

		$model=new Resultados('search');
		$model->fecha_inicio = date("Y-m-01");
		$model->fecha_fin = date("Y-m-t");
		$model->chbRepuestos = true;
		$model->chbCombustible = true;
		$model->chbRemuneraciones = true;
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Resultados'])){
			$model->attributes=$_GET['Resultados'];
		}

		if($model->chbCombustible == 1 && $model->chbRepuestos == 1 && $model->chbRemuneraciones == 1){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Repuestos','width'=>'md'],
				['name'=>'Remuneraciones','width'=>'md'],
				['name'=>'Combustible','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'remuneraciones','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'combustible','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		if($model->chbCombustible == 1 && $model->chbRepuestos == 1 && $model->chbRemuneraciones == 0){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Repuestos','width'=>'md'],
				['name'=>'Combustible','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'combustible','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		else if($model->chbCombustible == 0 && $model->chbRepuestos == 1 && $model->chbRemuneraciones == 1){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Repuestos','width'=>'md'],
				['name'=>'Remuneraciones','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'remuneraciones','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		else if($model->chbCombustible == 0 && $model->chbRepuestos == 1 && $model->chbRemuneraciones == 0){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Repuestos','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'repuestos','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		else if($model->chbCombustible == 1 && $model->chbRepuestos == 0 && $model->chbRemuneraciones == 1){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Remuneraciones','width'=>'md'],
				['name'=>'Combustible','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'remuneraciones','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'combustible','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		else if($model->chbCombustible == 1 && $model->chbRepuestos == 0 && $model->chbRemuneraciones == 0){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Combustible','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'combustible','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		else if($model->chbCombustible == 0 && $model->chbRepuestos == 0 && $model->chbRemuneraciones == 0){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}
		else if($model->chbCombustible == 0 && $model->chbRepuestos == 0 && $model->chbRemuneraciones == 1){
			$cabeceras = [
				['name'=>'Máquina o camión','width'=>'lg'],
				['name'=>'Operador o Chofer','width'=>'lg'],
				['name'=>'Centro Gestión','width'=>'lg'],
				['name'=>'Producción','width'=>'md'],
				['name'=>'Remuneraciones','width'=>'md'],
				['name'=>'Resultados','width'=>'md'],
			];
			$extra_datos = [
				['campo'=>'maquina','exportable','dots'=>"md"],
				['campo'=>'operador','exportable','dots'=>'md'],
				['campo'=>'centro_gestion','exportable','dots'=>'md'],
				['campo'=>'produccion','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'remuneraciones','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'resultados','exportable', 'format'=>'money','acumulado'=>'suma'],
			];
		}

		$datos = Resultados::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
		));
	}
}
