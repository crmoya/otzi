<?php

class InformeRegExpEquipoArrendadoController extends Controller
{
	
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
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
	
	function actionExportar()
	{
		// generate a resultset
		$data = InformeRegExpEquipoArrendado::model()->findAll();
		
		$this->toExcel($data,
			array('fecha','reporte','observaciones','observaciones_obra','equipo','horasReales','combustible','repuesto','horasGps','horasPanne','panne','faena'),
			'Expediciones Equipo Arrendado',
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
		$model=new InformeRegExpEquipoArrendado;
		$id_reg = $model->getReg($id);
		$this->redirect(CController::createUrl("//rEquipoArrendado/".$id_reg));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new InformeRegExpEquipoPropio;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['InformeRegExpEquipoPropio']))
		{
			$model->attributes=$_POST['InformeRegExpEquipoPropio'];
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

		if(isset($_POST['InformeRegExpEquipoPropio']))
		{
			$model->attributes=$_POST['InformeRegExpEquipoPropio'];
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
		$dataProvider=new CActiveDataProvider('InformeRegExpEquipoPropio');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new InformeRegExpEquipoArrendado('search');
		$model->unsetAttributes();  // clear any default values
		
		$model->fechaInicio = "01/".date("m/Y");
		$model->fechaFin = date("t/").date("m/Y");

		if(isset($_GET['InformeRegExpEquipoArrendado'])){
                    $model->attributes=$_GET['InformeRegExpEquipoArrendado'];
                if(isset($_GET['InformeRegExpEquipoArrendado']['fechaInicio']))
                    $model->fechaInicio=$_GET['InformeRegExpEquipoArrendado']['fechaInicio'];
                if(isset($_GET['InformeRegExpEquipoArrendado']['fechaFin']))
                    $model->fechaFin=$_GET['InformeRegExpEquipoArrendado']['fechaFin'];
                if(isset($_GET['InformeRegExpEquipoArrendado']['reporte']))
                    $model->reporte=$_GET['InformeRegExpEquipoArrendado']['reporte'];
                if(isset($_GET['InformeRegExpEquipoArrendado']['equipo_id']))
                    $model->equipo_id=$_GET['InformeRegExpEquipoArrendado']['equipo_id'];
		}

		
                $model->generarInforme();
		$this->render('admin',array('model'=>$model,));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=InformeRegExpEquipoPropio::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='informe-reg-exp-equipo-propio-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
