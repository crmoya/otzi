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


	public function actionConfigureRoles(){

/*
		$record=Authassignment::model()->deleteAll();
		$record=Authitem::model()->deleteAll();
		$record=Authitemchild::model()->deleteAll();
		$auth=Yii::app()->authManager;

		$auth->createOperation('configureRoles','configure app roles');

		$role=$auth->createRole('administrador');
		$role->addChild('configureRoles');

		$role=$auth->createRole('operativo');
		$role=$auth->createRole('gerencia');
		
		$auth->assign('administrador',4);

		$this->render("//admin/indexAdmin",array('nombre'=>Yii::app()->user->nombre));
*/
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
			if(Yii::app()->user->rol == 'administrador'){
				$this->render("//admin/indexAdmin",array('nombre'=>Yii::app()->user->nombre));
			}
			if(Yii::app()->user->rol == 'operativo'){
				$this->render("//operativo/indexOper",array('nombre'=>Yii::app()->user->nombre));
			}
			if(Yii::app()->user->rol == 'gerencia'){
				$this->render("//gerencia/indexGerencia",array('nombre'=>Yii::app()->user->nombre));
			}
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
					$new_password = Usuario::model()->findByPk(Yii::app()->user->id);
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
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	
	public function actionCompleta()
	{
		set_time_limit(0);
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$transaction=$connection->beginTransaction();
		try
		{
			$errores = [];
			$gastos = Gasto::model()->findAll();
			foreach($gastos as $gasto){
				$extras = $gasto->extraGastos;
				foreach($extras as $extra){					
					if(strtolower(trim($extra->name)) == "10% impto. retenido"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->retenido = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "cantidad"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->cantidad = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "centro de costo / faena"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->centro_costo_faena = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "departamento"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->departamento = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "faena"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->faena = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(trim($extra->name) == "Impuesto específico"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->impuesto_especifico = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "iva"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->iva = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(trim($extra->name) == "Km.Carguío"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->km_carguio = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "litros combustible"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->litros_combustible = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "monto neto"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->monto_neto = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "nombre quien rinde"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->nombre_quien_rinde = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(trim($extra->name) == "Número de Documento"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->nro_documento = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(trim($extra->name) == "Período de Planilla"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->periodo_planilla = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "rut proveedor"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->rut_proveedor = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "supervisor de combustible"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->supervisor_combustible = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "tipo de documento"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->tipo_documento = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "unidad"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->unidad = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(strtolower(trim($extra->name)) == "vehiculo o equipo"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->vehiculo_equipo = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
					if(trim($extra->name) == "Vehículo Oficina Central"){
						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
						if(!isset($gasto_completa)){
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
						}
						$gasto_completa->vehiculo_oficina_central = $extra->value;
						if(!$gasto_completa->save()){
							$errores[] = $gasto_completa->errors;
						}
					}
				}
			}	
			if(count($errores) == 0){
				$transaction->commit();
			}
			else{
				echo "<pre>";
				print_r($errores);
				echo "</pre>";
			}
		}
		catch(Exception $e)
		{
			$transaction->rollback();
		}
		

		$connection->active=false;
	}

	public function actionLoad()
	{
		set_time_limit(0);
		$connection=Yii::app()->db;
		$connection->active=true;
		
		$transaction=$connection->beginTransaction();
		try
		{
			$limite = 1;
			$primero = true;
			$errores = [];
			for($i = 1; $i <= $limite; $i++){
				$resultado = Tools::jwt_request(
					"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMzA2NDIiLCJjb21wYW55X2lkIjoiMzY0NiIsInJhbmRvbSI6InJhbmRBUEk1ZjEwNTdmYzVjOWU0MC4zNzY0MjU0MSJ9.Y3YjaG4SaO0SY9LPE_Uwuf809J4d_1lTTVgX8yCaQ5k",
					$i
				);
				if($primero){
					$limite = (int)$resultado->Records->Pages;
					$primero = false;
				}
				$expenses = $resultado->Expenses;
				foreach($expenses as $expense){
					$gasto = new Gasto();
					$gasto->supplier = $expense->Supplier;
					$gasto->issue_date = $expense->IssueDate;
					$gasto->net = $expense->Net;
					$gasto->total = $expense->Total;
					$gasto->category = $expense->Category;
					$gasto->category_group = $expense->CategoryGroup;
					$gasto->note = $expense->Note;
					$gasto->expense_policy_id = (int)$expense->ExpensePolicyId;
					if(!$gasto->save()){
						$errores[] = $gasto->errors;
					}
					else{
						if(isset($expense->ExtraFields)){
							foreach($expense->ExtraFields as $extra){
								$extra_gasto = new ExtraGasto();
								$extra_gasto->name = $extra->Name;
								$extra_gasto->value = $extra->Value;
								$extra_gasto->code = $extra->Code;
								$extra_gasto->gasto_id = $gasto->id;
								if(!$extra_gasto->save()){
									$errores[] = $extra_gasto->errors;
								}
							}
						}
					}
					if(isset($expense->Files)){
						$files = $expense->Files;
						foreach($files as $file){
							$gasto_imagen = new GastoImagen();
							$gasto_imagen->file_name = $file->FileName;
							$gasto_imagen->extension = $file->Extension;
							$gasto_imagen->original = $file->Original;
							$gasto_imagen->large = $file->Large;
							$gasto_imagen->medium = $file->Medium;
							$gasto_imagen->small = $file->Small;
							$gasto_imagen->gasto_id = $gasto->id;
							if(!$gasto_imagen->save()){
								$errores[] = $gasto_imagen->errors;
							}
						}
					}
				}				
			}
			if(count($errores) == 0){
				$transaction->commit();
			}
			else{
				echo "<pre>";
				print_r($errores);
				echo "</pre>";
			}
		}
		catch(Exception $e)
		{
			$transaction->rollback();
		}
		

		$connection->active=false;

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

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
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
				'actions'=>array('login','logout','error','index','load','completa'),
				'users'=>array('*'),
		),
		array('allow',
				'actions'=>array('cambiarClave'),
				'users'=>array('@'),
		),
		array('allow',
				'actions'=>array('configureRoles'),
				'roles'=>array('administrador'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}
}