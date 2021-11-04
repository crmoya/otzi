<?php

class ChipaxController extends Controller
{

    public function actionAdd()
	{
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $connection= Yii::app()->db;
		$respuesta = json_encode(['status'=>'OK','message' => ""]);
        $errores = [];
        $transaction=$connection->beginTransaction();
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


            echo "<pre>";
            print_r($gastoJson);
            die;

            $vehiculos = $gastoJson['vehiculos_seleccionados'];
            foreach($vehiculos as $vehiculo){
                $vehiculo_nombre = $vehiculo['nombre'];
                $vehiculo_valor = $vehiculo['valor'];

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

                $gastoImagen = new GastoImagen();
                $gastoImagen->gasto_id = $gasto->id;

                $gastoCompleta = new GastoCompleta();
                $gastoCompleta->gasto_id = $gasto->id;
                $gastoCompleta->vehiculo_equipo = $vehiculo_nombre;
                $gasto->net = $vehiculo_valor;
                $gasto->total = $vehiculo_valor; 
                $gastoCompleta->monto_neto = $vehiculo_valor;
                $gastoCompleta->total_calculado = $vehiculo_valor; 

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
                        case "html_factura":
                            $gastoImagen->file_name = $value;
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
                if(!$gastoImagen->save()){
                    throw new Exception("No se pudo crear la imagen del gasto");
                }

                //procesar gasto
                //primero filtrar por categoría
                $categoria = trim($gasto->category);
                
                //COMBUSTIBLES
                if(in_array($categoria,Tools::CATEGORIAS_COMBUSTIBLES_CHIPAX)){
                    $combustible = new CombustibleRindegasto();
                    $combustible->fecha = $gasto->issue_date;
                    $combustible->litros = floatval($gastoCompleta->litros_combustible);
                    $combustible->total = intval($gastoCompleta->iva) + intval($gasto->net) + intval($gastoCompleta->impuesto_especifico);
                    $combustible->gasto_completa_id = intval($gastoCompleta->id);
                    $combustible->status = $gasto->status;
                    $vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$gastoCompleta->vehiculo_equipo]);

                    $tipoCombustibleRG = TipoCombustibleRG::model()->findByAttributes(['tipocombustible'=>$gasto->category_code]);
                    if(isset($tipoCombustibleRG)){
                        $combustible->tipoCombustible_id = $tipoCombustibleRG->tipoCombustible_id;
                    }

                    $tipo_report = "";
                    if(isset($vehiculoRG)){
                        if($vehiculoRG->camionpropio_id != null){
                            $combustible->camionpropio_id = $vehiculoRG->camionpropio_id;
                            $tipo_report = "CP";
                        }
                        if($vehiculoRG->camionarrendado_id != null){
                            $combustible->camionarrendado_id = $vehiculoRG->camionarrendado_id;
                            $tipo_report = "CA";
                        }
                        if($vehiculoRG->equipopropio_id != null){
                            $combustible->equipopropio_id = $vehiculoRG->equipopropio_id;
                            $tipo_report = "EP";
                        }
                        if($vehiculoRG->equipoarrendado_id != null){
                            $combustible->equipoarrendado_id = $vehiculoRG->equipoarrendado_id;
                            $tipo_report = "EA";
                        }
                    }
                    else{
                        throw new Exception("No se encuentra en SAM el vehículo al cual está asociado el gasto. Por favor, valide que la vinculación a vehículo esté correcta.");
                    }
                    $combustible->faena_id = $gastoCompleta->centro_costo_faena;
                    /*
                    $faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
                    if(isset($faenaRG)){
                        $combustible->faena_id = $faenaRG->faena_id;
                    }
                    else{
                        $combustible->faena_id = 0;
                    }
                    */

                    //asociar gasto a report de carga de combustible
                    //según el tipo de report, busco si hay uno para la fecha 
                    if($tipo_report == "CP"){
                        $cargaComb = new CargaCombCamionPropio();
                        $cargaComb->petroleoLts = floatval($gastoCompleta->litros_combustible);
                        $cargaComb->kmCarguio = floatval($gastoCompleta->km_carguio);
                        $cargaComb->guia = "";
                        $cargaComb->factura = substr($gastoCompleta->nro_documento,0,45);
                        $cargaComb->fechaRendicion = $gasto->issue_date;
                        $cargaComb->precioUnitario = 0;
                        $cargaComb->valorTotal = (int)$gastoCompleta->monto_neto + (int)$gastoCompleta->impuesto_especifico;
                        
                        if(isset($faenaRG)){
                            $cargaComb->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $cargaComb->faena_id = 0;
                        }
                        if(isset($tipoCombustibleRG)){
                            $cargaComb->tipoCombustible_id = $tipoCombustibleRG->tipoCombustible_id;
                        }
                        else{
                            $cargaComb->tipoCombustible_id = 0;
                        }

                        $cargaComb->supervisorCombustible_id = 0;
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $cargaComb->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $cargaComb->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $cargaComb->rut_rinde = " ";
                        $informe = InformeGasto::model()->findByPk($gasto->report_id);
                        if(isset($informe)){
                            $cargaComb->numero = $informe->numero;
                        }
                        $cargaComb->cuenta = " ";
                        $cargaComb->nombre_proveedor = $gasto->supplier;
                        $cargaComb->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $cargaComb->observaciones = "Registro de Chipax";
                        $cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $cargaComb->rindegastos = 0;

                        //busco un report al que asociar la carga:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = RCamionPropio::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'camionPropio_id'=>$vehiculoRG->camionpropio_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = RCamionPropio::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->save();
                                    break;
                                }
                                $report = RCamionPropio::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new RCamionPropio();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->camionPropio_id = (int)$vehiculoRG->camionpropio_id;
                                $report->chofer_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->usuario_id = 213;
                                $report->horometro_inicial = 0;
                                $report->horometro_final = 0;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = RCamionPropio::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'camionPropio_id'=>$vehiculoRG->camionpropio_id]);
                            }
                        }
                        $cargaComb->rCamionPropio_id = $report->id;
                        //si puedo guardar la carga, enlazo el registro de rindegastos
                        if($cargaComb->save()){
                            $combustible->carga_id = $cargaComb->id;
                        }
                        else{
                            $errores[] = $cargaComb->errors;
                        }
                    }
                    else if($tipo_report == "CA"){
                        $cargaComb = new CargaCombCamionArrendado();
                        $cargaComb->petroleoLts = floatval($gastoCompleta->litros_combustible);
                        $cargaComb->kmCarguio = floatval($gastoCompleta->km_carguio);
                        $cargaComb->guia = "";
                        $cargaComb->fechaRendicion = $gasto->issue_date;
                        $cargaComb->factura = substr($gastoCompleta->nro_documento,0,45);
                        $cargaComb->precioUnitario = 0;
                        $cargaComb->valorTotal = (int)$gastoCompleta->monto_neto + (int)$gastoCompleta->impuesto_especifico;
                        if(isset($faenaRG)){
                            $cargaComb->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $cargaComb->faena_id = 0;
                        }
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $cargaComb->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $cargaComb->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $cargaComb->rut_rinde = " ";
                        $informe = InformeGasto::model()->findByPk($gasto->report_id);
                        if(isset($informe)){
                            $cargaComb->numero = $informe->numero;
                        }
                        $cargaComb->nombre_proveedor = $gasto->supplier;
                        $cargaComb->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $cargaComb->observaciones = "Registro de Chipax";
                        $cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        if(isset($faenaRG)){
                            $cargaComb->tipoCombustible_id = $tipoCombustibleRG->tipoCombustible_id;
                        }
                        else{
                            $cargaComb->tipoCombustible_id = 0;
                        }
                        $cargaComb->supervisorCombustible_id = 0;	
                        $cargaComb->rindegastos = 0;

                        //busco un report al que asociar la carga:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = RCamionArrendado::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'camionArrendado_id'=>$vehiculoRG->camionarrendado_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = RCamionArrendado::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->save();
                                    break;
                                }
                                $report = RCamionArrendado::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new RCamionArrendado();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->camionArrendado_id = (int)$vehiculoRG->camionarrendado_id;
                                $report->ordenCompra = "OC - Chipax";
                                $report->chofer_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->usuario_id = 213;
                                $report->horometro_inicial = 0;
                                $report->horometro_final = 0;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = RCamionArrendado::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'camionArrendado_id'=>$vehiculoRG->camionarrendado_id]);
                            }
                        }
                        $cargaComb->rCamionArrendado_id = $report->id;
                        //si puedo guardar la carga, enlazo el registro de rindegastos
                        if($cargaComb->save()){
                            $combustible->carga_id = $cargaComb->id;
                        }
                        else{
                            $errores[] = $cargaComb->errors;
                        }
                    }
                    else if($tipo_report == "EP"){
                        $cargaComb = new CargaCombEquipoPropio();
                        $cargaComb->petroleoLts = floatval($gastoCompleta->litros_combustible);
                        $cargaComb->hCarguio = 0;
                        $cargaComb->guia = "";
                        $cargaComb->fechaRendicion = $gasto->issue_date;
                        $cargaComb->factura = substr($gastoCompleta->nro_documento,0,45);
                        $cargaComb->precioUnitario = 0;
                        $cargaComb->valorTotal = (int)$gastoCompleta->monto_neto + (int)$gastoCompleta->impuesto_especifico;
                        if(isset($faenaRG)){
                            $cargaComb->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $cargaComb->faena_id = 0;
                        }
                        if(isset($faenaRG)){
                            $cargaComb->tipoCombustible_id = $tipoCombustibleRG->tipoCombustible_id;
                        }
                        else{
                            $cargaComb->tipoCombustible_id = 0;
                        }
                        $cargaComb->supervisorCombustible_id = 0;
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $cargaComb->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $cargaComb->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $cargaComb->rut_rinde = " ";
                        $informe = InformeGasto::model()->findByPk($gasto->report_id);
                        if(isset($informe)){
                            $cargaComb->numero = $informe->numero;
                        }
                        $cargaComb->nombre_proveedor = $gasto->supplier;
                        $cargaComb->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $cargaComb->observaciones = "Registro de Chipax";
                        $cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);							
                        $cargaComb->rindegastos = 0;

                        //busco un report al que asociar la compra:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = REquipoPropio::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'equipoPropio_id'=>$vehiculoRG->equipopropio_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = REquipoPropio::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->save();
                                    break;
                                }
                                $report = REquipoPropio::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new REquipoPropio();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->equipoPropio_id = (int)$vehiculoRG->equipopropio_id;
                                $report->hInicial = 0;
                                $report->hFinal = 0;
                                $report->horas = 0;
                                $report->operador_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->horasGps = 0;
                                $report->usuario_id = 213;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = REquipoPropio::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'equipoPropio_id'=>$vehiculoRG->equipopropio_id]);
                            }
                        }
                        $cargaComb->rEquipoPropio_id = $report->id;						
                        //si puedo guardar la carga, enlazo el registro de rindegastos
                        if($cargaComb->save()){
                            $combustible->carga_id = $cargaComb->id;
                        }
                        else{
                            $errores[] = $cargaComb->errors;
                        }
                    }
                    else if($tipo_report == "EA"){
                        $cargaComb = new CargaCombEquipoArrendado();
                        $cargaComb->petroleoLts = floatval($gastoCompleta->litros_combustible);
                        $cargaComb->hCarguio = 0;
                        $cargaComb->guia = "";
                        $cargaComb->fechaRendicion = $gasto->issue_date;
                        $cargaComb->factura = substr($gastoCompleta->nro_documento,0,45);
                        $cargaComb->precioUnitario = 0;
                        $cargaComb->valorTotal = (int)$gastoCompleta->monto_neto + (int)$gastoCompleta->impuesto_especifico;
                        if(isset($faenaRG)){
                            $cargaComb->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $cargaComb->faena_id = 0;
                        }
                        if(isset($faenaRG)){
                            $cargaComb->tipoCombustible_id = $tipoCombustibleRG->tipoCombustible_id;
                        }
                        else{
                            $cargaComb->tipoCombustible_id = 0;
                        }
                        $cargaComb->supervisorCombustible_id = 0;
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $cargaComb->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $cargaComb->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $cargaComb->rut_rinde = " ";
                        $informe = InformeGasto::model()->findByPk($gasto->report_id);
                        if(isset($informe)){
                            $cargaComb->numero = $informe->numero;
                        }
                        $cargaComb->nombre_proveedor = $gasto->supplier;
                        $cargaComb->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $cargaComb->observaciones = "Registro de Chipax";
                        $cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);							
                        $cargaComb->rindegastos = 0;


                        //busco un report al que asociar la carga:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = REquipoArrendado::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'equipoArrendado_id'=>$vehiculoRG->equipoarrendado_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la carga a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = REquipoArrendado::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->save();
                                    break;
                                }
                                $report = REquipoArrendado::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new REquipoArrendado();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->equipoArrendado_id = (int)$vehiculoRG->equipoarrendado_id;
                                $report->ordenCompra = "OC - Chipax";
                                $report->hInicial = 0;
                                $report->hFinal = 0;
                                $report->horas = 0;
                                $report->operador_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->horasGps = 0;
                                $report->usuario_id = 213;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = REquipoArrendado::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'equipoArrendado_id'=>$vehiculoRG->equipoarrendado_id]);
                            }
                        }
                        $cargaComb->rEquipoArrendado_id = $report->id;						
                        //si puedo guardar la carga, enlazo el registro de rindegastos
                        if($cargaComb->save()){
                            $combustible->carga_id = $cargaComb->id;
                        }
                        else{
                            $errores[] = $cargaComb->errors;
                        }
                    }

                    if(!$combustible->save()){
                        $errores[] = $combustible->errors;
                    }
                }
                //en remuneraciones y otros gastos falta filtrar por BHE o BTE
                //falta folio en gasto
                //probablemente la descripción esté mal

                //CATEGORIAS_REMUNERACIONES_RINDEGASTOS
                else if(in_array($categoria,Tools::CATEGORIAS_REMUNERACIONES_CHIPAX)){
                    $remuneracionRG = new RemuneracionRindegasto();
                    $remuneracionRG->status = $gasto->status;
                    $remuneracionRG->fecha = $gasto->issue_date;
                    $remuneracionRG->total = intval($gasto->net) + intval($gastoCompleta->iva);
                    $remuneracionRG->gasto_completa_id = intval($gastoCompleta->id);
                    $tipo_report = "";

                    $vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$gastoCompleta->vehiculo_equipo]);

                    if(isset($vehiculoRG)){
                        if($vehiculoRG->camionpropio_id != null){
                            $remuneracionRG->camionpropio_id = $vehiculoRG->camionpropio_id;
                            $tipo_report = "CP";
                        }
                        if($vehiculoRG->camionarrendado_id != null){
                            $remuneracionRG->camionarrendado_id = $vehiculoRG->camionarrendado_id;
                            $tipo_report = "CA";
                        }
                        if($vehiculoRG->equipopropio_id != null){
                            $remuneracionRG->equipopropio_id = $vehiculoRG->equipopropio_id;
                            $tipo_report = "EP";
                        }
                        if($vehiculoRG->equipoarrendado_id != null){
                            $remuneracionRG->equipoarrendado_id = $vehiculoRG->equipoarrendado_id;
                            $tipo_report = "EA";
                        }
                    }
                    else{
                        throw new Exception("No se encuentra en SAM el vehículo al cual está asociado el gasto. Por favor, valide que la vinculación a vehículo esté correcta.");
                    }

                    $remuneracionRG->faena_id = $gastoCompleta->centro_costo_faena;
                    /*
                    $faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
                    if(isset($faenaRG)){
                        $remuneracionRG->faena_id = $faenaRG->faena_id;
                    }
                    else{
                        $remuneracionRG->faena_id = 0;
                    }
                    */

                    //asociar gasto a report de remuneración
                    //según el tipo de report, busco si hay uno para la fecha 
                    if($tipo_report == "CP"){
                        $remuneracion = new RemuneracionCamionPropio();
                        $descripcion = $gasto->note;
                        if(strlen($descripcion) > 200){
                            $descripcion = substr($descripcion,0,200);
                        }
                        if($descripcion == ""){
                            $descripcion = "Sin descripción - Chipax";
                        }
                        $remuneracion->descripcion = $descripcion;
                        $remuneracion->montoNeto = (int)$gastoCompleta->monto_neto;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $remuneracion->cantidad = (float)$cantidad;
                        $remuneracion->fechaRendicion = $gasto->issue_date;
                        $remuneracion->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $remuneracion->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $remuneracion->faena_id = 0;
                        }
                        $remuneracion->documento = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $remuneracion->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $remuneracion->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $remuneracion->observaciones = "Registro de Chipax";
                        $remuneracion->rut_rinde = " ";
                        $remuneracion->cuenta = $gasto->category_code." - ".$gasto->category;
                        $remuneracion->nombre_proveedor = $gasto->supplier;
                        $remuneracion->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $remuneracion->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $remuneracion->rindegastos = 0;


                        //busco un report al que asociar la remuneración:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = RCamionPropio::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'camionPropio_id'=>$vehiculoRG->camionpropio_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la remuneración a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = RCamionPropio::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->camionPropio_id = (int)$vehiculoRG->camionpropio_id;
                                    $report->chofer_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->usuario_id = 213;
                                    $report->horometro_inicial = 0;
                                    $report->horometro_final = 0;
                                    $report->save();
                                    break;
                                }
                                $report = RCamionPropio::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new RCamionPropio();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->camionPropio_id = (int)$vehiculoRG->camionpropio_id;
                                $report->chofer_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->usuario_id = 213;
                                $report->horometro_inicial = 0;
                                $report->horometro_final = 0;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = RCamionPropio::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'camionPropio_id'=>$vehiculoRG->camionpropio_id]);
                            }
                        }
                        $remuneracion->rCamionPropio_id = $report->id;
                        //si puedo guardar la remuneracion, enlazo el registro de rindegastos
                        if($remuneracion->save()){
                            $remuneracionRG->remuneracion_id = $remuneracion->id;
                        }
                        else{
                            $errores[] = $remuneracion->errors;
                        }
                    }
                    else if($tipo_report == "CA"){
                        $remuneracion = new RemuneracionCamionArrendado();
                        $descripcion = $gasto->note;
                        if(strlen($descripcion) > 200){
                            $descripcion = substr($descripcion,0,200);
                        }
                        if($descripcion == ""){
                            $descripcion = "Sin descripción - Chipax";
                        }
                        $remuneracion->descripcion = $descripcion;
                        $remuneracion->montoNeto = (int)$gastoCompleta->monto_neto;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $remuneracion->fechaRendicion = $gasto->issue_date;
                        $remuneracion->cantidad = (float)$cantidad;
                        $remuneracion->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $remuneracion->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $remuneracion->faena_id = 0;
                        }
                        $remuneracion->documento = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $remuneracion->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $remuneracion->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $remuneracion->observaciones = "Registro de Chipax";
                        $remuneracion->rut_rinde = " ";
                        $remuneracion->cuenta = $gasto->category_code." - ".$gasto->category;
                        $remuneracion->nombre_proveedor = $gasto->supplier;
                        $remuneracion->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $remuneracion->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $remuneracion->rindegastos = 0;


                        //busco un report al que asociar la remuneración:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = RCamionArrendado::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'camionArrendado_id'=>$vehiculoRG->camionarrendado_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la remuneración a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = RCamionArrendado::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->camionArrendado_id = (int)$vehiculoRG->camionarrendado_id;
                                    $report->ordenCompra = "OC - Chipax";
                                    $report->chofer_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->usuario_id = 213;
                                    $report->horometro_inicial = 0;
                                    $report->horometro_final = 0;
                                    $report->save();
                                    break;
                                }
                                $report = RCamionArrendado::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new RCamionArrendado();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->camionArrendado_id = (int)$vehiculoRG->camionarrendado_id;
                                $report->ordenCompra = "OC - Chipax";
                                $report->chofer_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->usuario_id = 213;
                                $report->horometro_inicial = 0;
                                $report->horometro_final = 0;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = RCamionArrendado::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'camionArrendado_id'=>$vehiculoRG->camionarrendado_id]);
                            }
                        }
                        $remuneracion->rCamionArrendado_id = $report->id;						
                        //si puedo guardar la remuneración, enlazo el registro de rindegastos
                        if($remuneracion->save()){
                            $remuneracionRG->remuneracion_id = $remuneracion->id;
                        }
                        else{
                            $errores[] = $remuneracion->errors;
                        }
                    }
                    else if($tipo_report == "EP"){
                        $remuneracion = new RemuneracionEquipoPropio();
                        $descripcion = $gasto->note;
                        if(strlen($descripcion) > 200){
                            $descripcion = substr($descripcion,0,200);
                        }
                        if($descripcion == ""){
                            $descripcion = "Sin descripción - Chipax";
                        }
                        $remuneracion->descripcion = $descripcion;
                        $remuneracion->montoNeto = (int)$gastoCompleta->monto_neto;
                        $remuneracion->fechaRendicion = $gasto->issue_date;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $remuneracion->cantidad = (float)$cantidad;
                        $remuneracion->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $remuneracion->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $remuneracion->faena_id = 0;
                        }
                        $remuneracion->documento = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $remuneracion->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $remuneracion->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $remuneracion->observaciones = "Registro de Chipax";
                        $remuneracion->rut_rinde = " ";
                        $remuneracion->cuenta = $gasto->category_code." - ".$gasto->category;
                        $remuneracion->nombre_proveedor = $gasto->supplier;
                        $remuneracion->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $remuneracion->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $remuneracion->rindegastos = 0;


                        //busco un report al que asociar la remuneración:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = REquipoPropio::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'equipoPropio_id'=>$vehiculoRG->equipopropio_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la remuneración a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = REquipoPropio::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->equipoPropio_id = (int)$vehiculoRG->equipopropio_id;
                                    $report->hInicial = 0;
                                    $report->hFinal = 0;
                                    $report->horas = 0;
                                    $report->operador_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->horasGps = 0;
                                    $report->usuario_id = 213;
                                    $report->save();
                                    break;
                                }
                                $report = REquipoPropio::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new REquipoPropio();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                
                                $report->equipoPropio_id = (int)$vehiculoRG->equipopropio_id;
                                $report->hInicial = 0;
                                $report->hFinal = 0;
                                $report->horas = 0;
                                $report->operador_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->horasGps = 0;
                                $report->usuario_id = 213;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = REquipoPropio::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'equipoPropio_id'=>$vehiculoRG->equipopropio_id]);
                            }
                        }
                        $remuneracion->rEquipoPropio_id = $report->id;						
                        //si puedo guardar la remuneración, enlazo el registro de rindegastos
                        if($remuneracion->save()){
                            $remuneracionRG->remuneracion_id = $remuneracion->id;
                        }
                        else{
                            $errores[] = $remuneracion->errors;
                        }
                    }
                    else if($tipo_report == "EA"){
                        $remuneracion = new RemuneracionEquipoArrendado();
                        $descripcion = $gasto->note;
                        if(strlen($descripcion) > 200){
                            $descripcion = substr($descripcion,0,200);
                        }
                        if($descripcion == ""){
                            $descripcion = "Sin descripción - Chipax";
                        }
                        $remuneracion->descripcion = $descripcion;
                        $remuneracion->montoNeto = (int)$gastoCompleta->monto_neto;
                        $remuneracion->fechaRendicion = $gasto->issue_date;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $remuneracion->cantidad = (float)$cantidad;
                        $remuneracion->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $remuneracion->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $remuneracion->faena_id = 0;
                        }
                        $remuneracion->documento = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $remuneracion->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $remuneracion->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $remuneracion->observaciones = "Registro de Chipax";
                        $remuneracion->rut_rinde = " ";
                        $remuneracion->cuenta = $gasto->category_code." - ".$gasto->category;
                        $remuneracion->nombre_proveedor = $gasto->supplier;
                        $remuneracion->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $remuneracion->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $remuneracion->rindegastos = 0;


                        //busco un report al que asociar la remuneración:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = REquipoArrendado::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'equipoArrendado_id'=>$vehiculoRG->equipoarrendado_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la remuneración a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = REquipoArrendado::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->ordenCompra = "OC - Chipax";
                                    $report->equipoArrendado_id = (int)$vehiculoRG->equipoarrendado_id;
                                    $report->hInicial = 0;
                                    $report->hFinal = 0;
                                    $report->horas = 0;
                                    $report->operador_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->horasGps = 0;
                                    $report->usuario_id = 213;
                                    $report->save();
                                    break;
                                }
                                $report = REquipoArrendado::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new REquipoArrendado();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->equipoArrendado_id = (int)$vehiculoRG->equipoarrendado_id;
                                $report->ordenCompra = "OC - Chipax";
                                $report->hInicial = 0;
                                $report->hFinal = 0;
                                $report->horas = 0;
                                $report->operador_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->horasGps = 0;
                                $report->usuario_id = 213;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = REquipoArrendado::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'equipoArrendado_id'=>$vehiculoRG->equipoarrendado_id]);
                            }
                        }
                        $remuneracion->rEquipoArrendado_id = $report->id;						
                        //si puedo guardar la remuneración, enlazo el registro de rindegastos
                        if($remuneracion->save()){
                            $remuneracionRG->remuneracion_id = $remuneracion->id;
                        }
                        else{
                            $errores[] = $remuneracion->errors;
                        }
                    }

                    if(!$remuneracionRG->save()){
                        $errores[] = $remuneracionRG->errors;
                    }
                }

                //OTROS GASTOS
                else{
                    
                    $nocombustible = new NocombustibleRindegasto();
                    $nocombustible->status = $gasto->status;
                    $nocombustible->fecha = $gasto->issue_date;
                    $nocombustible->total = intval($gasto->net) + intval($gastoCompleta->iva);
                    $nocombustible->gasto_completa_id = intval($gastoCompleta->id);
                    $tipo_report = "";

                    $vehiculoRG = VehiculoRindegastos::model()->findByAttributes(['vehiculo'=>$gastoCompleta->vehiculo_equipo]);
                    if(isset($vehiculoRG)){
                        if($vehiculoRG->camionpropio_id != null){
                            $nocombustible->camionpropio_id = $vehiculoRG->camionpropio_id;
                            $tipo_report = "CP";
                        }
                        if($vehiculoRG->camionarrendado_id != null){
                            $nocombustible->camionarrendado_id = $vehiculoRG->camionarrendado_id;
                            $tipo_report = "CA";
                        }
                        if($vehiculoRG->equipopropio_id != null){
                            $nocombustible->equipopropio_id = $vehiculoRG->equipopropio_id;
                            $tipo_report = "EP";
                        }
                        if($vehiculoRG->equipoarrendado_id != null){
                            $nocombustible->equipoarrendado_id = $vehiculoRG->equipoarrendado_id;
                            $tipo_report = "EA";
                        }
                    }
                    else{
                        throw new Exception("No se encuentra en SAM el vehículo al cual está asociado el gasto. Por favor, valide que la vinculación a vehículo esté correcta.");
                    }

                    $nocombustible->faena_id = $gastoCompleta->centro_costo_faena;
                    /*
                    $faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
                    if(isset($faenaRG)){
                        $nocombustible->faena_id = $faenaRG->faena_id;
                    }
                    else{
                        $nocombustible->faena_id = 0;
                    }
                    */

                    //asociar gasto a report de compra de repuesto
                    //según el tipo de report, busco si hay uno para la fecha 
                    if($tipo_report == "CP"){
                        $compra = new CompraRepuestoCamionPropio();
                        $repuesto = $gasto->note;
                        if(strlen($repuesto) > 200){
                            $repuesto = substr($repuesto,0,200);
                        }
                        if($repuesto == ""){
                            $repuesto = "Sin descripción - Chipax";
                        }
                        $compra->repuesto = $repuesto;
                        $compra->montoNeto = (int)$gastoCompleta->monto_neto;
                        $compra->fechaRendicion = $gasto->issue_date;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $compra->cantidad = (float)$cantidad;
                        $compra->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $compra->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $compra->faena_id = 0;
                        }
                        $compra->factura = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $compra->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $compra->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $compra->observaciones = "Registro de Chipax";
                        $compra->rut_rinde = " ";
                        $compra->cuenta = $gasto->category_code." - ".$gasto->category;
                        $compra->nombre_proveedor = $gasto->supplier;
                        $compra->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $compra->rindegastos = 0;


                        //busco un report al que asociar la compra:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = RCamionPropio::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'camionPropio_id'=>$vehiculoRG->camionpropio_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = RCamionPropio::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->camionPropio_id = (int)$vehiculoRG->camionpropio_id;
                                    $report->chofer_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->usuario_id = 213;
                                    $report->horometro_inicial = 0;
                                    $report->horometro_final = 0;
                                    $report->save();
                                    break;
                                }
                                $report = RCamionPropio::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new RCamionPropio();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->camionPropio_id = (int)$vehiculoRG->camionpropio_id;
                                $report->chofer_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->usuario_id = 213;
                                $report->horometro_inicial = 0;
                                $report->horometro_final = 0;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = RCamionPropio::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'camionPropio_id'=>$vehiculoRG->camionpropio_id]);
                            }
                        }
                        $compra->rCamionPropio_id = $report->id;
                        //si puedo guardar la compra, enlazo el registro de rindegastos
                        if($compra->save()){
                            $nocombustible->compra_id = $compra->id;
                        }
                        else{
                            $errores[] = $compra->errors;
                        }
                        
                    }
                    else if($tipo_report == "CA"){
                        $compra = new CompraRepuestoCamionArrendado();
                        $repuesto = $gasto->note;
                        if(strlen($repuesto) > 200){
                            $repuesto = substr($repuesto,0,200);
                        }
                        if($repuesto == ""){
                            $repuesto = "Sin descripción - Chipax";
                        }
                        $compra->repuesto = $repuesto;
                        $compra->montoNeto = (int)$gastoCompleta->monto_neto;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $compra->cantidad = (float)$cantidad;
                        $compra->fechaRendicion = $gasto->issue_date;
                        $compra->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $compra->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $compra->faena_id = 0;
                        }
                        $compra->factura = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $compra->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $compra->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $compra->observaciones = "Registro de Chipax";
                        $compra->rut_rinde = " ";
                        $compra->cuenta = $gasto->category_code." - ".$gasto->category;
                        $compra->nombre_proveedor = $gasto->supplier;
                        $compra->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $compra->rindegastos = 0;


                        //busco un report al que asociar la compra:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = RCamionArrendado::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'camionArrendado_id'=>$vehiculoRG->camionarrendado_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = RCamionArrendado::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->camionArrendado_id = (int)$vehiculoRG->camionarrendado_id;
                                    $report->ordenCompra = "OC - Chipax";
                                    $report->chofer_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->usuario_id = 213;
                                    $report->horometro_inicial = 0;
                                    $report->horometro_final = 0;
                                    $report->save();
                                    break;
                                }
                                $report = RCamionArrendado::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new RCamionArrendado();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->camionArrendado_id = (int)$vehiculoRG->camionarrendado_id;
                                $report->ordenCompra = "OC - Chipax";
                                $report->chofer_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->usuario_id = 213;
                                $report->horometro_inicial = 0;
                                $report->horometro_final = 0;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = RCamionArrendado::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'camionArrendado_id'=>$vehiculoRG->camionarrendado_id]);
                            }
                        }
                        $compra->rCamionArrendado_id = $report->id;						
                        //si puedo guardar la compra, enlazo el registro de rindegastos
                        if($compra->save()){
                            $nocombustible->compra_id = $compra->id;
                        }
                        else{
                            $errores[] = $compra->errors;
                        }
                    }
                    else if($tipo_report == "EP"){
                        $compra = new CompraRepuestoEquipoPropio();
                        $repuesto = $gasto->note;
                        if(strlen($repuesto) > 200){
                            $repuesto = substr($repuesto,0,200);
                        }
                        if($repuesto == ""){
                            $repuesto = "Sin descripción - Chipax";
                        }
                        $compra->repuesto = $repuesto;
                        $compra->montoNeto = (int)$gastoCompleta->monto_neto;
                        $compra->fechaRendicion = $gasto->issue_date;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $compra->cantidad = (float)$cantidad;
                        $compra->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $compra->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $compra->faena_id = 0;
                        }
                        $compra->factura = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $compra->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $compra->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $compra->observaciones = "Registro de Chipax";
                        $compra->rut_rinde = " ";
                        $compra->cuenta = $gasto->category_code." - ".$gasto->category;
                        $compra->nombre_proveedor = $gasto->supplier;
                        $compra->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $compra->rindegastos = 0;


                        //busco un report al que asociar la compra:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = REquipoPropio::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'equipoPropio_id'=>$vehiculoRG->equipopropio_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = REquipoPropio::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->equipoPropio_id = (int)$vehiculoRG->equipopropio_id;
                                    $report->hInicial = 0;
                                    $report->hFinal = 0;
                                    $report->horas = 0;
                                    $report->operador_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->horasGps = 0;
                                    $report->usuario_id = 213;
                                    $report->save();
                                    break;
                                }
                                $report = REquipoPropio::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new REquipoPropio();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                
                                $report->equipoPropio_id = (int)$vehiculoRG->equipopropio_id;
                                $report->hInicial = 0;
                                $report->hFinal = 0;
                                $report->horas = 0;
                                $report->operador_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->horasGps = 0;
                                $report->usuario_id = 213;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = REquipoPropio::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'equipoPropio_id'=>$vehiculoRG->equipopropio_id]);
                            }
                        }
                        $compra->rEquipoPropio_id = $report->id;						
                        //si puedo guardar la compra, enlazo el registro de rindegastos
                        if($compra->save()){
                            $nocombustible->compra_id = $compra->id;
                        }
                        else{
                            $errores[] = $compra->errors;
                        }
                    }
                    else if($tipo_report == "EA"){
                        $compra = new CompraRepuestoEquipoArrendado();
                        $repuesto = $gasto->note;
                        if(strlen($repuesto) > 200){
                            $repuesto = substr($repuesto,0,200);
                        }
                        if($repuesto == ""){
                            $repuesto = "Sin descripción - Chipax";
                        }
                        $compra->repuesto = $repuesto;
                        $compra->montoNeto = (int)$gastoCompleta->monto_neto;
                        $compra->fechaRendicion = $gasto->issue_date;
                        $cantidad = str_replace(",",".",$gastoCompleta->cantidad);
                        $compra->cantidad = (float)$cantidad;
                        $compra->unidad = Tools::convertUnidad($gastoCompleta->unidad);
                        if(isset($faenaRG)){
                            $compra->faena_id = $faenaRG->faena_id;
                        }
                        else{
                            $compra->faena_id = 0;
                        }
                        $compra->factura = substr($gastoCompleta->nro_documento,0,45);
                        if(strlen($gastoCompleta->nombre_quien_rinde) > 100){
                            $compra->nombre = substr($gastoCompleta->nombre_quien_rinde,0,100);
                        }
                        else{
                            $compra->nombre = $gastoCompleta->nombre_quien_rinde;
                        }
                        $compra->observaciones = "Registro de Chipax";
                        $compra->rut_rinde = " ";
                        $compra->cuenta = $gasto->category_code." - ".$gasto->category;
                        $compra->nombre_proveedor = $gasto->supplier;
                        $compra->rut_proveedor = $gastoCompleta->rut_proveedor;
                        $compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
                        $compra->rindegastos = 0;


                        //busco un report al que asociar la compra:
                        //inicio buscando para la fecha del gasto
                        $fecha = new DateTime($gasto->issue_date);
                        $ultimoDiaMes = date("Y-m-t",strtotime($gasto->issue_date));
                        $report = REquipoArrendado::model()->findByAttributes(['fecha'=>$gasto->issue_date, 'equipoArrendado_id'=>$vehiculoRG->equipoarrendado_id]);
                        while($report == null){
                            //si no hay report busco para el día siguiente,
                            //sino para el subsiguiente, hasta llegar al día sábado, si no hay para el sábado
                            //creo un report para el sábado y asocio la compra a ese
                            //el día posterior, hasta el sábado de esa semana.
                            //pero si llega al fin de mes, lo agrego al fin de mes
                            $dow = date('w',strtotime($fecha->format("Y-m-d")));
                            if($dow == 6 || $ultimoDiaMes == $fecha->format("Y-m-d")){
                                //sábado o fin de mes
                                $report = REquipoArrendado::model()->findByAttributes([
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(isset($report)){
                                    //si ya había sido creado un report con ese campo reporte, lo traslado a fin de mes (que sería anterior)	
                                    //fix fin de mes
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->ordenCompra = "OC - Chipax";
                                    $report->equipoArrendado_id = (int)$vehiculoRG->equipoarrendado_id;
                                    $report->hInicial = 0;
                                    $report->hFinal = 0;
                                    $report->horas = 0;
                                    $report->operador_id = 0;
                                    $report->panne = 0;
                                    $report->iniPanne = "";
                                    $report->finPanne = "";
                                    $report->minPanne = 0;
                                    $report->horasGps = 0;
                                    $report->usuario_id = 213;
                                    $report->save();
                                    break;
                                }
                                $report = REquipoArrendado::model()->findByAttributes([
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'reporte' => "*".$gasto->id,
                                    'observaciones' => "Report creado automáticamente para asociación con RindeGastos",
                                ]);
                                if(!isset($report)){
                                    $report = new REquipoArrendado();
                                    $report->fecha = $fecha->format('Y-m-d');
                                    $report->reporte = "*".$gasto->id;
                                    $report->observaciones = "Report creado automáticamente para asociación con RindeGastos";
                                }
                                $report->equipoArrendado_id = (int)$vehiculoRG->equipoarrendado_id;
                                $report->ordenCompra = "OC - Chipax";
                                $report->hInicial = 0;
                                $report->hFinal = 0;
                                $report->horas = 0;
                                $report->operador_id = 0;
                                $report->panne = 0;
                                $report->iniPanne = "";
                                $report->finPanne = "";
                                $report->minPanne = 0;
                                $report->horasGps = 0;
                                $report->usuario_id = 213;
                                if(!$report->save()){
                                    $errores[] = $report->errors;
                                }
                                break;
                            }
                            else{
                                $fecha->add(new DateInterval('P1D'));
                                $report = REquipoArrendado::model()->findByAttributes(['fecha'=>$fecha->format('Y-m-d'), 'equipoArrendado_id'=>$vehiculoRG->equipoarrendado_id]);
                            }
                        }
                        $compra->rEquipoArrendado_id = $report->id;						
                        //si puedo guardar la compra, enlazo el registro de rindegastos
                        if($compra->save()){
                            $nocombustible->compra_id = $compra->id;
                        }
                        else{
                            $errores[] = $compra->errors;
                        }
                    }

                    if(!$nocombustible->save()){
                        $errores[] = $nocombustible->errors;
                    }

                }

                if(count($errores) > 0){
                    throw new Exception("No se pudo crear la carga de combustible o el report automático para asociar el gasto en SAM.");
                }
            }
            $transaction->commit();
            
        }catch(Exception $e){
            $respuesta = json_encode(['status'=>'ERROR', 'message' => $e->getMessage()]);
            $transaction->rollback();
        }
        

		echo $respuesta;

	}

    public function actionGetFaenas(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);

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

        
        $respuesta = json_encode(['status'=>'ERROR']);
        try {
            $categoriaJson = json_decode(file_get_contents("php://input"), true);
            $categoria = $categoriaJson['categoria'];
            
            $dev = [];
            $faenas = null;
            $es_combustible = in_array($categoria,Tools::CATEGORIAS_COMBUSTIBLES_CHIPAX);
            if($es_combustible){
                $faenas = Faena::model()->findAllByAttributes(['combustible'=>1,'vigente'=>'SÍ']);
            }
            else{
                $faenas = Faena::model()->findAllByAttributes(['vigente'=>'SÍ']);
            }
            foreach($faenas as $faena){
                $dev[] = [
                    'id' => $faena->id,
                    'nombre' => $faena->nombre,
                ];
            }
            $respuesta = json_encode(['status'=>'OK','faenas'=>$dev]);
        }
        catch(Exception $e){
            $respuesta = json_encode(['status'=>'ERROR']);
        }
        echo $respuesta;
    }
}
