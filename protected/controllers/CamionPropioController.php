<?php

class CamionPropioController extends Controller
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
		$data = CamionPropio::model()->findAll(['order'=>'nombre']);
		
		$this->toExcel($data,
			array('nombre','codigo','capacidad','consumoPromedio','coeficienteDeTrato','produccionMinima','horasMin','pesoOVolumen','vigente'),
			'CamionesPropios',
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','admin','delete','exportar','produccion'),
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

	public function actionProduccion(){
		$id = $_POST['id'];
		$camion = $this->loadModel($id);
		echo $camion->produccionMinima;
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
		$model=new CamionPropio;

                if(Yii::app()->authManager->isAssigned('operativo',Yii::app()->user->id)){
                    $this->layout = "//layouts/column1";
                }
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CamionPropio']))
		{
			$model->attributes=$_POST['CamionPropio'];
			$model->capacidad = str_replace(",",".", $model->capacidad);
			$model->consumoPromedio = str_replace(",",".", $model->consumoPromedio);
			$model->odometro_en_millas = $_POST['CamionPropio']['odometro_en_millas'];
			$model->vigente = $_POST['CamionPropio']['vigente'];
			if($model->horasMin == ""){
				$model->horasMin = 0;
			}
			if($model->produccionMinima == ""){
				$model->produccionMinima = 0;
			}
			if($model->coeficienteDeTrato == ""){
				$model->coeficienteDeTrato = 0;
			}
			if($model->horasMin == 0){
				$model->horasMin = 0.01;
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['CamionPropio']))
		{
			$model->attributes=$_POST['CamionPropio'];
			$model->capacidad = str_replace(",",".", $model->capacidad);
			$model->consumoPromedio = str_replace(",",".", $model->consumoPromedio);
			$model->vigente = $_POST['CamionPropio']['vigente'];
			$model->odometro_en_millas = $_POST['CamionPropio']['odometro_en_millas'];
			if($model->horasMin == ""){
				$model->horasMin = 0;
			}
			if($model->produccionMinima == ""){
				$model->produccionMinima = 0;
			}
			if($model->coeficienteDeTrato == ""){
				$model->coeficienteDeTrato = 0;
			}
			if($model->horasMin == 0){
				$model->horasMin = 0.01;
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
		$dataProvider=new CActiveDataProvider('CamionPropio');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CamionPropio('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CamionPropio']))
			$model->attributes=$_GET['CamionPropio'];

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
		$model=CamionPropio::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='camion-propio-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
