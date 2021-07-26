<?php

class UsuarioController extends Controller
{
	
	public function behaviors()
    {
        return array(
            'eexcelview'=>array(
                'class'=>'ext.eexcelview.EExcelBehavior',
            ),
        );
    }
    
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
		);
	}

	
	function actionExportar()
	{
		// generate a resultset
		$data = Usuario::model()->findAll();
		
		$this->toExcel($data,
			array('email','nombre','rol','user'),
			'Usuarios',
			array()
		);
		//ExcelExporter::sendAsXLS($data, true, 'ProduccionMaquinaria.xls');
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','admin','delete','exportar'),
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
		$model=new Usuario;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Usuario']))
		{
			$model->attributes=$_POST['Usuario'];
                        $clave = $model->clave;
			$model->clave = sha1($model->clave);
			$titulo = "CREACIÓN DE NUEVO USUARIO EN SISTEMA SAM";
                        $mensaje = "<html>
                                <head>
                                  <title>Creación de nuevo usuario en Sistema SAM</title>
                                </head>
                                <body>
                                    Estimado $model->nombre,<br/><br/>
                                    Le damos la más cordial bienvenida al Sistema de Administración de Maquinarias SAM, de Constructora OTZI SPA<br/>
                                    Le informamos que se asignado una nueva cuenta de usuario para usted.<br/>
                                    Los datos son los siguientes:<br/>
                                    <table>
                                        <tr>
                                            <td><b>Nombre de Usuario:</b></td>
                                            <td>$model->user</td>
                                        </tr>
                                        <tr>
                                            <td><b>Clave:</b></td>
                                            <td>$clave</td>
                                        </tr>
                                        <tr>
                                            <td><b>Rol:</b></td>
                                            <td>$model->rol</td>
                                        </tr>
                                    </table><br/>
                                    Muchas gracias.<br/>
                                    Saludos Cordiales.<br/>
                                </body>
                            </html>";
                
                        
			if($model->save()){
                                
				$auth=Yii::app()->authManager;
				$auth->assign($model->rol,$model->id);
                                
                                $to_email = $model->email;
                                $para = $to_email;
                                $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
                                $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                                $cabeceras .= 'From: Sistema de Administración de Maquinarias SAM' . "\r\n";

                                mail($para, $titulo, $mensaje, $cabeceras);
                                $this->redirect(array('view','id'=>$model->id));
			}
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

		if(isset($_POST['Usuario']))
		{
			$model->attributes=$_POST['Usuario'];
			if($model->save()){
				$usuario = Usuario::model()->findByPk($model->id);
				$rol = $usuario->rol;
				$auth=Yii::app()->authManager;
				$authAssignments = Authassignment::model()->findAllByAttributes(['userid'=>$usuario->id]);
				foreach($authAssignments as $authAssignment){
					$authAssignment->delete();
				}
				$auth->assign($model->rol,$model->id);
				$this->redirect(array('view','id'=>$model->id));
			}
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			//$this->loadModel($id)->delete();

			$usuario = $this->loadModel($id);
			$usuario->vigente = 0;
			$usuario->save();
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Usuario');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Usuario('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Usuario']))
		$model->attributes=$_GET['Usuario'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Usuario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='usuario-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
