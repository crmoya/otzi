<?php

class InformecombustibleController extends Controller
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
    
	function actionExportar()
	{
		// generate a resultset
		/*
		ExcelExporter::sendAsXLS($data, true, 'Expediciones.xls');
		*/
		$data = Informecombustible::model()->findAll();
		$this->toExcel($data,
			array(
                            'petroleoLts',
                            'carguio',
                            'valorTotal',
                            'faena',
                            'tipoCombustible',
                            'numero',
                            'nombre',
                            'fechaRendicion',
                            'camion',
                            'tipo_documento',
                            'rut_proveedor',
                            'nombre_proveedor',
                            'factura',
                            'reporte',
                            'fecha',
                            'observaciones',
                        ),
			'Cargas de Combustible',
			array()
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
				'roles'=>array('gerencia'),
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
		$model=new Informecombustible('search');
		$model->unsetAttributes();  // clear any default values
		$model->fecha_inicio=date("01/m/Y");
		$model->fecha_fin=date("d/m/Y");
		
		if(isset($_GET['Informecombustible'])){
			$model->attributes=$_GET['Informecombustible'];
			$model->fecha_inicio=$_GET['Informecombustible']['fecha_inicio'];
			$model->fecha_fin=$_GET['Informecombustible']['fecha_fin'];
			$model->nombre=$_GET['Informecombustible']['nombre'];
			$model->numero=$_GET['Informecombustible']['numero'];
			$model->agrupar_por=$_GET['Informecombustible']['agrupar_por'];
			$model->propio_arrendado=$_GET['Informecombustible']['propio_arrendado'];
			
		}
		$model->generarInforme();
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Expediciones the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Informecombustible::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Expediciones $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='expediciones-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
