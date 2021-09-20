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

		$gastoJson = json_decode(file_get_contents("php://input"), true);
        $rs= Yii::app()->db->createCommand('select max(id) as id from gasto')->queryAll();
        $maxid = 0;
        foreach($rs as $row){
            $maxid = $row['id'];
        }
        $nextid = 90000000000;
        if((int)$maxid > 90000000000){
            $nextid = $maxid + 1;
        }
        $gasto = new Gasto();
        $gasto->id = $nextid;
        $gasto->status = 1;
        $gasto->tax = 0;
        $gasto->other_taxes = 0;
        $gasto->category_group = "";
        $gasto->category_code = "";

        
        $gasto->net = $expense->Net;
        $gasto->report_id = null;
        $gasto->expense_policy_id = null;
        
        
        $gastoCompleta = new GastoCompleta();
        $gastoCompleta->gasto_id = $gasto->id;
        foreach($gastoJson as $key => $value) {
            switch ($key) {
                case "fecha":
                    $gasto->issue_date = $value;
                    break;
                case "nombre_proveedor":
                    $gasto->supplier = $value;
                    break;
                case "categoria":
                    $gasto->category = $value;
                    break;
                case "nota":
                    $gasto->note = $value;
                    break;
                default:
                    echo "fin";
            }
        }
        $gasto->total = $gasto->net;
	}
}
