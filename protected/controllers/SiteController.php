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
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}

	public function actionRepair(){
		$nocombustibles = NocombustibleRindegasto::model()->findAllByAttributes(['faena_id'=>0]);
		foreach($nocombustibles as $nocombustible){
			$gastoCompleta = GastoCompleta::model()->findByPk($nocombustible->gasto_completa_id);
			$faena_id = 0;
			if(isset($gastoCompleta)){
				$faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
				if(isset($faenaRG)){
					$faena_id = $faenaRG->faena_id;
				}
				$nocombustible->faena_id = $faena_id;
				$nocombustible->save();
				if($nocombustible->camionpropio_id != null){
					$compra = CompraRepuestoCamionPropio::model()->findByPk($nocombustible->compra_id);
					if(isset($compra)){
						$compra->faena_id = $faena_id;
						$compra->save();
					}
				}
				if($nocombustible->camionarrendado_id != null){
					$compra = CompraRepuestoCamionArrendado::model()->findByPk($nocombustible->compra_id);
					if(isset($compra)){
						$compra->faena_id = $faena_id;
						$compra->save();
					}
				}
				if($nocombustible->equipopropio_id != null){
					$compra = CompraRepuestoEquipoPropio::model()->findByPk($nocombustible->compra_id);
					if(isset($compra)){
						$compra->faena_id = $faena_id;
						$compra->save();
					}
				}
				if($nocombustible->equipoarrendado_id != null){
					$compra = CompraRepuestoEquipoArrendado::model()->findByPk($nocombustible->compra_id);
					if(isset($compra)){
						$compra->faena_id = $faena_id;
						$compra->save();
					}
				}
			}
		}

		$combustibles = CombustibleRindegasto::model()->findAllByAttributes(['faena_id'=>0]);
		foreach($combustibles as $combustible){
			$gastoCompleta = GastoCompleta::model()->findByPk($combustible->gasto_completa_id);
			$faena_id = 0;
			if(isset($gastoCompleta)){
				$faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
				if(isset($faenaRG)){
					$faena_id = $faenaRG->faena_id;
				}
				$combustible->faena_id = $faena_id;
				$combustible->save();
				if($combustible->camionpropio_id != null){
					$carga = CargaCombCamionPropio::model()->findByPk($combustible->carga_id);
					if(isset($carga)){
						$carga->faena_id = $faena_id;
						$carga->save();
					}
				}
				if($nocombustible->camionarrendado_id != null){
					$carga = CargaCombCamionArrendado::model()->findByPk($combustible->carga_id);
					if(isset($carga)){
						$carga->faena_id = $faena_id;
						$carga->save();
					}
				}
				if($nocombustible->equipopropio_id != null){
					$carga = CargaCombEquipoPropio::model()->findByPk($combustible->carga_id);
					if(isset($carga)){
						$carga->faena_id = $faena_id;
						$carga->save();
					}
				}
				if($nocombustible->equipoarrendado_id != null){
					$carga = CargaCombEquipoArrendado::model()->findByPk($combustible->carga_id);
					if(isset($carga)){
						$carga->faena_id = $faena_id;
						$carga->save();
					}
				}
			}
		}

		$remuneraciones = RemuneracionRindegasto::model()->findAllByAttributes(['faena_id'=>0]);
		foreach($remuneraciones as $remuneracion){
			$gastoCompleta = GastoCompleta::model()->findByPk($remuneracion->gasto_completa_id);
			$faena_id = 0;
			if(isset($gastoCompleta)){
				$faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
				if(isset($faenaRG)){
					$faena_id = $faenaRG->faena_id;
				}
				$remuneracion->faena_id = $faena_id;
				$remuneracion->save();
				if($remuneracion->camionpropio_id != null){
					$rem = RemuneracionCamionPropio::model()->findByPk($remuneracion->remuneracion_id);
					if(isset($rem)){
						$rem->faena_id = $faena_id;
						$rem->save();
					}
				}
				if($remuneracion->camionarrendado_id != null){
					$rem = RemuneracionCamionArrendado::model()->findByPk($remuneracion->remuneracion_id);
					if(isset($rem)){
						$rem->faena_id = $faena_id;
						$rem->save();
					}
				}
				if($remuneracion->equipopropio_id != null){
					$rem = RemuneracionEquipoPropio::model()->findByPk($remuneracion->remuneracion_id);
					if(isset($rem)){
						$rem->faena_id = $faena_id;
						$rem->save();
					}
				}
				if($remuneracion->equipoarrendado_id != null){
					$rem = RemuneracionEquipoArrendado::model()->findByPk($remuneracion->remuneracion_id);
					if(isset($rem)){
						$rem->faena_id = $faena_id;
						$rem->save();
					}
				}
			}
		}
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		if (Yii::app()->user->isGuest) {
			$this->actionLogin();
		} else {
			if (Yii::app()->user->rol == 'administrador') {
				$this->render("//admin/indexAdmin", array('nombre' => Yii::app()->user->nombre));
			}
			if (Yii::app()->user->rol == 'operativo') {
				$this->render("//operativo/indexOper", array('nombre' => Yii::app()->user->nombre));
			}
			if (Yii::app()->user->rol == 'gerencia') {
				$this->render("//gerencia/indexGerencia", array('nombre' => Yii::app()->user->nombre));
			}
		}
	}


	
	public function actionRindegastos(){
		set_time_limit(0);
		$carga = new Carga();
		$carga->rindeGastos();
	}

	public function actionGastos(){
		set_time_limit(0);
		$carga = new Carga();
		$carga->gastos();
	}

	public function actionInformes(){
		set_time_limit(0);
		$carga = new Carga();
		$carga->informes();
	}
	


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
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
		if (isset(Yii::app()->user->id)) {
			if (isset($_POST['CambiarClaveForm'])) {
				$form->attributes = $_POST['CambiarClaveForm'];
				if ($form->validate()) {
					$new_password = Usuario::model()->findByPk(Yii::app()->user->id);
					if ($new_password->clave != sha1($form->clave)) {
						$form->addError('clave', "clave incorrecta");
					} else {
						if ($form->nueva == $form->repita) {
							$new_password->clave = sha1($form->nueva);
							if ($new_password->save()) {
								Yii::app()->user->setFlash(
									'profileMessage',
									"Clave cambiada correctamente."
								);
							} else {
								Yii::app()->user->setFlash(
									'profileMessage',
									"No se pudo cambiar la clave, inténtelo de nuevo más tarde."
								);
							}
							$this->refresh();
						} else {
							$form->addError('nueva', "claves nuevas no coinciden");
							$form->addError('repita', "claves nuevas no coinciden");
						}
					}
				}
			}
			$this->render('//site/cambiarClave', array('model' => $form));
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model = new ContactForm;
		if (isset($_POST['ContactForm'])) {
			$model->attributes = $_POST['ContactForm'];
			if ($model->validate()) {
				$headers = "From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
				Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact', array('model' => $model));
	}



	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login())
				$this->redirect(CController::createUrl("//site/index"));
		}
		// display the login form
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(CController::createUrl("//site/index"));
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
			array(
				'allow',
				'actions' => array('login','loginmovil', 'logout', 'error', 'index', 'gastos', 'informes', 'rindegastos','fix','fixmaquinas','fixcamiones','test','fixdupli','clean','repair'),
				'users' => array('*'),
			),
			array(
				'allow',
				'actions' => array('cambiarClave'),
				'users' => array('@'),
			),
			array(
				'allow',
				'actions' => array('configureRoles'),
				'roles' => array('administrador'),
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}
}
