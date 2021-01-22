<?php

class TipoCombustibleRGController extends Controller
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
		$model=new TipoCombustibleRG('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TipoCombustibleRG'])){
			$model->attributes=$_GET['TipoCombustibleRG'];
		}

		
		$modelForm = new TipoCombustibleRGForm;
		if(isset($_POST['TipoCombustibleRGForm'])){
			$modelForm->attributes=$_POST['TipoCombustibleRGForm'];
			$modelForm->tipocombustiblesam = $_POST['TipoCombustibleRGForm']['tipocombustiblesam'];
			$tipoRG = TipoCombustibleRG::model()->findByAttributes(['tipocombustible'=>$modelForm->tipocombustible,'tipoCombustible_id'=>$modelForm->tipocombustiblesam]);
			if(!isset($tipoRG)){
				$tipoRG = new TipoCombustibleRG();
				$tipoRG->tipoCombustible_id = $modelForm->tipocombustiblesam;
				$tipoRG->tipocombustible = $modelForm->tipocombustible;
				$tipoRG->save();
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
		$model = TipoCombustibleRG::model()->findByPk($id);
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
