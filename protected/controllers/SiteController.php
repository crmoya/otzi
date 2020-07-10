<?php

class SiteController extends Controller
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
				'actions'=>array('login','logout','error','index'),
				'users'=>array('*'),
		),
		array('allow',
				'actions'=>array('cambiarClave'),
				'users'=>array('@'),
		),
		array('allow',
				'actions'=>array('informes'),
				'roles'=>array('operador'),
		),
		array('allow',
				'actions'=>array('configureRoles'),
				'users'=>array('*'),
		),
		array('allow',
				'actions'=>array('download'),
				'roles'=>array('operador'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		if(Yii::app()->user->isGuest){
			$this->actionLogin();
		}
		else{
                    
                    $usuario = Usuarios::model()->findByPk(Yii::app()->user->id);
                    $nombre = "SIN USUARIO";
                    if($usuario != null){
                        $nombre = $usuario->nombre;
                    }
                    
			if(Yii::app()->user->rol == 'administrador'){
				$this->render("//admin/indexAdmin",array('nombre'=>$nombre));
			}
			else if(Yii::app()->user->rol == 'operador'){
				$this->render("//operador/indexOper",array('nombre'=>$nombre));
			}
			else{
				$this->render("//site/error",array('code'=>'Rol no existe','message'=>'Por favor reingrese a la Aplicación, si el error persiste contacte al administrador del sitio.'));
			}
		}


	}
	
	public function actionDownload($path,$nombre,$tipo)
	{
		if($tipo == "contrato"){
			return Yii::app()->getRequest()->sendFile($nombre, @file_get_contents(Yii::app()->basePath.'/adjuntos/contratos/'.$path));
		}	
		if($tipo == "resolucion"){
			return Yii::app()->getRequest()->sendFile($nombre, @file_get_contents(Yii::app()->basePath.'/adjuntos/resoluciones/'.$path));
		}
		if($tipo == "garantia"){
			return Yii::app()->getRequest()->sendFile($nombre, @file_get_contents(Yii::app()->basePath.'/adjuntos/garantias/'.$path));
		}
		if($tipo == "libro"){
			return Yii::app()->getRequest()->sendFile($nombre, @file_get_contents(Yii::app()->basePath.'/adjuntos/libros/'.$path));
		}		
	}
	public function actionInformes()
	{
		
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		if(Yii::app()->user->isGuest){
			$this->actionLogin();
		}
		else{
			if(Yii::app()->user->rol == 'operador'){
				$this->render("//admin/informes",array('nombre'=>Yii::app()->user->nombre));
			}
		}


	}
/**
	 * This is the action to handle external exceptions.
	 */
	public function actionCambiarClave()
	{
		$form = new CambiarClaveForm();
		if (isset(Yii::app()->user->id))
		{
			if(isset($_POST['CambiarClaveForm']))
			{
				$form->attributes = $_POST['CambiarClaveForm'];
				if($form->validate())
				{
					$new_password = Usuarios::model()->findByPk(Yii::app()->user->id);
					if($new_password->clave != sha1($form->clave))
					{
						$form->addError('clave', "clave incorrecta");
					}
					else
					{
						if($form->nueva == $form->repita){
							$new_password->clave = sha1($form->nueva);								
							if($new_password->save())
							{
								Yii::app()->user->setFlash('profileMessage',
			 					"Clave cambiada correctamente.");
							}
							else
							{
								Yii::app()->user->setFlash('profileMessage',
			 					"No se pudo cambiar la clave, inténtelo de nuevo más tarde.");
							}	
							$this->refresh();
						}
						else{
							$form->addError('nueva', "claves nuevas no coinciden");
							$form->addError('repita', "claves nuevas no coinciden");
						}
						
					}
				}
			}
	 		$this->render('//site/cambiarClave',array('model'=>$form));
	 	}
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

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	public function actionConfigureRoles(){

		$record=AuthAssignment::model()->deleteAll();
		$record=AuthItem::model()->deleteAll();
		$record=AuthItemChild::model()->deleteAll();
		$auth=Yii::app()->authManager;

		$role=$auth->createRole('administrador');
		$role=$auth->createRole('operador');
		
		$auth->assign('administrador',1);

                
		$this->render("//admin/indexAdmin",array('nombre'=>'SIN NOMBRE'));

	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
}