<?php

class ContratosAdjudicadosController extends Controller
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
				'actions'=>array('admin','exportar','exportarFlujos','verDetalle'),
				'roles'=>array('operador'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	function actionExportarFlujos($id)
	{
		// generate a resultset
		$criteria=new CDbCriteria();
		$criteria->condition = 'resoluciones_id IN (SELECT id FROM resoluciones WHERE contratos_id =:contratosID)';
		$criteria->params = array(':contratosID'=>$id);

		$data = FlujosReales::model()->findAll($criteria);
		
		$this->toExcel($data,
			array('mes','agno','produccion'),
			'Flujos Reales',
			array()
		);
	}
	function actionExportar()
	{
		// generate a resultset
		$data = ContratosAdjudicados::model()->findAll();
		
		$this->toExcel($data,
			array('nombre','rut_mandante','nombre_mandante','plazo','fecha_inicio','fecha_termino','monto_inicial_neto','modificaciones_neto','monto_actualizado_neto','totales','diferencia_por_cobrar','observacion'),
			'Contratos Adjudicados',
			array()
		);
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ContratosAdjudicados('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ContratosAdjudicados'])){
			$model->attributes=$_GET['ContratosAdjudicados'];
			if(isset($_GET['ContratosAdjudicados']['inicio_desde']))
				$model->inicio_desde=$_GET['ContratosAdjudicados']['inicio_desde'];
			if(isset($_GET['ContratosAdjudicados']['inicio_hasta']))
				$model->inicio_hasta=$_GET['ContratosAdjudicados']['inicio_hasta'];
			if(isset($_GET['ContratosAdjudicados']['termino_desde']))
				$model->termino_desde=$_GET['ContratosAdjudicados']['termino_desde'];
			if(isset($_GET['ContratosAdjudicados']['termino_hasta']))
				$model->termino_hasta=$_GET['ContratosAdjudicados']['termino_hasta'];
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionVerDetalle($id)
	{
		$dataProvider=new CActiveDataProvider('EpObra', array(
		    'criteria'=>array(
		        'condition'=>'resoluciones_id IN (SELECT id FROM resoluciones WHERE contratos_id =:contratosID)',
		        'order'=>'CAST(CONCAT_WS("-",agno,mes,"01") AS DATE)',
		        'params'=>array(':contratosID'=>$id),
		    ),
		    'pagination'=>array(
		        'pageSize'=>20,
		    ),
		));	
		
		$this->render('verDetalle',array(
			'dataProvider'=>$dataProvider,
			'id'=>$id,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ContratosAdjudicados::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='contratos-adjudicados-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
