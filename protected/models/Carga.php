<?php

class Carga{


	public function rindeGastos()
	{
		$connection= Yii::app()->db;
		$transaction=$connection->beginTransaction();
		$errores = [];
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		try {

			//elimino todo lo anterior
			CombustibleRindegasto::model()->deleteAll();
			NocombustibleRindegasto::model()->deleteAll();

			//además elimino las compras de repuestos que hayan sido ingresadas por rindegastos
			CompraRepuestoCamionPropio::model()->deleteAllByAttributes(['rindegastos'=>1]);
			CompraRepuestoCamionArrendado::model()->deleteAllByAttributes(['rindegastos'=>1]);
			CompraRepuestoEquipoPropio::model()->deleteAllByAttributes(['rindegastos'=>1]);
			CompraRepuestoEquipoArrendado::model()->deleteAllByAttributes(['rindegastos'=>1]);

			//además elimino las compras de repuestos que hayan sido ingresadas por rindegastos
			CargaCombCamionPropio::model()->deleteAllByAttributes(['rindegastos'=>1]);
			CargaCombCamionArrendado::model()->deleteAllByAttributes(['rindegastos'=>1]);
			CargaCombEquipoPropio::model()->deleteAllByAttributes(['rindegastos'=>1]);
			CargaCombEquipoArrendado::model()->deleteAllByAttributes(['rindegastos'=>1]);


			//INGRESO LOS GASTOS DE COMBUSTIBLE DE ACUERDO A LA TABLA GASTO_COMPLETA
			$gastos = Gasto::model()->findAllByAttributes(['expense_policy_id'=>GastoCompleta::POLICY_COMBUSTIBLES,'status'=>1]);
			foreach($gastos as $gasto){
				$gastoCompleta = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
				if(isset($gastoCompleta)){
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
					$faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
					if(isset($faenaRG)){
						$combustible->faena_id = $faenaRG->faena_id;
					}
					else{
						$combustible->faena_id = 0;
					}

					//asociar gasto a report de carga de combustible
					//según el tipo de report, busco si hay uno para la fecha 
					if($tipo_report == "CP"){
						$cargaComb = new CargaCombCamionPropio();
						$cargaComb->petroleoLts = floatval($gastoCompleta->litros_combustible);
						$cargaComb->kmCarguio = floatval($gastoCompleta->km_carguio);
						$cargaComb->guia = "";
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
						$cargaComb->cuenta = " ";
						$cargaComb->nombre_proveedor = $gasto->supplier;
						$cargaComb->rut_proveedor = $gastoCompleta->rut_proveedor;
						$cargaComb->observaciones = "Registro de Rindegastos";
						$cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
						$cargaComb->rindegastos = 1;

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
						//si puedo guardar la compra, enlazo el registro de rindegastos
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
						$cargaComb->observaciones = "Registro de Rindegastos";
						$cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
						if(isset($faenaRG)){
							$cargaComb->tipoCombustible_id = $tipoCombustibleRG->tipoCombustible_id;
						}
						else{
							$cargaComb->tipoCombustible_id = 0;
						}
						$cargaComb->supervisorCombustible_id = 0;	
						$cargaComb->rindegastos = 1;

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
								$report->ordenCompra = "OC - RindeGastos";
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
						//si puedo guardar la compra, enlazo el registro de rindegastos
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
						$cargaComb->observaciones = "Registro de Rindegastos";
						$cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);							
						$cargaComb->rindegastos = 1;

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
						$cargaComb->observaciones = "Registro de Rindegastos";
						$cargaComb->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);							
						$cargaComb->rindegastos = 1;


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
								$report->ordenCompra = "OC - RindeGastos";
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
			}

			//INGRESO LOS GASTOS DIFERENTES DE COMBUSTIBLE DE ACUERDO A LA TABLA GASTO_COMPLETA
			$gastos = Gasto::model()->findAllByAttributes(['expense_policy_id'=>GastoCompleta::POLICY_MAQUINARIA,'status'=>1]);
			foreach($gastos as $gasto){
				$gastoCompleta = GastoCompleta::model()->findByAttributes(['gasto_id'=>$gasto->id]);
				if(isset($gastoCompleta)){
					$nocombustible = new NocombustibleRindegasto();
					$nocombustible->status = $gasto->status;
					$nocombustible->fecha = $gasto->issue_date;
					$nocombustible->total = intval($gasto->net);
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

					$faenaRG = FaenaRindegasto::model()->findByAttributes(['faena'=>$gastoCompleta->centro_costo_faena]);
					if(isset($faenaRG)){
						$nocombustible->faena_id = $faenaRG->faena_id;
					}
					else{
						$nocombustible->faena_id = 0;
					}

					//asociar gasto a report de compra de repuesto
					//según el tipo de report, busco si hay uno para la fecha 
					if($tipo_report == "CP"){
						$compra = new CompraRepuestoCamionPropio();
						$repuesto = $gasto->note;
						if(strlen($repuesto) > 200){
							$repuesto = substr($repuesto,0,200);
						}
						if($repuesto == ""){
							$repuesto = "Sin descripción - Rindegastos";
						}
						$compra->repuesto = $repuesto;
						$compra->montoNeto = (int)$gastoCompleta->monto_neto;
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
						$compra->observaciones = "Registro de Rindegastos";
						$compra->rut_rinde = " ";
						$compra->cuenta = " ";
						$compra->nombre_proveedor = $gasto->supplier;
						$compra->rut_proveedor = $gastoCompleta->rut_proveedor;
						$compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
						$compra->rindegastos = 1;


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
							$repuesto = "Sin descripción - Rindegastos";
						}
						$compra->repuesto = $repuesto;
						$compra->montoNeto = (int)$gastoCompleta->monto_neto;
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
						$compra->observaciones = "Registro de Rindegastos";
						$compra->rut_rinde = " ";
						$compra->cuenta = " ";
						$compra->nombre_proveedor = $gasto->supplier;
						$compra->rut_proveedor = $gastoCompleta->rut_proveedor;
						$compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
						$compra->rindegastos = 1;


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
									$report->ordenCompra = "OC - RindeGastos";
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
								$report->ordenCompra = "OC - RindeGastos";
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
							$repuesto = "Sin descripción - Rindegastos";
						}
						$compra->repuesto = $repuesto;
						$compra->montoNeto = (int)$gastoCompleta->monto_neto;
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
						$compra->observaciones = "Registro de Rindegastos";
						$compra->rut_rinde = " ";
						$compra->cuenta = " ";
						$compra->nombre_proveedor = $gasto->supplier;
						$compra->rut_proveedor = $gastoCompleta->rut_proveedor;
						$compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
						$compra->rindegastos = 1;


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
							$repuesto = "Sin descripción - Rindegastos";
						}
						$compra->repuesto = $repuesto;
						$compra->montoNeto = (int)$gastoCompleta->monto_neto;
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
						$compra->observaciones = "Registro de Rindegastos";
						$compra->rut_rinde = " ";
						$compra->cuenta = " ";
						$compra->nombre_proveedor = $gasto->supplier;
						$compra->rut_proveedor = $gastoCompleta->rut_proveedor;
						$compra->tipo_documento = Tools::traducirTipoDocumento($gastoCompleta->tipo_documento);
						$compra->rindegastos = 1;


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
									$report->ordenCompra = "OC - RindeGastos";
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
								$report->ordenCompra = "OC - RindeGastos";
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
			}


			if (count($errores) > 0) {
				echo "<pre>";
				print_r($errores);
				echo "</pre>";
				$transaction->rollback();
			}
			else{
				$transaction->commit();
			}
		} catch (Exception $e) {
			echo "Excepción: " . $e;
			$transaction->rollback();
		}

	}

	public function gastos()
	{
		$errores = [];
		ini_set("memory_limit", "-1");
		set_time_limit(0);

		$connection= Yii::app()->db;
		$transaction=$connection->beginTransaction();

		try {

			//elimino todo lo anterior
			GastoImagen::model()->deleteAll();
			ExtraGasto::model()->deleteAll();
			GastoCompleta::model()->deleteAll();
			Gasto::model()->deleteAll();


			//AHORA TRAIGO LOS
			//GASTOS Y SUS DERIVADOS

			$limite = 1;
			$primero = true;
			for ($i = 1; $i <= $limite; $i++) {
				$resultadoCombustible = Tools::getExpenses($i, GastoCompleta::POLICY_COMBUSTIBLES);
				if ($primero) {
					$limite = (int)$resultadoCombustible->Records->Pages;
					$primero = false;
				}
				$expensesCombustible = $resultadoCombustible->Expenses;
				foreach ($expensesCombustible as $expense) {
					//chequear traer solo gastos de POLITICA DE COMBUSTIBLES
					if((int)$expense->ExpensePolicyId != GastoCompleta::POLICY_COMBUSTIBLES){
						continue;
					}
					$gasto = Gasto::model()->findByPk($expense->Id);
					if (isset($gasto)) {
						continue;
					}
					$gasto = new Gasto();
					$gasto->id = $expense->Id;
					$gasto->status = $expense->Status;
					$gasto->supplier = $expense->Supplier;
					$gasto->issue_date = $expense->IssueDate;
					$gasto->net = $expense->Net;
					$gasto->total = $expense->Total;
					$gasto->tax = $expense->Tax;
					$gasto->other_taxes = $expense->OtherTaxes;
					$gasto->category = $expense->Category;
					$gasto->category_group = $expense->CategoryGroup;
					$gasto->category_code = $expense->CategoryCode;
					$gasto->note = $expense->Note;
					$gasto->expense_policy_id = (int)$expense->ExpensePolicyId;
					$gasto->report_id = (int)$expense->ReportId;

					if (!$gasto->save()) {
						$errores[] = $gasto->errors;
					} else {
						if (isset($expense->ExtraFields)) {
							foreach ($expense->ExtraFields as $extra) {
								$extra_gasto = new ExtraGasto();
								$extra_gasto->name = $extra->Name;
								$extra_gasto->value = $extra->Value;
								$extra_gasto->code = $extra->Code;
								$extra_gasto->gasto_id = $gasto->id;
								if (!$extra_gasto->save()) {
									$errores[] = $extra_gasto->errors;
								} else {
									if (strtolower(trim($extra_gasto->name)) == "10% impto. retenido") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->retenido = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "cantidad") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->cantidad = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "centro de costo / faena") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->centro_costo_faena = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "departamento") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->departamento = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "faena") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->faena = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Impuesto específico") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->impuesto_especifico = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "iva") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->iva = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Km.Carguío") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->km_carguio = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "litros combustible") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->litros_combustible = (float)$extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "monto neto") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->monto_neto = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "nombre quien rinde") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->nombre_quien_rinde = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Número de Documento") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->nro_documento = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Período de Planilla") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->periodo_planilla = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "rut proveedor") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->rut_proveedor = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "supervisor de combustible") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->supervisor_combustible = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "tipo de documento") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->tipo_documento = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "unidad") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->unidad = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "vehiculo o equipo") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$valor = $extra_gasto->value;
										if ($extra_gasto->value == "Taller (vehículo virtual para registrar tosdos los gastos excepto combustibles que son de Taller y que no pueden cargarse directamente a ningún equipo o vehic.)") {
											$valor = "Taller (virtual no comb.)";
										}
										$gasto_completa->vehiculo_equipo = $valor;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Vehículo Oficina Central") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->vehiculo_oficina_central = $extra_gasto->value;

										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
								}
							}
						}

						//ya agregué los campos extra, pero debo ver si hay que rectificar el impuesto específico y el IVA 
						if (isset($gasto_completa)) {
							if ($gasto->net > 0 && ($gasto_completa->monto_neto == '' || $gasto_completa->monto_neto == null)) {
								$gasto_completa->monto_neto = $gasto->net;
								if(!$gasto_completa->save()){
									$errores[] = $gasto_completa->errors;
								}
							}
							if ($gasto->tax > 0 && ($gasto_completa->iva == '' || $gasto_completa->iva == null)) {
								$gasto_completa->iva = $gasto->tax;
							}
							if ($gasto->other_taxes > 0) {
								$gasto_completa->impuesto_especifico = $gasto->other_taxes;
							}
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}	
						}

						//ahora que ya agregué todos los campos extras a gasto_completa,
						//puedo agregar el total_calculado

						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
						if (!isset($gasto_completa)) {
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}
						}

						//para factura
						if (trim($gasto_completa->tipo_documento) == 'Factura Combustible' || trim($gasto_completa->tipo_documento) == 'Factura afecta') {
							$gasto_completa->total_calculado = (int)$gasto_completa->impuesto_especifico + (int)$gasto_completa->iva + (int)$gasto_completa->monto_neto;
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}
						}
						//para boleta o vale
						if (trim($gasto_completa->tipo_documento) == 'Boleta' || trim($gasto_completa->tipo_documento) == 'Vale') {
							$gasto_completa->total_calculado = (int)$gasto->total;
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}
						}

						if (isset($expense->Files)) {
							$files = $expense->Files;
							foreach ($files as $file) {
								$gasto_imagen = new GastoImagen();
								$gasto_imagen->file_name = $file->FileName;
								$gasto_imagen->extension = $file->Extension;
								$gasto_imagen->original = $file->Original;
								$gasto_imagen->large = $file->Large;
								$gasto_imagen->medium = $file->Medium;
								$gasto_imagen->small = $file->Small;
								$gasto_imagen->gasto_id = $gasto->id;
								if (!$gasto_imagen->save()) {
									$errores[] = $gasto_imagen->errors;
								}
							}
						}
					}
					//END GASTOS Y SUS DERIVADOS
				}
			}

			$limite = 1;
			$primero = true;
			for ($i = 1; $i <= $limite; $i++) {
				$resultadoMaquinaria = Tools::getExpenses($i, GastoCompleta::POLICY_MAQUINARIA);
				if ($primero) {
					$limite = (int)$resultadoMaquinaria->Records->Pages;
					$primero = false;
				}
				$expensesMaquinaria = $resultadoMaquinaria->Expenses;
				foreach ($expensesMaquinaria as $expense) {
					//chequear traer solo gastos de POLITICA DE MAQUINARIA
					if((int)$expense->ExpensePolicyId != GastoCompleta::POLICY_MAQUINARIA){
						continue;
					}
					$gasto = Gasto::model()->findByPk($expense->Id);
					if (isset($gasto)) {
						continue;
					}
					$gasto = new Gasto();
					$gasto->id = $expense->Id;
					$gasto->status = $expense->Status;
					$gasto->supplier = $expense->Supplier;
					$gasto->issue_date = $expense->IssueDate;
					$gasto->net = $expense->Net;
					$gasto->total = $expense->Total;
					$gasto->tax = $expense->Tax;
					$gasto->other_taxes = $expense->OtherTaxes;
					$gasto->category = $expense->Category;
					$gasto->category_group = $expense->CategoryGroup;
					$gasto->category_code = $expense->CategoryCode;
					$gasto->note = $expense->Note;
					$gasto->expense_policy_id = (int)$expense->ExpensePolicyId;
					$gasto->report_id = (int)$expense->ReportId;

					if (!$gasto->save()) {
						$errores[] = $gasto->errors;
					} else {
						if (isset($expense->ExtraFields)) {
							foreach ($expense->ExtraFields as $extra) {
								$extra_gasto = new ExtraGasto();
								$extra_gasto->name = $extra->Name;
								$extra_gasto->value = $extra->Value;
								$extra_gasto->code = $extra->Code;
								$extra_gasto->gasto_id = $gasto->id;
								if (!$extra_gasto->save()) {
									$errores[] = $extra_gasto->errors;
								} else {
									if (strtolower(trim($extra_gasto->name)) == "10% impto. retenido") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->retenido = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "cantidad") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->cantidad = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "centro de costo / faena") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->centro_costo_faena = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "departamento") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->departamento = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "faena") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->faena = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Impuesto específico") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->impuesto_especifico = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "iva") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->iva = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Km.Carguío") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->km_carguio = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "litros combustible") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->litros_combustible = (float)$extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "monto neto") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->monto_neto = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "nombre quien rinde") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->nombre_quien_rinde = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Número de Documento") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->nro_documento = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Período de Planilla") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->periodo_planilla = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "rut proveedor") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->rut_proveedor = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "supervisor de combustible") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->supervisor_combustible = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "tipo de documento") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->tipo_documento = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "unidad") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->unidad = $extra_gasto->value;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (strtolower(trim($extra_gasto->name)) == "vehiculo o equipo") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$valor = $extra_gasto->value;
										if ($extra_gasto->value == "Taller (vehículo virtual para registrar tosdos los gastos excepto combustibles que son de Taller y que no pueden cargarse directamente a ningún equipo o vehic.)") {
											$valor = "Taller (virtual no comb.)";
										}
										$gasto_completa->vehiculo_equipo = $valor;
										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
									if (trim($extra_gasto->name) == "Vehículo Oficina Central") {
										$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
										if (!isset($gasto_completa)) {
											$gasto_completa = new GastoCompleta();
											$gasto_completa->gasto_id = $gasto->id;
										}
										$gasto_completa->vehiculo_oficina_central = $extra_gasto->value;

										if (!$gasto_completa->save()) {
											$errores[] = $gasto_completa->errors;
										}
									}
								}
							}
						}

						//ya agregué los campos extra, pero debo ver si hay que rectificar el impuesto específico y el IVA 
						if (isset($gasto_completa)) {
							if ($gasto->net > 0 && ($gasto_completa->monto_neto == '' || $gasto_completa->monto_neto == null)) {
								$gasto_completa->monto_neto = $gasto->net;
								if(!$gasto_completa->save()){
									$errores[] = $gasto_completa->errors;
								}
							}
							if ($gasto->tax > 0 && ($gasto_completa->iva == '' || $gasto_completa->iva == null)) {
								$gasto_completa->iva = $gasto->tax;
							}
							if ($gasto->other_taxes > 0) {
								$gasto_completa->impuesto_especifico = $gasto->other_taxes;
							}
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}	
						}

						//ahora que ya agregué todos los campos extras a gasto_completa,
						//puedo agregar el total_calculado

						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
						if (!isset($gasto_completa)) {
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}
						}

						//para factura afecta
						if (trim($gasto_completa->tipo_documento) == 'Factura afecta') {
							$gasto_completa->total_calculado = (int)($gasto_completa->monto_neto * 1.19);
							$gasto_completa->iva = $gasto->total - $gasto_completa->monto_neto;
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}
						} else {
							$gasto_completa->total_calculado = (int)$gasto->total;
							if(!$gasto_completa->save()){
								$errores[] = $gasto_completa->errors;
							}
						}

						if (isset($expense->Files)) {
							$files = $expense->Files;
							foreach ($files as $file) {
								$gasto_imagen = new GastoImagen();
								$gasto_imagen->file_name = $file->FileName;
								$gasto_imagen->extension = $file->Extension;
								$gasto_imagen->original = $file->Original;
								$gasto_imagen->large = $file->Large;
								$gasto_imagen->medium = $file->Medium;
								$gasto_imagen->small = $file->Small;
								$gasto_imagen->gasto_id = $gasto->id;
								if (!$gasto_imagen->save()) {
									$errores[] = $gasto_imagen->errors;
								}
							}
						}
					}
					//END GASTOS Y SUS DERIVADOS
				}
			}

			//ELIMINO LOS EXTRAS PUES YA NO SIRVEN
			ExtraGasto::model()->deleteAll();

			if (count($errores) > 0) {
				echo "<pre>";
				print_r($errores);
				echo "</pre>";
				$transaction->rollback();
			}
			else{
				$transaction->commit();
			}
		} catch (Exception $e) {
			echo "Excepción: " . $e;
			$transaction->rollback();
		}
	}

	public function informes()
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		$connection= Yii::app()->db;
		$transaction=$connection->beginTransaction();
		$errores = [];
		$informes = 0;
		try {

			//elimino todo lo anterior
			InformeGasto::model()->deleteAll();
			//LO PRIMERO ES TRAER LOS INFORMES, PUES NO TIENEN DEPENDENCIAS

			//TRAER INFORMES
			//DE COMBUSTIBLES
			$limite = 1;
			$primero = true;
			for ($i = 1; $i <= $limite; $i++) {
				$resultadoCombustible = Tools::getReports($i, GastoCompleta::POLICY_COMBUSTIBLES);
				if ($primero) {
					$limite = (int)$resultadoCombustible->Records->Pages;
					$primero = false;
				}
				$reportsCombustible = $resultadoCombustible->ExpenseReports;
				foreach ($reportsCombustible as $report) {
					//chequear traer solo informes de gastos de POLITICA DE COMBUSTIBLES
					if((int)$report->PolicyId != GastoCompleta::POLICY_COMBUSTIBLES){
						continue;
					}
					$informe = InformeGasto::model()->findByPk($report->Id);
					if (isset($informe)) {
						continue;
					}
					$informe = new InformeGasto();
					$informe->id = $report->Id;
					$informe->titulo = $report->Title;
					$informe->numero = $report->ReportNumber;
					$informe->fecha_envio = $report->SendDate;
					$informe->fecha_cierre = $report->CloseDate;
					$informe->nombre_empleado = $report->EmployeeName;
					$informe->rut_empleado = $report->EmployeeIdentification;
					$informe->aprobado_por = $report->ApproverName;
					$informe->politica_id = $report->PolicyId;
					$informe->politica = $report->PolicyName;
					$informe->estado = $report->Status;
					$informe->total = $report->ReportTotal;
					$informe->total_aprobado = $report->ReportTotalApproved;
					$informe->nro_gastos = $report->NbrExpenses;
					$informe->nro_gastos_aprobados = $report->NbrApprovedExpenses;
					$informe->nro_gastos_rechazados = $report->NbrRejectedExpenses;
					$informe->nota = $report->Note;
					if (!$informe->save()) {
						$errores[] = $informe->errors;
					} else {
						$informes++;
					}
				}
			}

			//DE MAQUINARIA
			$limite = 1;
			$primero = true;
			for ($i = 1; $i <= $limite; $i++) {
				$resultadoMaquinaria = Tools::getReports($i, GastoCompleta::POLICY_MAQUINARIA);
				if ($primero) {
					$limite = (int)$resultadoMaquinaria->Records->Pages;
					$primero = false;
				}
				$reportsMaquinaria = $resultadoMaquinaria->ExpenseReports;
				foreach ($reportsMaquinaria as $report) {
					//chequear traer solo informes de gastos de POLITICA DE MAQUINARIA
					if((int)$report->PolicyId != GastoCompleta::POLICY_MAQUINARIA){
						continue;
					}
					$informe = InformeGasto::model()->findByPk($report->Id);
					if (isset($informe)) {
						continue;
					}
					$informe = new InformeGasto();
					$informe->id = $report->Id;
					$informe->titulo = $report->Title;
					$informe->numero = $report->ReportNumber;
					$informe->fecha_envio = $report->SendDate;
					$informe->fecha_cierre = $report->CloseDate;
					$informe->nombre_empleado = $report->EmployeeName;
					$informe->rut_empleado = $report->EmployeeIdentification;
					$informe->aprobado_por = $report->ApproverName;
					$informe->politica_id = $report->PolicyId;
					$informe->politica = $report->PolicyName;
					$informe->estado = $report->Status;
					$informe->total = $report->ReportTotal;
					$informe->total_aprobado = $report->ReportTotalApproved;
					$informe->nro_gastos = $report->NbrExpenses;
					$informe->nro_gastos_aprobados = $report->NbrApprovedExpenses;
					$informe->nro_gastos_rechazados = $report->NbrRejectedExpenses;
					$informe->nota = $report->Note;
					if (!$informe->save()) {
						$errores[] = $informe->errors;
					} else {
						$informes++;
					}
				}
			}

			//END TRAER INFORMES

			if (count($errores) > 0) {
				echo "<pre>";
				print_r($errores);
				echo "</pre>";
				$transaction->rollback();
			}
			else{
				$transaction->commit();
			}

		} catch (Exception $e) {
			echo "Excepción: " . $e;
			$transaction->rollback();
		}
	}

}