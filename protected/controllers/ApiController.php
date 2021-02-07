<?php

class ApiController extends Controller
{

	public function actionLogin(){
		$usuario = Yii::app()->request->getPost('usuario');
		$clave = Yii::app()->request->getPost('clave');
		echo Yii::app()->oauth->login($usuario, $clave);
	}

}
