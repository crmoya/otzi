<?php

class AdminController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
		array('allow',
				'actions'=>array('usuariosContratos','asociarUsuarios'),
				'roles'=>array('administrador'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}
	
	
	public function actionUsuariosContratos(){
		$model=new Contratos('search');
		$model->unsetAttributes();  
		if(isset($_GET['Contratos']))
			$model->attributes=$_GET['Contratos'];
		$this->render('usuariosContratos',array(
			'model'=>$model,
		));
	}
	
	public function actionAsociarUsuarios($id){
		$contrato=Contratos::model()->findByPk($id);
		$usuarios=new Usuarios('search');
		$usuarios->unsetAttributes();  
		if(isset($_GET['Usuarios']))
			$usuarios->attributes=$_GET['Usuarios'];

		$total = 0;
		if(isset($_POST['Usuarios'])){
			UsuariosContratos::model()->deleteAllByAttributes(array('contratos_id'=>$id));
			if(isset($_POST['chbUsuarioId'])){
				foreach($_POST['chbUsuarioId'] as $i=>$usuario){
					$usuario_contrato = new UsuariosContratos();
					$usuario_contrato->contratos_id = $id;
					$usuario_contrato->usuarios_id = $usuario;
					if($usuario_contrato->validate()){
						$usuario_contrato->save();
						$total++;
					}				
				}
			}
			Yii::app()->user->setFlash('asociarMessage',$total." usuarios asociados a Contrato ".$contrato->nombre);
			$this->refresh();
		}
		
			
		$this->render('asociarUsuarios',array(
			'contrato'=>$contrato,
			'usuarios'=>$usuarios,
		));     
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

		
	
	
	

}