<?php

class AdministrativoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','exportar'),
				'roles'=>array('operador'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Administrativo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Administrativo'])){
			$model->attributes=$_GET['Administrativo'];
			if(isset($_GET['Administrativo']['desde_mes']))
				$model->desde_mes=$_GET['Administrativo']['desde_mes'];
			if(isset($_GET['Administrativo']['hasta_mes']))
				$model->hasta_mes=$_GET['Administrativo']['hasta_mes'];
			if(isset($_GET['Administrativo']['desde_fecha']))
				$model->desde_fecha=$_GET['Administrativo']['desde_fecha'];
			if(isset($_GET['Administrativo']['hasta_fecha']))
				$model->hasta_fecha=$_GET['Administrativo']['hasta_fecha'];
			if(isset($_GET['Administrativo']['agrupar_por']))
				$model->agrupar_por=$_GET['Administrativo']['agrupar_por'];
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionExportar()
	{
		// generate a resultset
		$data = Administrativo::model()->findAll();
		
		$this->toExcel($data,
			array('nombre_contrato','mes','numero_resolucion','observacion','fecha_final','suma_monto_contrato'),
			'Administrativo',
			array()
		);
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Administrativo::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='administrativo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
