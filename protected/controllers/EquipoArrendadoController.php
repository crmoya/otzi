<?php

class EquipoArrendadoController extends Controller
{
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }
    
	function actionExportar()
	{
		// generate a resultset
		$data = EquipoArrendado::model()->findAll(['order'=>'nombre']);
		
		$this->toExcel($data,
			array('nombre','horasMin','precioUnitario','consumoEsperado','coeficienteDeTrato','propietario_id','valorHora','vigente'),
			'EquiposArrendados',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ProduccionMaquinaria.xls');
	}
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','create','update','admin','delete','exportar'),
				'roles'=>array('administrador'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','create'),
				'roles'=>array('operativo'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(Yii::app()->authManager->isAssigned('operativo',Yii::app()->user->id)){
			$this->layout = "//layouts/column1";
		}
		$model=new EquipoArrendado;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EquipoArrendado']))
		{
			$model->attributes=$_POST['EquipoArrendado'];
			$model->vigente=$_POST['EquipoArrendado']['vigente'];
			if($model->save()){
				$historial = new HistorialPuArrendado;
				$historial->pu = $model->precioUnitario;
				$historial->fecha_desde = date("Y-m-d H:i:s");
				$historial->equipoarrendado_id = $model->id;
				$historial->save();
				$this->redirect(array('view','id'=>$model->id));
			}
				
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$puAnterior = $model->precioUnitario;
		if(isset($_POST['EquipoArrendado']))
		{
			$model->attributes=$_POST['EquipoArrendado'];
			$model->vigente=$_POST['EquipoArrendado']['vigente'];
			$puNuevo = $model->precioUnitario;
			if($puAnterior != $puNuevo){
				$historialAnterior = HistorialPuArrendado::model()->findByAttributes(['equipoarrendado_id'=>$id],['order'=>'id DESC']);
				if(isset($historialAnterior)){
					$historialAnterior->fecha_hasta = date("Y-m-d H:i:s");
					$historialAnterior->save();
				}
				$historial = new HistorialPuArrendado;
				$historial->pu = $puNuevo;
				$historial->fecha_desde = date("Y-m-d H:i:s");
				$historial->equipoarrendado_id = $id;
				$historial->save();
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('EquipoArrendado');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new EquipoArrendado('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EquipoArrendado']))
			$model->attributes=$_GET['EquipoArrendado'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=EquipoArrendado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='equipo-arrendado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
