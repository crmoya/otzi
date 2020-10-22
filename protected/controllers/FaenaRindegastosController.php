<?php

class FaenaRindegastosController extends Controller
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
		$vinculados = FaenaRindegasto::model()->findAll();
		if(count($vinculados)==0){
			FaenaRindegasto::autoVincular();
		}
		$model=new FaenaRindegasto('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FaenaRindegasto'])){
			$model->attributes=$_GET['FaenaRindegasto'];
		}

		$modelForm = new FaenaRindegastosForm;
		if(isset($_POST['FaenaRindegastosForm'])){
			$modelForm->attributes=$_POST['FaenaRindegastosForm'];
			$modelForm->faenasam = $_POST['FaenaRindegastosForm']['faenasam'];
			$faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$modelForm->faena,'faena_id'=>$modelForm->faenasam]);
			if(!isset($faenaRG)){
				$faenaRG = new FaenaRindegasto();
				$faenaRG->faena_id = $modelForm->faenasam;
				$faenaRG->faena = $modelForm->faena;
				$faenaRG->save();
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
		$model = FaenaRindegasto::model()->findByPk($id);
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
