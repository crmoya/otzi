<?php

class VehiculoRindegastosController extends Controller
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('vincular', 'delete'),
                'roles' => array('administrador'),
			),
            array('deny', // deny all users
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


	
	public function actionVincular(){
		$vinculados = VehiculoRindegastos::model()->findAll();
		if(count($vinculados)==0){
			VehiculoRindegastos::autoVincular();
		}
		$model=new VehiculoRindegastos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['VehiculoRindegastos'])){
			$model->attributes=$_GET['VehiculoRindegastos'];
		}

		$modelForm = new VehiculoRindegastosForm;
		if(isset($_POST['VehiculoRindegastosForm'])){
			$modelForm->attributes=$_POST['VehiculoRindegastosForm'];
			$modelForm->vehiculosam = $_POST['VehiculoRindegastosForm']['vehiculosam'];
			$vehiculoSamArr = explode("-",$modelForm->vehiculosam);
			$id = (int)$vehiculoSamArr[0];
			$tipo = $vehiculoSamArr[1];
			if($tipo == "cp"){
				$vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$modelForm->vehiculo,'camionpropio_id'=>$id]);
				if(!isset($vehiculoRG)){
					$vehiculoRG = new VehiculoRindegastos();
					$vehiculoRG->camionpropio_id = $id;
					$vehiculoRG->vehiculo = $modelForm->vehiculo;
					$vehiculoRG->save();
				}
			}
			if($tipo == "ca"){
				$vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$modelForm->vehiculo,'camionarrendado_id'=>$id]);
				if(!isset($vehiculoRG)){
					$vehiculoRG = new VehiculoRindegastos();
					$vehiculoRG->camionarrendado_id = $id;
					$vehiculoRG->vehiculo = $modelForm->vehiculo;
					$vehiculoRG->save();
					echo CHtml::errorSummary($vehiculoRG);
				}
			}
			if($tipo == "ep"){
				$vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$modelForm->vehiculo,'equipopropio_id'=>$id]);
				if(!isset($vehiculoRG)){
					$vehiculoRG = new VehiculoRindegastos();
					$vehiculoRG->equipopropio_id = $id;
					$vehiculoRG->vehiculo = $modelForm->vehiculo;
					$vehiculoRG->save();
				}
			}
			if($tipo == "ea"){
				$vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$modelForm->vehiculo,'equipoarrendado_id'=>$id]);
				if(!isset($vehiculoRG)){
					$vehiculoRG = new VehiculoRindegastos();
					$vehiculoRG->equipoarrendado_id = $id;
					$vehiculoRG->vehiculo = $modelForm->vehiculo;
					$vehiculoRG->save();
				}
			}
			
		}	

		$this->render('vincular', array('model'=>$model,'modelForm'=>$modelForm));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		
		$model = $this->loadModel($id);
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	
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
		$model = VehiculoRindegastos::model()->findByPk($id);
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
