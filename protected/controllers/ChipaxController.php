<?php

class ChipaxController extends Controller
{

    public function actionAdd()
	{
        $connection= Yii::app()->db;
		$transaction=$connection->beginTransaction();
        $respuesta = json_encode(['status'=>'OK','message' => ""]);
        try {
            $hash = Yii::app()->request->getQuery('hash');
            $local_hash = Tools::chipaxSecret(0);
            $intentos = 1;
            while ($local_hash != $hash && $intentos <= 30) {
                $local_hash = Tools::chipaxSecret($intentos);
                $intentos++;
            }
            if ($hash != $local_hash) {
                throw new Exception("Hash incorrecto");
            }
    
            $gastoJson = json_decode(file_get_contents("php://input"), true);
            $rs= Yii::app()->db->createCommand('select max(id) as id from gasto')->queryAll();
            $maxid = 0;
            foreach($rs as $row){
                $maxid = $row['id'];
            }
            $nextid = 1000000000;
            if((int)$maxid >= 1000000000){
                $nextid = $maxid + 1;
            }
            $gasto = new Gasto();
            $gasto->id = $nextid;
            $gasto->status = 1;
            $gasto->tax = 0;
            $gasto->other_taxes = 0;
            $gasto->category_group = "";
            $gasto->category_code = "";
            $gasto->report_id = null;
            $gasto->expense_policy_id = null;
            $gasto->chipax = 1;
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
                    case "neto":
                        $gasto->net = $value;
                        $gasto->total = $value; 
                        $gastoCompleta->monto_neto = $value;
                        $gastoCompleta->total_calculado = $value; 
                        break;
                    case "cantidad":
                        $gastoCompleta->cantidad = $value;
                        break;
                    case "faena_seleccionada":
                        $gastoCompleta->centro_costo_faena = $value;
                        break;
                    case "rendidor_seleccionado":
                        $gastoCompleta->nombre_quien_rinde = $value;
                        break;
                    case "nro_documento":
                        $gastoCompleta->nro_documento = $value;
                        break;
                    case "rut_proveedor":
                        $gastoCompleta->rut_proveedor = $value;
                        break;
                    case "tipo_documento_seleccionado":
                        $gastoCompleta->tipo_documento = $value;
                        break;
                    case "unidad_seleccionada":
                        $gastoCompleta->unidad = $value;
                        break;
                    case "vehiculo_seleccionado":
                        $gastoCompleta->vehiculo_equipo = $value;
                        break;
                    default:
                        break;
                }
            }
             
            if(!$gasto->save()){
                throw new Exception("No se pudo crear el gasto");
            } 
            if(!$gastoCompleta->save()){
                throw new Exception("No se pudo crear el gasto completo");
            }        
            $transaction->commit();  
            
        }catch(Exception $e){
            $respuesta = json_encode(['status'=>'ERROR', 'message' => $e->getMessage()]);
            $transaction->rollback();
        }

		echo $respuesta;

	}
}
