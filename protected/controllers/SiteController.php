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


	public function actionConfigureRoles()
	{

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

/*
	public function actionFixcamiones(){

		set_time_limit(0);

		$viajesCamionPropio = ViajeCamionPropio::model()->findAll();
		foreach($viajesCamionPropio as $viaje){
			$total = $viaje->total;
			$totalTransportado = $viaje->totalTransportado;
			$kms = $viaje->kmRecorridos;
			if($totalTransportado == 0 || $kms == 0){
				$pu = 0;
			}
			else{
				$pu = $total / ($totalTransportado * $kms);
			}
			$faena_id = $viaje->faena_id;
			$camionpropio_id = $viaje->rCamionPropio->camiones->id;
			$unidad = Unidadfaena::UNIDAD_DIAS;
			$produccion_minima = $viaje->rCamionPropio->camiones->produccionMinima;
			$unidadFaena = UnidadFaena::model()->findByAttributes(['faena_id'=>$faena_id,'camionpropio_id'=>$camionpropio_id]);
			if($unidadFaena == null){
				$unidadFaena = new Unidadfaena();
				$unidadFaena->faena_id = $faena_id;
				$unidadFaena->camionpropio_id = $camionpropio_id;
			}			
			$unidadFaena->unidad = $unidad;
			$unidadFaena->pu = $pu;
			$unidadFaena->produccion_minima = $produccion_minima;
			$unidadFaena->save();
		}

		$viajesCamionArrendado = ViajeCamionArrendado::model()->findAll();
		foreach($viajesCamionArrendado as $viaje){
			$total = $viaje->total;
			$totalTransportado = $viaje->totalTransportado;
			$kms = $viaje->kmRecorridos;
			if($totalTransportado == 0 || $kms == 0){
				$pu = 0;
			}
			else{
				$pu = $total / ($totalTransportado * $kms);
			}
			$faena_id = $viaje->faena_id;
			$camionarrendado_id = $viaje->rCamionArrendado->camiones->id;
			$unidadFaena = UnidadFaena::model()->findByAttributes(['faena_id'=>$faena_id,'camionarrendado_id'=>$camionarrendado_id]);
			if($unidadFaena == null){
				$unidadFaena = new Unidadfaena();
			}
			$unidad = Unidadfaena::UNIDAD_DIAS;
			$produccion_minima = $viaje->rCamionArrendado->camiones->produccionMinima;			
			$unidadFaena->unidad = $unidad;
			$unidadFaena->pu = $pu;
			$unidadFaena->faena_id = $faena_id;
			$unidadFaena->camionarrendado_id = $camionarrendado_id;
			$unidadFaena->produccion_minima = $produccion_minima;
			$unidadFaena->save();
		}
	}
	

	
	public function actionFixmaquinas(){

		set_time_limit(0);

		$unidadesFaenaEquipo = UnidadfaenaEquipo::model()->findAll();
		foreach($unidadesFaenaEquipo as $unidad){
			if(!isset($unidad->horas_minimas)){
				if(isset($unidad->equipoarrendado)){
					$horas_minimas = (double)$unidad->equipoarrendado->horasMin;
					$unidad->horas_minimas = $horas_minimas;
					$unidad->save();
				} else if(isset($unidad->equipopropio)){
					$horas_minimas = (double)$unidad->equipopropio->horasMin;
					$unidad->horas_minimas = $horas_minimas;
					$unidad->save();
				}
			}
		}
	}

	
	public function actionFixmaquinas(){

		set_time_limit(0);

		$rEquiposPropios = REquipoPropio::model()->findAll();
		foreach($rEquiposPropios as $rEquipoPropio){
			//agregar unidadfaena_equipo faltantes
			$uFaenaEquipo = UnidadfaenaEquipo::model()->findByAttributes(['faena_id'=>$rEquipoPropio->faena_id,'equipopropio_id'=>$rEquipoPropio->equipoPropio_id,'unidad'=>UnidadfaenaEquipo::UNIDAD_HORAS]);
			if(!isset($uFaenaEquipo)){
				$uFaenaEquipo = new UnidadfaenaEquipo();
				$uFaenaEquipo->unidad = UnidadfaenaEquipo::UNIDAD_HORAS;
				if(isset($rEquipoPropio->equipos)){
					$uFaenaEquipo->pu = $rEquipoPropio->equipos->precioUnitario;
				}
				$uFaenaEquipo->faena_id = $rEquipoPropio->faena_id;
				$uFaenaEquipo->equipopropio_id = $rEquipoPropio->equipoPropio_id;
				$uFaenaEquipo->save();
			}

			//agregar expediciones por tiempo faltantes
			$expedicion = new Expedicionportiempoeq();
			$expedicion->cantidad = $rEquipoPropio->horas;
			$expedicion->total = $uFaenaEquipo->pu * $rEquipoPropio->horas;
			$expedicion->faena_id = $uFaenaEquipo->faena_id;
			$expedicion->requipopropio_id = $rEquipoPropio->id;
			$expedicion->unidadfaena_equipo_id = $uFaenaEquipo->id;
			$expedicion->agregada = 1;
			$expedicion->save();
		}

		$rEquiposArrendados = REquipoArrendado::model()->findAll();
		foreach($rEquiposArrendados as $rEquipoArrendado){
			$uFaenaEquipo = UnidadfaenaEquipo::model()->findByAttributes(['faena_id'=>$rEquipoArrendado->faena_id,'equipoarrendado_id'=>$rEquipoArrendado->equipoArrendado_id,'unidad'=>UnidadfaenaEquipo::UNIDAD_HORAS]);
			if(!isset($uFaenaEquipo)){
				$uFaenaEquipo = new UnidadfaenaEquipo();
				$uFaenaEquipo->unidad = UnidadfaenaEquipo::UNIDAD_HORAS;
				if(isset($rEquipoArrendado->equipos)){
					$uFaenaEquipo->pu = $rEquipoArrendado->equipos->precioUnitario;
				}
				$uFaenaEquipo->faena_id = $rEquipoArrendado->faena_id;
				$uFaenaEquipo->equipoarrendado_id = $rEquipoArrendado->equipoArrendado_id;
				$uFaenaEquipo->save();
			}
			
			//agregar expediciones por tiempo faltantes
			$expedicion = new Expedicionportiempoeqarr();
			$expedicion->cantidad = $rEquipoArrendado->horas;
			$expedicion->total = $uFaenaEquipo->pu * $rEquipoArrendado->horas;
			$expedicion->faena_id = $uFaenaEquipo->faena_id;
			$expedicion->requipoarrendado_id = $rEquipoArrendado->id;
			$expedicion->unidadfaena_equipo_id = $uFaenaEquipo->id;
			$expedicion->agregada = 1;
			$expedicion->save();
		}



	}
	*/

	/*
	public function actionFix(){
		$rEquiposPropios = REquipoPropio::model()->findAll();
		foreach($rEquiposPropios as $rEquipoPropio){
			$uFaenaEquipo = UnidadfaenaEquipo::model()->findByAttributes(['faena_id'=>$rEquipoPropio->faena_id,'equipopropio_id'=>$rEquipoPropio->equipoPropio_id,'unidad'=>UnidadfaenaEquipo::UNIDAD_HORAS]);
			if(!isset($uFaenaEquipo)){
				$uFaenaEquipo = new UnidadfaenaEquipo();
				$uFaenaEquipo->unidad = UnidadfaenaEquipo::UNIDAD_HORAS;
				if(isset($rEquipoPropio->equipos)){
					$uFaenaEquipo->pu = $rEquipoPropio->equipos->precioUnitario;
				}
				$uFaenaEquipo->faena_id = $rEquipoPropio->faena_id;
				$uFaenaEquipo->equipopropio_id = $rEquipoPropio->equipoPropio_id;
				$uFaenaEquipo->save();
			}
		}
		$rEquiposArrendados = REquipoArrendado::model()->findAll();
		foreach($rEquiposArrendados as $rEquipoArrendado){
			$uFaenaEquipo = UnidadfaenaEquipo::model()->findByAttributes(['faena_id'=>$rEquipoArrendado->faena_id,'equipoarrendado_id'=>$rEquipoArrendado->equipoArrendado_id,'unidad'=>UnidadfaenaEquipo::UNIDAD_HORAS]);
			if(!isset($uFaenaEquipo)){
				$uFaenaEquipo = new UnidadfaenaEquipo();
				$uFaenaEquipo->unidad = UnidadfaenaEquipo::UNIDAD_HORAS;
				if(isset($rEquipoArrendado->equipos)){
					$uFaenaEquipo->pu = $rEquipoArrendado->equipos->precioUnitario;
				}
				$uFaenaEquipo->faena_id = $rEquipoArrendado->faena_id;
				$uFaenaEquipo->equipoarrendado_id = $rEquipoArrendado->equipoArrendado_id;
				$uFaenaEquipo->save();
			}
		}
	}
*/


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
				'actions' => array('login', 'logout', 'error', 'index', 'gastos', 'informes', 'rindegastos','fix','fixmaquinas','fixcamiones','test'),
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
