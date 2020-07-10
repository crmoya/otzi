<?php

class InformeGarantiasController extends Controller
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
		$model=new InformeGarantias('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['InformeGarantias'])){
			$model->attributes=$_GET['InformeGarantias'];
			if(isset($_GET['InformeGarantias']['desde_fecha']))
				$model->desde_fecha=$_GET['InformeGarantias']['desde_fecha'];
			if(isset($_GET['InformeGarantias']['hasta_fecha']))
				$model->hasta_fecha=$_GET['InformeGarantias']['hasta_fecha'];
			if(isset($_GET['InformeGarantias']['desde_fecha_d']))
				$model->desde_fecha_d=$_GET['InformeGarantias']['desde_fecha_d'];
			if(isset($_GET['InformeGarantias']['hasta_fecha_d']))
				$model->hasta_fecha_d=$_GET['InformeGarantias']['hasta_fecha_d'];
		}
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionExportar()
	{
		// generate a resultset
		$data = InformeGarantias::model()->findAll();
		
		$this->toExcel($data,
			array('tipo_garantia', 'institucion', 'monto', 'moneda', 'contrato', 'objeto_garantia', 'fecha_vencimiento','estado','fecha_devolucion'),
			'Informe Garantias',
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
		$model=InformeGarantias::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='informe-garantias-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
