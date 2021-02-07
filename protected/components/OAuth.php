<?php

class OAuth extends CApplicationComponent
{
	public function login($usuario, $clave)
	{
		$appuser = Usuario::model()->findByAttributes(['user'=>$usuario,'clave'=>sha1($clave)]);
		if(isset($appuser)){
			if($appuser->rol == 'operativo'){
				$token = $this->getToken($appuser->id);
				return CJSON::encode(['status'=>'OK','token'=>$token]);
			}
		}
		return CJSON::encode(['status'=>'ERROR']);
	}


	private function getToken($userid){
		$token = $this->generateRandomString();
		$user = Usuario::model()->findByAttributes(['token'=>$token]);
		while(isset($user)){
			$token = $this->generateRandomString();
			$user = Usuario::model()->findByAttributes(['token'=>$token]);
		}
		$usuario = Usuario::model()->findByPk($userid);
		$usuario->token = $token;
		$usuario->save();
		return $token;
	}

	private function generateRandomString($length = 60) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
}