<?php

class VisualizadorController extends Controller
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
				'actions'=>array('index','flujos'),
				'users'=>array('@'),
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
			$model=new Contratos('search');
			$model->unsetAttributes();
			$this->render("//visualizador/informe",array('model'=>$model));
		}


	}
	
	/**
	 * Esta acción genera los gráficos que relacionan a los flujos programados con los flujos reales
	 */
	 public function actionFlujos()
	 {
	 	if(Yii::app()->user->isGuest){
			$this->actionLogin();
		}
		else{
			$model=new Contratos('search');
			$model->unsetAttributes();
			
			// Datos personalizados
			$categories = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio');
			$series = array(array('Flujo Real',1,2,3,4,5,6,7),array('Flujo Programado',4,3,2,1,7,6,5));
			
			$this->render("//visualizador/graficador",array('title'=>'Comparador de Flujos',
															'title_y'=>'Flujo',
															'type'=>'line',
															'categories'=>$categories,
															'serie'=> $series));
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
	
}