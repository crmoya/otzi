<?php

class RCamionPropioController extends Controller
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
		$report = RCamionPropio::model()->findByPk($id);
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
				$report = RCamionPropio::model()->findByPk($report_id);
				$informe = InformeRegExpCamionPropio::model()->findByAttributes(array('id_reg' => $report_id));
				if ($report != null && $informe != null) {
					if ($report->validado == 0) {
						$report->validado = 1;
					} else if ($report->validado == 1) {
						$report->validado = 2;
					}
					if ($informe->validado == 0) {
						$informe->validado = 1;
					} else if ($informe->validado == 1) {
						$informe->validado = 2;
					}

					$report->validador_id = Yii::app()->user->id;
					$informe->validador_id = Yii::app()->user->id;
					$historial = new HistorialValidacionesCp();
					$historial->fecha = date("Y-m-d H:i");
					$historial->rCamionPropio_id = $report->id;
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
				$report = RCamionPropio::model()->findByPk($_POST['report_id']);
				if ($report == null) {
					echo "ERROR: Report no reconocido.";
				} else {
					$report->validado = 0;
					$report->validador_id = null;
					$mod_cp = new ModCpAutorizadaPor();
					$mod_cp->fecha = date('Y-m-d H:i');
					$mod_cp->usuario_1 = $user1->id;
					$mod_cp->usuario_2 = $user2->id;
					$mod_cp->rCamionPropio_id = $report->id;
					$historiales = HistorialValidacionesCp::model()->findAllByAttributes(array('rCamionPropio_id' => $report->id), array('order' => 'fecha DESC'));
					if (count($historiales) > 0)
						$historial = $historiales[0];
					if ($historial != null) {
						$mod_cp->historial_validaciones_cp_id = $historial->id;
					}
					$report->save();
					$mod_cp->save();
					$informe = InformeRegExpCamionPropio::model()->findByAttributes(array('id_reg' => $report->id));
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$viajes = ViajeCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
		$cargas = CargaCombCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
		$compras = CompraRepuestoCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
		$model->fecha = Tools::backFecha($model->fecha);

		$camion_id = $model->camionPropio_id;
		$camion = CamionPropio::model()->findByPk($camion_id);

		$recorridos = number_format($model->kmFinal - $model->kmInicial, 2);
		$recorridos = str_replace(",", "", $recorridos);
		$chofer_id = $model->chofer_id;
		$chofer = Chofer::model()->findByPk($chofer_id);
		$this->render('view', array(
			'model' => $model,
			'viajes' => $viajes,
			'cargas' => $cargas,
			'compras' => $compras,
			'camion' => $camion,
			'chofer' => $chofer,
			'recorridos' => $recorridos,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new RCamionPropio;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['RCamionPropio'])) {
			$model->attributes = $_POST['RCamionPropio'];
			$model->observaciones_obra = $_POST['RCamionPropio']['observaciones_obra'];
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

		$viajes = ViajeCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
		$viajesT = Expedicionportiempo::model()->findAllByAttributes(array('rcamionpropio_id' => $id));
		$cargas = CargaCombCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
		$compras = CompraRepuestoCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['RCamionPropio'])) {
			$model->attributes = $_POST['RCamionPropio'];
			$model->observaciones_obra = $_POST['RCamionPropio']['observaciones_obra'];
			if ($model->validado == 2) {
				return;
			}
			if ($model->validado == 0) {
				$model->fecha = Tools::fixFecha($model->fecha);
				$model->usuario_id = Yii::app()->user->id;
				$model->iniPanne = $_POST['RCamionPropio']['iniPanne'];
				$model->finPanne = $_POST['RCamionPropio']['finPanne'];
				$model->panne = $_POST['RCamionPropio']['panne'];
				$model->horometro_inicial = $_POST['RCamionPropio']['horometro_inicial'];
				$model->horometro_final = $_POST['RCamionPropio']['horometro_final'];
				

				$camion = CamionPropio::model()->findByPk($_POST['RCamionPropio']['camionPropio_id']);
				if(isset($camion)){
					if($camion->odometro_en_millas){
						$model->kmInicial = $model->kmInicial * Tools::FACTOR_KMS_MILLAS;
						$model->kmFinal = $model->kmFinal * Tools::FACTOR_KMS_MILLAS;
					}
				}


				if ($model->panne == 1) {
					$iniPanne = str_replace(":", "", $_POST['RCamionPropio']['iniPanne']);
					$finPanne = str_replace(":", "", $_POST['RCamionPropio']['finPanne']);
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
					$path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'archivos' . DIRECTORY_SEPARATOR . "camiones_propios";
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
					if (Yii::app()->user->rol == "administrador") {
						foreach ($cargas as $carga) {
							$valid = $carga->validate() && $valid;
							$carga->delete();
						}
						foreach ($compras as $compra) {
							$valid = $compra->validate() && $valid;
							$compra->delete();
						}
					}
					if ($valid) {
						if (isset($_POST['ViajeCamionPropio']) && $model->validado == 0) {
							foreach ($_POST['ViajeCamionPropio'] as $i => $viajeArr) {
								$viaje = new ViajeCamionPropio();
								$viaje->faena_id = $viajeArr['faena_id'];
								$viaje->origendestino_faena_id = $viajeArr['origendestino_faena_id'];
								$viaje->kmRecorridos = $viajeArr['kmRecorridos'];
								$viaje->nVueltas = $viajeArr['nVueltas'];
								$viaje->rCamionPropio_id = $model->id;
								$viaje->total = $viajeArr['total'];
								$viaje->totalTransportado = $viajeArr['totalTransportado'];
								$viaje->coeficiente = $viajeArr['coeficiente'];

								$valid = $valid && $viaje->validate();
								if ($valid) {
									$viaje->save();
								}
							}
						}

						if (isset($_POST['Expedicionportiempo']) && $model->validado == 0) {
							foreach ($_POST['Expedicionportiempo'] as $i => $viajeTArr) {								
								$viajeT = new Expedicionportiempo();
								$viajeT->unidadfaena_id = $viajeTArr['unidadfaena_id'];
								$viajeT->faena_id = $viajeTArr['faena_id'];
								$viajeT->rcamionpropio_id = $model->id;
								$viajeT->total = $viajeTArr['total'];
								$viajeT->cantidad = $viajeTArr['cantidad'];
								$valid = $valid && $viajeT->validate();
								if ($valid) {
									$viajeT->save();
								}
							}
						}
						//if(Yii::app()->user->rol == "administrador"){
						if (isset($_POST['CargaCombCamionPropio'])) {
							foreach ($_POST['CargaCombCamionPropio'] as $i => $cargaArr) {
								$carga = null;
								$id = -1;
								if (isset($cargaArr['id'])) $id = $cargaArr['id'];
								if ($id > 0 && Yii::app()->user->rol == "operativo") {
									continue;
								} else {
									$carga = new CargaCombCamionPropio();
								}
								$carga->factura = $cargaArr['factura'];
								$carga->faena_id = $cargaArr['faena_id'];
								$carga->guia = $cargaArr['guia'];
								$carga->kmCarguio = $cargaArr['kmCarguio'];
								$carga->petroleoLts = $cargaArr['petroleoLts'];
								$carga->precioUnitario = $cargaArr['precioUnitario'];
								$carga->rCamionPropio_id = $model->id;
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
							}
						}
						if (isset($_POST['CompraRepuestoCamionPropio'])) {
							foreach ($_POST['CompraRepuestoCamionPropio'] as $i => $compraArr) {
								$compra = null;
								$id = -1;
								if (isset($compraArr['id'])) $id = $compraArr['id'];
								if ($id > 0 && Yii::app()->user->rol == "operativo") {
									continue;
								} else {
									$compra = new CompraRepuestoCamionPropio();
								}
								$compra->factura = $compraArr['factura'];
								$compra->guia = $compraArr['guia'];
								$compra->montoNeto = $compraArr['montoNeto'];
								$compra->rCamionPropio_id = $model->id;
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
								$valid = $valid && $compra->validate();
								if ($valid) {
									$compra->save();
								}
							}
						}
						if ($valid) {
							$transaction->commit();
							Yii::app()->user->setFlash('camionesMessage', "Formulario Guardado con éxito.");
							
						} else {
							Yii::app()->user->setFlash('camionesError', "Error. No se pudo actualizar el formulario, inténtelo de nuevo. " . $model->errors);
							$transaction->rollback();
						}
					} else {
						Yii::app()->user->setFlash('camionesError', "Error. No se pudo actualizar el formulario, inténtelo de nuevo. " . $model->errors);
						$transaction->rollback();
					}
				} else {
					Yii::app()->user->setFlash('camionesError', "Error. No se pudo actualizar el formulario, inténtelo de nuevo. " . $model->errors);
					$transaction->rollback();
				}
			} else {
				Yii::app()->user->setFlash('camionesError', "Existen errores en el formulario, por favor vuelva a intentarlo. " . $model->errors);
				$transaction->rollback();
			}
			$this->refresh();
		}

		$camion_id = $model->camionPropio_id;
		$camion = CamionPropio::model()->findByPk($camion_id);
		$codigo = $camion->codigo;
		$capacidad = $camion->capacidad;

		if (Yii::app()->user->rol == "administrador") {
			$this->render('camionesPropios', array(
				'model' => $model,
				'viajes' => $viajes,
				'viajesT' => $viajesT,
				'cargas' => $cargas,
				'compras' => $compras,
				'capacidad' => $capacidad,
				'codigo' => $codigo,
			));
		}
		if (Yii::app()->user->rol == "operativo") {
			$this->render('camionesPropiosOp', array(
				'model' => $model,
				'viajes' => $viajes,
				'viajesT' => $viajesT,
				'cargas' => $cargas,
				'compras' => $compras,
				'capacidad' => $capacidad,
				'codigo' => $codigo,
			));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
			$viajes = ViajeCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
			$cargas = CargaCombCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
			$compras = CompraRepuestoCamionPropio::model()->findAllByAttributes(array('rCamionPropio_id' => $id));
			foreach ($viajes as $viaje) {
				$viaje->delete();
			}
			foreach ($cargas as $carga) {
				$carga->delete();
			}
			foreach ($compras as $compra) {
				$compra->delete();
			}
			Expedicionportiempo::model()->deleteAllByAttributes(['rcamionpropio_id'=> $id]);
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
		$dataProvider = new CActiveDataProvider('RCamionPropio');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new RCamionPropio('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RCamionPropio']))
			$model->attributes = $_GET['RCamionPropio'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin2()
	{
		$model = new RCamionPropio('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RCamionPropio']))
			$model->attributes = $_GET['RCamionPropio'];

		$this->render('admin2', array(
			'model' => $model,
		));
	}

	/**
	 * Manages all models with filters.
	 */
	public function actionAdminFilter()
	{
		$model = new RCamionPropio('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RCamionPropio']))
			$model->attributes = $_GET['RCamionPropio'];

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
		$model = RCamionPropio::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'rcamion-propio-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
