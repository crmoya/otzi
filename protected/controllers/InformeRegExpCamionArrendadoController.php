<?php

class InformeregexpcamionarrendadoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

        
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }
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

        function actionExportar()
	{
		// generate a resultset
		$data = Informeregexpcamionarrendado::model()->findAll();
		
		$this->toExcel($data,
			array('fecha','reporte','observaciones','observaciones_obra','camion','kmRecorridos','kmGps','combustible','repuesto','produccionReal','horasPanne','panne'),
			'Expediciones Camión Arrendado',
			array()
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
				'actions'=>array('admin','view','exportar'),
				'roles'=>array('gerencia'),
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
		$model=new Informeregexpcamionarrendado;
		$id_reg = $model->getReg($id);
		$this->redirect(CController::createUrl("//rCamionArrendado/".$id_reg));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Informeregexpcamionarrendado;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Informeregexpcamionarrendado']))
		{
			$model->attributes=$_POST['Informeregexpcamionarrendado'];
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

		if(isset($_POST['Informeregexpcamionarrendado']))
		{
			$model->attributes=$_POST['Informeregexpcamionarrendado'];
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Informeregexpcamionarrendado');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Informeregexpcamionarrendado('search');
		$model->unsetAttributes();  // clear any default values
		
                $model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['Informeregexpcamionarrendado'])){
                    $model->attributes=$_GET['Informeregexpcamionarrendado'];
                if(isset($_GET['Informeregexpcamionarrendado']['fechaInicio']))
                    $model->fechaInicio=$_GET['Informeregexpcamionarrendado']['fechaInicio'];
                if(isset($_GET['Informeregexpcamionarrendado']['fechaFin']))
                    $model->fechaFin=$_GET['Informeregexpcamionarrendado']['fechaFin'];
                if(isset($_GET['Informeregexpcamionarrendado']['reporte']))
                    $model->reporte=$_GET['Informeregexpcamionarrendado']['reporte'];
                if(isset($_GET['Informeregexpcamionarrendado']['camion_id']))
                    $model->camion_id=$_GET['Informeregexpcamionarrendado']['camion_id'];
                }
		$model->generarInforme();
		
		$this->render('admin',array('model'=>$model,));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Informeregexpcamionarrendado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Informeregexpcamionarrendado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Informeregexpcamionarrendado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='informeregexpcamionarrendado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
