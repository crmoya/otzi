<?php

class RCamionArrendadoController extends Controller
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
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('admin', 'delete', 'view', 'update'),
				'roles' => array('administrador'),
			),
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('admin2', 'update'),
				'roles' => array('operativo'),
			),
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('adminFilter', 'view', 'validar', 'verHistorial'),
				'roles' => array('gerencia'),
			),
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('unlock'),
				'roles' => array('administrador', 'operativo'),
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionVerHistorial($id)
	{
		$report = RCamionArrendado::model()->findByPk($id);
		if ($report != null) {
			$this->render('verHistorial', array('report' => $report));
		}
	}

	public function actionValidar()
	{
		$reports = $_POST['reports'];
		$rep_arr = explode(",", $reports);
		$ok = true;
		foreach ($rep_arr as $report_id) {
			if (is_numeric($report_id)) {
				$report = RCamionArrendado::model()->findByPk($report_id);
				if ($report != null) {
					if ($report->validado == 0) {
						$report->validado = 1;
					} else if ($report->validado == 1) {
						$report->validado = 2;
					}

					$report->validador_id = Yii::app()->user->id;
					$historial = new HistorialValidacionesCa();
					$historial->fecha = date("Y-m-d H:i");
					$historial->rCamionArrendado_id = $report->id;
					$historial->usuario_id = Yii::app()->user->id;
					if (!$report->save() || !$historial->save()) {
						$ok = false;
					}
				}
			}
		}
		if ($ok) {
			echo "OK";
		} else {
			//echo CHtml::errorSummary($report);
			//echo CHtml::errorSummary($historial);
			echo "ERROR: No se pudieron validar todos los reports, reintente por favor.";
		}
	}

	public function actionUnlock()
	{
		if (isset($_POST['admin1']) && isset($_POST['admin2']) && isset($_POST['aut1']) && isset($_POST['aut2']) && isset($_POST['report_id'])) {
			$user1 = Usuario::model()->findByAttributes(array('id' => $_POST['admin1'], 'clave' => sha1($_POST['aut1'])));
			$user2 = Usuario::model()->findByAttributes(array('id' => $_POST['admin2'], 'clave' => sha1($_POST['aut2'])));
			if ($user1 == null || $user2 == null) {
				echo "ERROR: Usuario(s) o Clave(s) incorrecta(s).";
			} else if ($user1->user == $user2->user) {
				echo "ERROR: Usuarios deben ser diferentes.";
			} else if ($user1->rol != 'administrador' || $user2->rol != 'administrador') {
				echo "ERROR: Ambos usuarios deben tener el rol de ADMINISTRADOR.";
			} else {
				$report = RCamionArrendado::model()->findByPk($_POST['report_id']);
				if ($report == null) {
					echo "ERROR: Report no reconocido.";
				} else {
					$report->validado = 0;
					$report->validador_id = null;
					$mod_cp = new ModCaAutorizadaPor();
					$mod_cp->fecha = date('Y-m-d H:i');
					$mod_cp->usuario_1 = $user1->id;
					$mod_cp->usuario_2 = $user2->id;
					$mod_cp->rCamionArrendado_id = $report->id;
					$historiales = HistorialValidacionesCa::model()->findAllByAttributes(array('rCamionArrendado_id' => $report->id), array('order' => 'fecha DESC'));
					if (count($historiales) > 0)
						$historial = $historiales[0];
					if ($historial != null) {
						$mod_cp->historial_validaciones_ca_id = $historial->id;
					}
					$report->save();
					$mod_cp->save();
					$informe = InformeRegExpCamionArrendado::model()->findByAttributes(array('id_reg' => $report->id));
					$informe->validador_id = null;
					$informe->validado = 0;
					$informe->save();
					echo "OK";
				}
			}
		} else {
			echo "ERROR: Debe ingresar todos los campos.";
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new RCamionArrendado;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['RCamionArrendado'])) {
			$model->attributes = $_POST['RCamionArrendado'];
			$model->observaciones_obra = $_POST['RCamionArrendado']['observaciones_obra'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		$model = $this->loadModel($id);
		$model->horas = number_format($model->horometro_final - $model->horometro_inicial,2,".","");


		if(isset($model->camiones)){
			if($model->camiones->odometro_en_millas){
				$model->kmInicial = number_format($model->kmInicial / Tools::FACTOR_KMS_MILLAS,2,'.','');
				$model->kmFinal = number_format($model->kmFinal / Tools::FACTOR_KMS_MILLAS,2,'.','');
			}
		}
		
		$viajes = ViajeCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
		$viajesT = Expedicionportiempoarr::model()->findAllByAttributes(array('rcamionarrendado_id' => $id));
		$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
		$compras = CompraRepuestoCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['RCamionArrendado'])) {
			$errores = "";
			if ($model->validado == 2 ) {
				return;
			}
			if ($model->validado == 0) {
				$model->attributes = $_POST['RCamionArrendado'];
				$model->observaciones_obra = $_POST['RCamionArrendado']['observaciones_obra'];
				$model->fecha = Tools::fixFecha($model->fecha);
				$model->usuario_id = Yii::app()->user->id;
				$model->iniPanne = $_POST['RCamionArrendado']['iniPanne'];
				$model->finPanne = $_POST['RCamionArrendado']['finPanne'];
				$model->panne = $_POST['RCamionArrendado']['panne'];
				$model->horometro_inicial = $_POST['RCamionArrendado']['horometro_inicial'];
				$model->horometro_final = $_POST['RCamionArrendado']['horometro_final'];

				$camion = CamionArrendado::model()->findByPk($_POST['RCamionArrendado']['camionArrendado_id']);
				if(isset($camion)){
					if($camion->odometro_en_millas){
						$model->kmInicial = number_format($model->kmInicial * Tools::FACTOR_KMS_MILLAS,2,".","");
						$model->kmFinal = number_format($model->kmFinal * Tools::FACTOR_KMS_MILLAS,2,".","");
					}
				}

				if ($model->panne == 1) {
					$iniPanne = str_replace(":", "", $_POST['RCamionArrendado']['iniPanne']);
					$finPanne = str_replace(":", "", $_POST['RCamionArrendado']['finPanne']);
					$minutos = $finPanne - $iniPanne;
					$horas = (int)($minutos / 100);
					$min = $minutos % 100;
					$model->minPanne = $horas * 60 + $min;
				} else {
					$model->minPanne = 0;
					$model->iniPanne = "08:00";
					$model->finPanne = "08:00";
				}
			}
			if ($model->validate()) {
				if ($model->save()) {


					//archivos del report
					$path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR . "camiones_arrendados";
					if(!is_dir($path)){
						mkdir($path);
					}
					$path = $path . DIRECTORY_SEPARATOR . $model->id;
					if(!is_dir($path)){
						mkdir($path);
					}

					$archivos=CUploadedFile::getInstancesByName('archivos');
					if(isset($archivos)){
						if(count($archivos) > 0){
							foreach($archivos as $archivo)
							{                                                       
								$archivo->saveAs($path . DIRECTORY_SEPARATOR . $archivo->name);                                              
							}  
						}
					}
					if($model->validado == 0){
						if(isset($_POST['eliminar'])){
							$eliminables = $_POST['eliminar'];
							if(isset($eliminables)){
								foreach($eliminables as $archivo => $status){
									if($status == 'on'){
										unlink($path . DIRECTORY_SEPARATOR . $archivo);
									}
								}
							}
						}
					}
					

					//end archivos del report


					$valid = true;
					if ($model->validado == 0) {
						foreach ($viajes as $viaje) {
							$valid = $viaje->validate() && $valid;
							$viaje->delete();
						}
						foreach ($viajesT as $viajeT) {
							$valid = $viajeT->validate() && $valid;
							$viajeT->delete();
						}
					}
					
					//if (Yii::app()->user->rol == "administrador") {
						foreach ($cargas as $carga) {
							$valid = $carga->validate() && $valid;
							$carga->delete();
						}
						foreach ($compras as $compra) {
							$valid = $compra->validate() && $valid;
							$compra->delete();
						}
					//}
					if ($valid) {
						if (isset($_POST['ViajeCamionArrendado']) && $model->validado == 0) {
							foreach ($_POST['ViajeCamionArrendado'] as $i => $viajeArr) {
								$viaje = new ViajeCamionArrendado();
								$viaje->faena_id = $viajeArr['faena_id'];
								$viaje->origendestino_faena_id = $viajeArr['origendestino_faena_id'];
								$viaje->kmRecorridos = $viajeArr['kmRecorridos'];
								$viaje->nVueltas = $viajeArr['nVueltas'];
								$viaje->rCamionArrendado_id = $model->id;
								$viaje->total = $viajeArr['total'];
								$viaje->totalTransportado = $viajeArr['totalTransportado'];
								$viaje->coeficiente = $viajeArr['coeficiente'];

								$valid = $valid && $viaje->validate();
								if ($valid) {
									$viaje->save();
								}
								else{
									foreach($viaje->errors as $error){
										$errores .= $error[0];
									}
								}
							}
						}

						if (isset($_POST['Expedicionportiempoarr']) && $model->validado == 0) {

							
							foreach ($_POST['Expedicionportiempoarr'] as $i => $viajeTArr) {								
								$viajeT = new Expedicionportiempoarr();
								$viajeT->unidadfaena_id = $viajeTArr['unidadfaena_id'];
								$viajeT->faena_id = $viajeTArr['faena_id'];
								$viajeT->rcamionarrendado_id = $model->id;
								$viajeT->total = $viajeTArr['total'];
								$viajeT->cantidad = $viajeTArr['cantidad'];
								$valid = $valid && $viajeT->validate();
								if ($valid) {
									$viajeT->save();
								}
								else{
									foreach($viajeT->errors as $error){
										$errores .= $error[0];
									}
								}
								
							}
						}

						if (isset($_POST['CargaCombCamionArrendado'])) {
							foreach ($_POST['CargaCombCamionArrendado'] as $i => $cargaArr) {
								$carga = null;
								if (isset($cargaArr['id'])) $id = $cargaArr['id'];
								//if ($id > 0 && Yii::app()->user->rol == "operativo") {
								//	continue;
								//} else {
									$carga = new CargaCombCamionArrendado();
								//}
								$carga->factura = $cargaArr['factura'];
								$carga->faena_id = $cargaArr['faena_id'];
								$carga->guia = $cargaArr['guia'];
								$carga->kmCarguio = $cargaArr['kmCarguio'];
								$carga->petroleoLts = $cargaArr['petroleoLts'];
								$carga->precioUnitario = $cargaArr['precioUnitario'];
								$carga->rCamionArrendado_id = $model->id;
								$carga->supervisorCombustible_id = $cargaArr['supervisorCombustible_id'];
								$carga->tipoCombustible_id = $cargaArr['tipoCombustible_id'];
								$carga->valorTotal = $cargaArr['valorTotal'];
								$carga->numero = $cargaArr['numero'];
								$carga->nombre = $cargaArr['nombre'];
								$carga->rut_rinde = $cargaArr['rut_rinde'];
								Rendidor::model()->ingresaRendidor($carga->rut_rinde, $carga->nombre);
								$carga->fechaRendicion = $cargaArr['fechaRendicion'];
								$carga->observaciones = $cargaArr['observaciones'];
								$carga->nombre_proveedor = $cargaArr['nombre_proveedor'];
								$carga->rut_proveedor = $cargaArr['rut_proveedor'];
								Proveedor::model()->ingresaProveedor($carga->rut_proveedor, $carga->nombre_proveedor);
								$carga->tipo_documento = $cargaArr['tipo_documento'];
								$valid = $valid && $carga->validate();
								if ($valid) {
									$carga->save();
								}
								else{
									foreach($carga->errors as $error){
										$errores .= $error[0];
									}
								}
							}
						}
						if (isset($_POST['CompraRepuestoCamionArrendado'])) {
							foreach ($_POST['CompraRepuestoCamionArrendado'] as $i => $compraArr) {
								$compra = null;
								if (isset($compraArr['id'])) $id = $compraArr['id'];
								//if ($id > 0 && Yii::app()->user->rol == "operativo") {
								//	continue;
								//} else {
									$compra = new CompraRepuestoCamionArrendado();
								//}
								$compra->factura = $compraArr['factura'];
								$compra->guia = $compraArr['guia'];
								$compra->montoNeto = $compraArr['montoNeto'];
								$compra->rCamionArrendado_id = $model->id;
								$compra->repuesto = $compraArr['repuesto'];
								$compra->cantidad = $compraArr['cantidad'];
								$compra->unidad = $compraArr['unidad'];
								$compra->faena_id = $compraArr['faena_id'];
								$compra->numero = $compraArr['numero'];
								$compra->nombre = $compraArr['nombre'];
								$compra->rut_rinde = $compraArr['rut_rinde'];
								Rendidor::model()->ingresaRendidor($compra->rut_rinde, $compra->nombre);
								$compra->tipo_documento = $compraArr['tipo_documento'];
								$compra->fechaRendicion = $compraArr['fechaRendicion'];
								$compra->observaciones = $compraArr['observaciones'];
								$compra->nombre_proveedor = $compraArr['nombre_proveedor'];
								$compra->rut_proveedor = $compraArr['rut_proveedor'];
								Proveedor::model()->ingresaProveedor($compra->rut_proveedor, $compra->nombre_proveedor);
								$compra->cuenta = $compraArr['cuenta'];
								$compra->rindegastos = $compraArr['rindegastos'];
								$valid = $valid && $compra->validate();
								if ($valid) {
									$compra->save();
								}
								else{
									foreach($compra->errors as $error){
										$errores .= $error[0];
									}
								}
							}
						}
						if ($valid) {
							$transaction->commit();
							Yii::app()->user->setFlash('camionesMessage', "Formulario Guardado con éxito.");
							$this->refresh();
						} else {
							Yii::app()->user->setFlash('camionesError', "Error. No se pudo actualizar el formulario, inténtelo de nuevo. " . $errores);
							$transaction->rollback();
						}
					} else {
						Yii::app()->user->setFlash('camionesError', "Error. No se pudo actualizar el formulario, inténtelo de nuevo: ");
						$transaction->rollback();
					}
				} else {
					Yii::app()->user->setFlash('camionesError', "Error. No se pudo actualizar el formulario, inténtelo de nuevo: ");
					$transaction->rollback();					
				}
			} else {
				Yii::app()->user->setFlash('camionesError', "Existen errores en el formulario, por favor vuelva a intentarlo: ");
				$transaction->rollback();
			}
			
		}

		$camion_id = $model->camionArrendado_id;
		$camion = CamionArrendado::model()->findByPk($camion_id);
		$capacidad = $camion->capacidad;

		if (Yii::app()->user->rol == "administrador") {
			$this->render('camionesArrendados', array(
				'model' => $model,
				'viajes' => $viajes,
				'viajesT' => $viajesT,
				'cargas' => $cargas,
				'compras' => $compras,
				'capacidad' => $capacidad,
			));
		}
		if (Yii::app()->user->rol == "operativo") {
			$this->render('camionesArrendadosOp', array(
				'model' => $model,
				'viajes' => $viajes,
				'viajesT' => $viajesT,
				'cargas' => $cargas,
				'compras' => $compras,
				'capacidad' => $capacidad,
			));
		}
	}

	public function actionView($id)
	{
		$model = $this->loadModel($id);

		$viajes = ViajeCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
		$viajesT = Expedicionportiempoarr::model()->findAllByAttributes(array('rcamionarrendado_id' => $id));
		$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
		$compras = CompraRepuestoCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
		$model->fecha = Tools::backFecha($model->fecha);

		$recorridos = number_format($model->kmFinal - $model->kmInicial, 2);
		$recorridos = str_replace(",", "", $recorridos);
		$camion_id = $model->camionArrendado_id;
		$camion = CamionArrendado::model()->findByPk($camion_id);

		$chofer_id = $model->chofer_id;
		$chofer = Chofer::model()->findByPk($chofer_id);
		$this->render('view', array(
			'model' => $model,
			'viajes' => $viajes,
			'viajesT' => $viajesT,
			'cargas' => $cargas,
			'compras' => $compras,
			'camion' => $camion,
			'chofer' => $chofer,
			'recorridos' => $recorridos,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			$viajes = ViajeCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
			$cargas = CargaCombCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
			$compras = CompraRepuestoCamionArrendado::model()->findAllByAttributes(array('rCamionArrendado_id' => $id));
			foreach ($viajes as $viaje) {
				$viaje->delete();
			}
			foreach ($cargas as $carga) {
				$carga->delete();
			}
			foreach ($compras as $compra) {
				$compra->delete();
			}
			Expedicionportiempoarr::model()->deleteAllByAttributes(['rcamionarrendado_id'=> $id]);
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		} else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('RCamionArrendado');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new RCamionArrendado('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RCamionArrendado']))
			$model->attributes = $_GET['RCamionArrendado'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin2()
	{
		$model = new RCamionArrendado('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RCamionArrendado']))
			$model->attributes = $_GET['RCamionArrendado'];

		$this->render('admin2', array(
			'model' => $model,
		));
	}

	/**
	 * Manages all models with filters.
	 */
	public function actionAdminFilter()
	{
		$model = new RCamionArrendado('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RCamionArrendado']))
			$model->attributes = $_GET['RCamionArrendado'];

		$this->render('adminFilter', array(
			'model' => $model,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = RCamionArrendado::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'rcamion-arrendado-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
