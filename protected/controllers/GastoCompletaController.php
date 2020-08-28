<?php

class GastoCompletaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

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
				'roles' => array('administrador'),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}


	function actionExportar($policy)
	{
		
		set_time_limit(0);
		$criteria = new CDbCriteria;
		$criteria->with = array('gasto');
		$criteria->addColumnCondition([
			'gasto.expense_policy_id'=>$policy
		]);
		

		$session=new CHttpSession;
		$session->open();
		if(isset($session['criteria'])){
			$criteria = $session['criteria'];
		}

		$data = GastoCompleta::model()->findAll($criteria);
		
		$this->toExcel(
			$data,
			[
				($policy == GastoCompleta::POLICY_COMBUSTIBLES)?'supplier':'commerce',
				'date',
				'net',
				'tot',
				'category',
				'categorygroup',
				'note',
				'retenido',
				'cantidad',
				'centro_costo_faena',
				'departamento',
				'faena',
				'impuesto_especifico',
				'iva',
				'km_carguio',
				'litros_combustible',
				'monto_neto',
				'nombre_quien_rinde',
				'nro_documento',
				'periodo_planilla',
				'rut_proveedor',
				'supervisor_combustible',
				'tipo_documento',
				'unidad',
				'vehiculo_equipo',
				'vehiculo_oficina_central',
			],
			'Gastos',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ProduccionMaquinaria.xls');
	}

	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new GastoCompleta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['GastoCompleta'])) {
			$model->attributes = $_POST['GastoCompleta'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['GastoCompleta'])) {
			$model->attributes = $_POST['GastoCompleta'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('GastoCompleta');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($policy)
	{
		
		$this->pageTitle = "";

		$model=new GastoCompleta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GastoCompleta'])){
			$model->attributes=$_GET['GastoCompleta'];
		}

		$model->policy = $policy;

		$gastoNombre = "DEPARTAMENTO DE MAQUINARIA DIFERENTE DE COMBUSTIBLES";
		if($policy == GastoCompleta::POLICY_COMBUSTIBLES){
			$gastoNombre = "COMBUSTIBLES";
			$cabeceras = [
				['name'=>'Proveedor','width'=>'md'],
				['name'=>'Fecha','width'=>'sm', 'format'=>'date'],
				['name'=>'Imp. Esp.','width'=>'xs'],
				['name'=>'IVA','width'=>'xs'],
				['name'=>'Neto','width'=>'xs'],
				['name'=>'Total','width'=>'xs'],
				['name'=>'Categoría','width'=>'md'],
				['name'=>'Nota','width'=>'md'],
				['name'=>'Cantidad (lts.)','width'=>'md'],
				['name'=>'C. Costo Faena','width'=>'md'],
				['name'=>'Rendidor','width'=>'md'],
				['name'=>'Nº doc.','width'=>'md'],
				['name'=>'Tipo doc.','width'=>'sm'],
				['name'=>'Vehículo Equipo','width'=>'lg'],
				['name'=>'Folio','width'=>'xs'],
				['name'=>'Imagen','width'=>'xs'],
			];
	
			$extra_datos = [
				['campo'=>'supplier','exportable','dots'=>"md"],
				['campo'=>'date','exportable','dots'=>'sm'],
				['campo'=>'impuesto_especifico','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'iva','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'monto_neto','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'total','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'categoria','exportable','dots'=>"md"],
				['campo'=>'nota','exportable','dots'=>"md"],
				['campo'=>'litros_combustible','exportable','acumulado'=>'suma'],
				['campo'=>'centro_costo_faena','exportable','dots'=>"md"],
				['campo'=>'nombre_quien_rinde','exportable','dots'=>"md"],
				['campo'=>'nro_documento','exportable','dots'=>"sm"],
				['campo'=>'tipo_documento','exportable','dots'=>"sm"],
				['campo'=>'vehiculo_equipo','exportable','dots'=>"md"],
				['campo'=>'folio','exportable','format'=> 'enlace', 'url'=>"//informeGasto/view", 'params'=>['folio','gasto_id']],
				['campo'=>'imagen','format'=>'imagen','dots'=>'xs'],
			];
		}
		else{
			$cabeceras = [
				['name'=>'Comercio','width'=>'md'],
				['name'=>'Fecha','width'=>'sm', 'format'=>'date'],
				['name'=>'Neto','width'=>'xs'],
				['name'=>'IVA','width'=>'xs'],
				['name'=>'Total','width'=>'xs'],
				['name'=>'Categoría','width'=>'md'],
				['name'=>'C. Costo Faena','width'=>'md'],
				['name'=>'Rendidor','width'=>'md'],
				['name'=>'Tipo doc.','width'=>'sm'],
				['name'=>'Nº doc.','width'=>'md'],
				['name'=>'Vehículo Equipo','width'=>'lg'],
				['name'=>'Folio','width'=>'xs'],
				['name'=>'Imagen','width'=>'xs'],
			];
	
			$extra_datos = [
				['campo'=>'supplier','exportable','dots'=>"md"],
				['campo'=>'date','exportable','dots'=>'sm'],
				['campo'=>'monto_neto','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'iva','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'total','exportable', 'format'=>'money','acumulado'=>'suma'],
				['campo'=>'categoria','exportable','dots'=>"md"],
				['campo'=>'centro_costo_faena','exportable','dots'=>"md"],
				['campo'=>'nombre_quien_rinde','exportable','dots'=>"md"],
				['campo'=>'tipo_documento','exportable','dots'=>"sm"],
				['campo'=>'nro_documento','exportable','dots'=>"sm"],
				['campo'=>'vehiculo_equipo','exportable','dots'=>"md"],
				['campo'=>'folio','exportable'],
				['campo'=>'imagen','format'=>'imagen','dots'=>'xs'],
			];
		}

		$datos = GastoCompleta::model()->findAll($model->search());

		$this->render("admin",array(
			'model'=>$model,
			'datos' => $datos,
			'cabeceras' => $cabeceras,
			'extra_datos' => $extra_datos,
			'gastoNombre' => $gastoNombre,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GastoCompleta the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = GastoCompleta::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GastoCompleta $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'gasto-completa-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
