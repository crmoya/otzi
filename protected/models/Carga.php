<?php

class Carga{

	public function gastos()
	{
		$errores = [];
		ini_set("memory_limit", "-1");
		set_time_limit(0);
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
				$resultado = Tools::getExpenses($i);
				if ($primero) {
					$limite = (int)$resultado->Records->Pages;
					$primero = false;
				}
				$expenses = $resultado->Expenses;
				foreach ($expenses as $expense) {
					//solo traer gastos aprobados
					/*if((int)$expense->Status != 1){
						$no_aprobados++;
						continue;
					}*/

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
										$gasto_completa->litros_combustible = $extra_gasto->value;
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
								$gasto_completa->save();
							}
							if ($gasto->tax > 0 && ($gasto_completa->iva == '' || $gasto_completa->iva == null)) {
								$gasto_completa->iva = $gasto->tax;
							}
							if ($gasto->other_taxes > 0) {
								$gasto_completa->impuesto_especifico = $gasto->other_taxes;
							}
							$gasto_completa->save();	
						}

						//ahora que ya agregué todos los campos extras a gasto_completa,
						//puedo agregar el total_calculado

						$gasto_completa = GastoCompleta::model()->findByAttributes(['gasto_id' => $gasto->id]);
						if (!isset($gasto_completa)) {
							$gasto_completa = new GastoCompleta();
							$gasto_completa->gasto_id = $gasto->id;
							$gasto_completa->save();
						}
						//para combustibles
						if ($gasto->expense_policy_id == GastoCompleta::POLICY_COMBUSTIBLES) {
							//para factura
							if (trim($gasto_completa->tipo_documento) == 'Factura Combustible' || trim($gasto_completa->tipo_documento) == 'Factura afecta') {
								$gasto_completa->total_calculado = (int)$gasto_completa->impuesto_especifico + (int)$gasto_completa->iva + (int)$gasto_completa->monto_neto;
								$gasto_completa->save();
							}
							//para boleta
							if (trim($gasto_completa->tipo_documento) == 'Boleta') {
								$gasto_completa->total_calculado = (int)$gasto->total;
								$gasto_completa->save();
							}
						} else {
							//para factura afecta
							if (trim($gasto_completa->tipo_documento) == 'Factura afecta') {
								$gasto_completa->total_calculado = (int)($gasto_completa->monto_neto * 1.19);
								$gasto_completa->iva = $gasto->total - $gasto_completa->monto_neto;
								$gasto_completa->save();
							} else {
								$gasto_completa->total_calculado = (int)$gasto->total;
								$gasto_completa->save();
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
			}
		} catch (Exception $e) {
			echo "Excepción: " . $e;
		}
	}



	public function informes()
	{
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		try {

			//elimino todo lo anterior
			InformeGasto::model()->deleteAll();


			//LO PRIMERO ES TRAER LOS INFORMES, PUES NO TIENEN DEPENDENCIAS

			//TRAER INFORMES
			$limite = 1;
			$primero = true;
			$errores = [];
			$informes = 0;


			for ($i = 1; $i <= $limite; $i++) {
				$resultado = Tools::getReports($i);
				if ($primero) {
					$limite = (int)$resultado->Records->Pages;
					$primero = false;
				}
				$reports = $resultado->ExpenseReports;
				foreach ($reports as $report) {
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


		} catch (Exception $e) {
			echo "Excepción: " . $e;
		}
	}

}