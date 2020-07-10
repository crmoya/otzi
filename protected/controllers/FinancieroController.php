<?php

class FinancieroController extends Controller
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
		$model=new Financiero('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Financiero'])){
			$model->attributes=$_GET['Financiero'];
			if(isset($_GET['Financiero']['desde_mes']))
				$model->desde_mes=$_GET['Financiero']['desde_mes'];
			if(isset($_GET['Financiero']['hasta_mes']))
				$model->hasta_mes=$_GET['Financiero']['hasta_mes'];
			if(isset($_GET['Financiero']['agrupar_por']))
				$model->agrupar_por=$_GET['Financiero']['agrupar_por'];
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionExportar()
	{
		// generate a resultset
		$data = Financiero::model()->findAll();
		
		$this->toExcel($data,
			array('nombre_contrato','rut_mandante','nombre_mandante', 'mes', 'saldo_por_cobrar_retenciones', 'venta_facturada_neta', 'venta_facturada_acumulada_neta', 'costo', 'costo_acumulado', 'resultado_mensual_neto', 'porc_rent_sobre_valor_contrato', 'resultado_acumulado_neto', 'porc_rent_sobre_valor_contrato_acum'),
			'Financiero',
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
		$model=Financiero::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='financiero-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
