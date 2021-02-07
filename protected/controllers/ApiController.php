<?php

class ApiController extends Controller
{

	public function actionLogin(){
		header('Content-type: application/json');
		$usuario = Yii::app()->request->getPost('usuario');
		$clave = Yii::app()->request->getPost('clave');
		echo Yii::app()->oauth->login($usuario, $clave);
	}

	public function actionObtenerdatos(){
		header('Content-type: application/json');
		$token = Yii::app()->request->getPost('token');
		if(isset($token) && strlen($token)>3){
			$usuario = Usuario::model()->findByAttributes(['token'=>$token]);
			if(isset($usuario)){
				echo CJSON::encode([
					'status'=>'OK',
					'email' => $usuario->email,
					'nombre' => $usuario->nombre,
					'rol' => $usuario->rol,
				]);
				die;
			}			
		}
		echo CJSON::encode(['status'=>'ERROR']);
	}

	public function actionInsertardatos(){
		$token = Yii::app()->request->getPost('token');
		header('Content-type: application/json');
		$token = Yii::app()->request->getPost('token');
		if(isset($token) && strlen($token)>3){
			$usuario = Usuario::model()->findByAttributes(['token'=>$token]);
			if(isset($usuario)){
				$nombre = Yii::app()->request->getPost('nombre');
				$telefono = Yii::app()->request->getPost('telefono');
				$fecha_nacimiento = Yii::app()->request->getPost('fecha_nacimiento');
				if(isset($nombre) && isset($telefono) && isset($fecha_nacimiento)){
					echo CJSON::encode(['status'=>'OK']);
					die;
				}
			}
		}
		echo CJSON::encode(['status'=>'ERROR']);
	}
}
