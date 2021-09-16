<?php

class ChipaxController extends Controller
{

    public function actionAdd()
	{
		$hash = Yii::app()->request->getQuery('hash');
		$local_hash = Tools::chipaxSecret(0);
        $intentos = 1;
        while ($local_hash != $hash && $intentos <= 30) {
            $local_hash = Tools::chipaxSecret($intentos);
            $intentos++;
        }
        if ($hash != $local_hash) {
            die("Hash incorrecto");
        }

		$gasto = json_decode(file_get_contents("php://input"), true);
		echo json_encode($gasto);
	}
}
