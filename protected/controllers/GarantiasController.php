<?php

class GarantiasController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',
				'actions'=>array('adminGarantias','agregar'),
				'roles'=>array('operador'),
			),
			array('allow',
				'actions'=>array('admin','view','index'),
				'roles'=>array('administrador'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Garantias;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Garantias']))
		{
			$model->attributes=$_POST['Garantias'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
				
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Garantias']))
		{
			$model->attributes=$_POST['Garantias'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Garantias');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Selección de contrato para agregar garantía.
	 */
	public function actionAdminGarantias()
	{
		$model=new ContratosDeUsuario('search');
		$model->unsetAttributes();
		if(isset($_GET['ContratosDeUsuario']))
			$model->attributes=$_GET['ContratosDeUsuario'];
		$this->render('adminGarantias',array(
				'model'=>$model,
		));
		
	}
	
	/**
	 * Agrega garantía a contrato seleccionado
	 * @param unknown $id
	 */
	public function actionAgregar($id){
		$contrato = Contratos::model()->findByPk($id);
		if($contrato->estados_contratos_id != Contratos::ESTADO_ADJUDICADO){
			Yii::app()->user->setFlash('adminError',"Contrato no se puede editar, pues no está adjudicado.");
			CController::forward('contratos/adminAdjudicados');
			Yii::app()->end();
		}
	
		//revisar que el usuario tenga permisos sobre el contrato
		$contratos_de_usuario = UsuariosContratos::model()->findAllByAttributes(array('usuarios_id'=>Yii::app()->user->id,'contratos_id'=>$id));
		if(count($contratos_de_usuario)==0){
			Yii::app()->user->setFlash('adminError',"Usted no posee permisos sobre este contrato, no se puede agregar garantía.");
			CController::forward('contratos/adminAdjudicados');
			Yii::app()->end();
		}

		// obtener garantías asociada al contrato
		$garantias_asociadas = $contrato->getGarantias();
		$garantia = new Garantias();
		
		$objetos = ObjetosGarantias::model()->findAll();
		$objetos_garantias = array();
		foreach($objetos as $i=>$objeto){
			$objetos_garantias[$i]=$objeto->descripcion;
		}
		
		$tipos = TiposGarantias::model()->findAll();
		$tipos_garantias = array();
		foreach($tipos as $i=>$tipo){
			$tipos_garantias[$i]=$tipo->nombre;
		}
		
		if(isset($_POST['Garantias']) && isset($_POST['objeto_garantia']) && isset($_POST['tipo_garantia'])){
			$garantia->attributes = $_POST['Garantias'];
			$garantia->fecha_vencimiento=Tools::fixFecha($garantia->fecha_vencimiento);

			//para hacer la transformación por el autocomplete
			$objetoGarantia = ObjetosGarantias::model()->findByAttributes(array('descripcion'=>$_POST['objeto_garantia']));
			if($objetoGarantia != null){
				$garantia->objetos_garantias_id = $objetoGarantia->id;
			}else{
				$objeto_new = new ObjetosGarantias();
				$objeto_new->descripcion = $_POST['objeto_garantia'];
				if($objeto_new->validate()){
					$objeto_new->save();
					$garantia->objetos_garantias_id = $objeto_new->id;
				}else{
					Yii::app()->user->setFlash('garantiasError',"No se pudo agregar Garantía: Objeto Garantía inválido. Reintente");
					$this->refresh();
				}
			}
			
			$tipoGarantia = TiposGarantias::model()->findByAttributes(array('nombre'=>$_POST['tipo_garantia']));
			if($tipoGarantia != null){
				$garantia->tipos_garantias_id = $tipoGarantia->id;
			}else{
				$tipo_new = new TiposGarantias();
				$tipo_new->nombre = $_POST['tipo_garantia'];
				if($tipo_new->validate()){
					$tipo_new->save();
					$garantia->tipos_garantias_id = $tipo_new->id;
				}else{
					Yii::app()->user->setFlash('garantiasError',"No se pudo agregar Garantía: Tipo Garantía inválido. Reintente");
					$this->refresh();
				}
			}
			
			$garantia->contratos_id = $id;
			$contrato->modificador_id = Yii::app()->user->id;
			$garantia->creador_id = Yii::app()->user->id;
			$garantia->modificador_id = Yii::app()->user->id;
			$garantia->monto = Tools::fixPlataDecimales($garantia->monto);
			// Inicialmente garantías siempre son activas y no existe fecha devolución (se iguala a fecha de vencimiento)
			$garantia->fecha_devolucion = null;
			$garantia->estado_garantia = true;
			$garantia->observacion = Tools::sacaPorcentaje($garantia->observacion);
			
			if($garantia->validate() && $contrato->validate()){
				$contrato->save();
				$garantia->save();
				Yii::app()->user->setFlash('garantiasMessage',"Garantía agregada a Contrato ".$contrato->nombre." correctamente.");
				$this->refresh();
			}
			else{
				Yii::app()->user->setFlash('garantiasError',CHtml::errorSummary($garantia));
				$this->refresh();
			}
		}
		
		
		
		$this->render(	'agregar',
				array(	'tipos_garantias'=>$tipos_garantias,
						'objetos_garantias'=>$objetos_garantias,
						'contrato'=>$contrato,
						'garantia'=>$garantia,
						'garantias_asociadas'=>$garantias_asociadas));
		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin($id)
	{
		$contrato = Contratos::model()->findByPk($id);
		
		if($contrato->estados_contratos_id != Contratos::ESTADO_ADJUDICADO){
			Yii::app()->user->setFlash('adminError',"Solamente se pueden editar contratos Adjudicados.");
			CController::forward('contratos/admin');
			Yii::app()->end();
		}
		// obtener garantías asociada al contrato
		$garantias_asociadas = $contrato->getGarantias();
		
		$objetos = ObjetosGarantias::model()->findAll();
		$objetos_garantias = array();
		foreach($objetos as $i=>$objeto){
			$objetos_garantias[$i]=$objeto->descripcion;
		}
		
		$tipos = TiposGarantias::model()->findAll();
		$tipos_garantias = array();
		foreach($tipos as $i=>$tipo){
			$tipos_garantias[$i]=$tipo->nombre;
		}
		
		$valid = true;
		$garSave = array();

		if(isset($_POST['Garantias']) && isset($_POST['objeto_garantia']) && isset($_POST['tipo_garantia'])){
			$contrato->modificador_id = Yii::app()->user->id;
			$valid = $contrato->validate();
			
			foreach($_POST['Garantias'] as $i=>$garArray){
				
				$garantia = Garantias::model()->findByPk($garArray['id']);
				$garantia->numero = $garArray['numero'];
				$garantia->monto = Tools::fixPlataDecimales($garArray['monto']);
				$garantia->tipo_monto = $garArray['tipo_monto'];
				$garantia->fecha_vencimiento = Tools::fixFecha($garArray[ 'fecha_vencimiento' ]);
				$garantia->instituciones_id = $garArray['instituciones_id'];
				$garantia->observacion = $garArray['observacion'];
				$garantia->modificador_id = Yii::app()->user->id;
				if(isset($garArray['estado_garantia'])){
					$garantia->estado_garantia = $garArray['estado_garantia'];
					if(!$garantia->estado_garantia){
						$garantia->fecha_devolucion = date("Y-m-d");
					}
					else{
						$garantia->fecha_devolucion = null;
					}
				}
				
				
				//para hacer la transformación por el autocomplete
				$objetoGarantia = ObjetosGarantias::model()->findByAttributes(array('descripcion'=>$_POST['objeto_garantia'][$i]));
				if($objetoGarantia != null){
					$garantia->objetos_garantias_id = $objetoGarantia->id;
				}else{
					$objeto_new = new ObjetosGarantias();
					$objeto_new->descripcion = $_POST['objeto_garantia'][$i];
					if($objeto_new->validate()){
						$objeto_new->save();
						$garantia->objetos_garantias_id = $objeto_new->id;
					}else{
						Yii::app()->user->setFlash('garantiasError',"No se pudo agregar Garantía: Objeto Garantía inválido. Reintente");
						$this->refresh();
					}
				}
				
				$tipoGarantia = TiposGarantias::model()->findByAttributes(array('nombre'=>$_POST['tipo_garantia'][$i]));
				if($tipoGarantia != null){
					$garantia->tipos_garantias_id = $tipoGarantia->id;
				}else{
					$tipo_new = new TiposGarantias();
					$tipo_new->nombre = $_POST['tipo_garantia'][$i];
					if($tipo_new->validate()){
						$tipo_new->save();
						$garantia->tipos_garantias_id = $tipo_new->id;
					}else{
						Yii::app()->user->setFlash('garantiasError',"No se pudo agregar Garantía: Tipo Garantía inválido. Reintente");
						$this->refresh();
					}
				}
				
				$garantia->observacion = Tools::sacaPorcentaje($garantia->observacion);
				$valid = $garantia->validate();	
				
				$garSave[$i]=$garantia;		
			}	
				
			if($valid){
				
				foreach($garSave as $gar){
					$gar->save();
				}
				$contrato->save();
				
				Yii::app()->user->setFlash('garantiasMessage',"Cambios grabados con éxito.");
				$this->refresh();
			}
			else{
				Yii::app()->user->setFlash('garantiasError',"No se pudo agregar Garantía. Reintente");
				$this->refresh();
			}
		
			
		}		
		
		
		$this->render('editGarantia',
				array(	'contrato'=>$contrato, 
						'garantias_asociadas'=>$garantias_asociadas,
						'tipos_garantias'=>$tipos_garantias,
						'objetos_garantias'=>$objetos_garantias ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Garantias the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Garantias::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Garantias $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='garantias-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
