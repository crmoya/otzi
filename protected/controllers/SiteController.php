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

	
	
	public function actionCarga()
	{
		set_time_limit(0);
		try
		{

			//LO PRIMERO ES TRAER LOS INFORMES, PUES NO TIENEN DEPENDENCIAS

			
			
			//TRAER INFORMES
			$limite = 1;
			$primero = true;
			$errores = [];
			$informes = 0;
			
			
			for($i = 1; $i <= $limite; $i++){
				$resultado = Tools::getReports($i);
				if($primero){
					$limite = (int)$resultado->Records->Pages;
					$primero = false;
				}
				$reports = $resultado->ExpenseReports;
				foreach($reports as $report){
					$informe = InformeGasto::model()->findByPk($report->Id);
					if(isset($informe)){
						continue;
					}
					$informe = new InformeGasto();
					$informe->id = $report->Id;
					$informe->titulo = $report->Title;
					$informe->numero = $report->ReportNumber;
					$informe->fecha_envio = $report->SendDate;
					$informe->fecha_cierre = $report->CloseDate;
					$informe->nombre_empleado = $report->EmployeeName;
					$informe->rut_empleado = $report->EmployeeIdentification;
					$informe->aprobado_por = $report->ApproverName;
					$informe->politica_id = $report->PolicyId;
					$informe->politica = $report->PolicyName;
					$informe->estado = $report->Status;
					$informe->total = $report->ReportTotal;
					$informe->total_aprobado = $report->ReportTotalApproved;
					$informe->nro_gastos = $report->NbrExpenses;
					$informe->nro_gastos_aprobados = $report->NbrApprovedExpenses;
					$informe->nro_gastos_rechazados = $report->NbrRejectedExpenses;
					if(!$informe->save()){
						$errores[] = $informe->errors;
					}
					else{
						$informes++;
					}
				}
						
			}

			//END TRAER INFORMES

			
			
			//AHORA TRAIGO LOS
			//GASTOS Y SUS DERIVADOS

			$no_aprobados = 0;
			$limite = 1;
			$primero = true;
			for($i = 1; $i <= $limite; $i++){
				$resultado = Tools::getExpenses($i);
				if($primero){
					$limite = (int)$resultado->Records->Pages;
					$primero = false;
				}
				$expenses = $resultado->Expenses;
				foreach($expenses as $expense){
					//solo traer gastos aprobados
					if((int)$expense->Status != 1){
						$no_aprobados++;
						continue;
					}

					$gasto = Gasto::model()->findByPk($expense->Id);
					if(isset($gasto)){
						continue;
					}

					$gasto = new Gasto();
					$gasto->id = $expense->Id;
					$gasto->status = $expense->Status;
					$gasto->supplier = $expense->Supplier;
					$gasto->issue_date = $expense->IssueDate;
					$gasto->net = $expense->Net;
					$gasto->total = $expense->Total;
					$gasto->category = $expense->Category;
					$gasto->category_group = $expense->CategoryGroup;
					$gasto->note = $expense->Note;
					$gasto->expense_policy_id = (int)$expense->ExpensePolicyId;
					$gasto->report_id = (int)$expense->ReportId;
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
								else{
									if(strtolower(trim($extra_gasto->name)) == "10% impto. retenido"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->retenido = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "cantidad"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->cantidad = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "centro de costo / faena"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->centro_costo_faena = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "departamento"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->departamento = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "faena"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->faena = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(trim($extra_gasto->name) == "Impuesto específico"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->impuesto_especifico = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "iva"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->iva = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(trim($extra_gasto->name) == "Km.Carguío"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->km_carguio = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "litros combustible"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->litros_combustible = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "monto neto"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->monto_neto = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "nombre quien rinde"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->nombre_quien_rinde = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(trim($extra_gasto->name) == "Número de Documento"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->nro_documento = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(trim($extra_gasto->name) == "Período de Planilla"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->periodo_planilla = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "rut proveedor"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->rut_proveedor = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "supervisor de combustible"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->supervisor_combustible = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "tipo de documento"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->tipo_documento = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "unidad"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->unidad = $extra_gasto->value;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(strtolower(trim($extra_gasto->name)) == "vehiculo o equipo"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$valor = $extra_gasto->value;
										if($extra_gasto->value == "Taller (vehículo virtual para registrar tosdos los gastos excepto combustibles que son de Taller y que no pueden cargarse directamente a ningún equipo o vehic.)"){
											$valor = "Taller (virtual no comb.)";
										}
										$gasto_completa->vehiculo_equipo = $valor;
										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
									}
									if(trim($extra_gasto->name) == "Vehículo Oficina Central"){
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
										if(!isset($gasto_completa)){
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->vehiculo_oficina_central = $extra_gasto->value;

										if(!$gasto_completa->save()){
											$errores[] = $gasto_completa->errors;
										}
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
					//END GASTOS Y SUS DERIVADOS
				}
				
				
			}

			//ELIMINO LOS EXTRAS PUES YA NO SIRVEN
			ExtraGasto::model()->deleteAll();

			if(count($errores) > 0){
				echo "<pre>";
				print_r($errores);
				echo "</pre>";
			}
			
		}
		catch(Exception $e)
		{
			echo "Excepción: ".$e;
		}
		
		
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
				'actions'=>array('login','logout','error','index','carga'),
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